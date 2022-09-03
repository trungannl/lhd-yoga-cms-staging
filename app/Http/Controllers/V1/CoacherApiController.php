<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\CoacherRequest;
use App\Http\Resources\CoacherDataResource;
use App\Http\Resources\CoacherResource;
use App\Http\Resources\StudioCoacherResource;
use App\Libraries\Utility;
use App\Repositories\CoacherCheckin\CoacherCheckinRepositoryInterface;
use App\Repositories\CoacherClassData\CoacherClassDataInterface;
use App\Repositories\Studio\StudioRepositoryInterface;
use App\Repositories\Upload\UploadRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use App\Service\SendOtp;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\JWTException;
use Validator;

class CoacherApiController extends Controller
{
    protected $userRepository;
    protected $studioRepository;
    protected $coacherClassDataRepository;
    protected $coacherCheckinRepository;
    protected $uploadRepository;

    public function __construct(UserRepositoryInterface $userRepository, StudioRepositoryInterface $studioRepository, CoacherClassDataInterface $coacherClassDataRepository, CoacherCheckinRepositoryInterface $coacherCheckinRepository, UploadRepositoryInterface $uploadRepository)
    {
        $this->userRepository = $userRepository;
        $this->studioRepository = $studioRepository;
        $this->coacherClassDataRepository = $coacherClassDataRepository;
        $this->coacherCheckinRepository = $coacherCheckinRepository;
        $this->uploadRepository = $uploadRepository;
    }

    public function index(CoacherRequest $coacherRequest)
    {
        $data = $this->userRepository->getAllCoacherWithPaginator($coacherRequest->all());
        return $this->sendResponse(
            new CoacherResource($data),
            'get success',
            200
        );
    }

    public function store(CoacherRequest $coacherRequest)
    {
        try {
            $password = rand(pow(10, 5), pow(10, 6)-1);
            $input = array_merge(
                $coacherRequest->validated(),
                ['password' => Hash::make($password), 'is_coacher' => 1]
            );

            $sendOtp = new SendOtp();
            $status = $sendOtp->send($input['phone'], 'password: '.$password);
            if (!$status) {
                return $this->sendError([], 'Fail to send otp');
            }

            $coacher = $this->userRepository->create($input);
            if($coacherRequest->hasFile('avatar')){
                $coacher->addMedia($input['avatar'])
                    ->toMediaCollection('avatar');
            }

            return $this->sendResponse(
                CoacherResource::make($coacher),
                'Coacher successfully create'
            );
        }
        catch (JWTException $th) {
            return $this->sendError(['error' => 'Unauthorized'], 'Fail to register', 401);
        }
    }

    public function update(CoacherRequest $request, $id)
    {
        $coacher = $this->userRepository->find($id);

        if (empty($coacher)) {
            $this->sendError(['error' => 'Not found!'], 'cannot found this coacher', 404);
        }

        $input  = $request->validated();

        try {
            $coacher = $this->userRepository->update($id, $input);
            if($request->hasFile('avatar')){
                $coacher->addMedia($input['avatar'])->toMediaCollection('avatar');
            }

            return $this->sendResponse(
                CoacherResource::make($coacher),
                'Coacher successfully update'
            );

        } catch (\Exception $e) {
            return $this->sendError(['error' => 'system error'], $e->getMessage(), 401);
        }
    }

    public function destroy($id)
    {
        try {
            $coacher = $this->userRepository->find($id);

            if (empty($coacher)) {
                $this->sendError(['error' => 'Not found!'], 'cannot found this coacher', 404);
            }

            $this->userRepository->destroy($coacher->id);

            return $this->sendResponse(null, 'Coacher successfully removed',200);
        }
        catch (JWTException $th) {
            return $this->sendError(['error' => 'Coacher remove fail'], 'Fail to remove', 401);
        }
    }

    public function show($id)
    {
        try {
            $coacher = $this->userRepository->find($id);

            if (empty($student)) {
                $this->sendError(['error' => 'Not found!'], 'cannot found this coacher', 404);
            }

            return $this->sendResponse(
                CoacherResource::make($coacher),
                'Show coacher'
            );
        }
        catch (JWTException $th) {
            return $this->sendError(['error' => 'cannot found this coacher'], 'Fail to show', 401);
        }
    }

    public function class()
    {
        $user = Auth::guard('api')->user();

        $studios = $this->studioRepository->getListStudioFromCoacher($user->id);

        return $this->sendResponse(
            StudioCoacherResource::collection($studios),
            'get success'
        );
    }

    public function showClass($classId)
    {
        $studio = $this->studioRepository->find($classId);

        return $this->sendResponse(
            StudioCoacherResource::make($studio),
            'get success'
        );
    }

    public function listData($classId)
    {
        $user = Auth::guard('api')->user();

        $studio = $this->studioRepository->find($classId);
        if (empty($studio)) {
            return $this->sendError(['error' => 'Not found!'], 'cannot found this class', 404);
        }

        $data = $this->coacherClassDataRepository->getData($studio->id, $user->id);

        return $this->sendResponse(
            CoacherDataResource::collection($data),
            'get success'
        );
    }

    public function createData(Request $request, $classId)
    {
        $user = Auth::guard('api')->user();

        $studio = $this->studioRepository->find($classId);
        if (empty($studio)) {
            return $this->sendError(['error' => 'Not found!'], 'cannot found this class', 404);
        }

        $validator = Validator::make($request->all(), [
            'description' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendError(['error' => $validator->errors()], 'Fail to create data',401);
        }

        $item = $this->coacherClassDataRepository->create([
            'description' => $request->description,
            'studio_id' => $studio->id,
            'user_id' => $user->id,
            'date' => date('Y-m-d')
        ]);

        if (!empty($request->images)) {
            foreach ($request->images as $imageUuid)  {
                $cacheUpload = $this->uploadRepository->getByUuid($imageUuid);
                $mediaItem = $cacheUpload->getMedia('image')->first();
                $mediaItem->copy($item, 'data-image');
            }
        }

        return $this->sendResponse(
            CoacherDataResource::make($item),
            'Data successfully create'
        );

    }

    public function checkin(Request $request, $classId)
    {
        $user = Auth::guard('api')->user();

        $studio = $this->studioRepository->find($classId);
        if (empty($studio)) {
            return $this->sendError(['error' => 'Not found!'], 'cannot found this class', 404);
        }

        $inputs = $request->all();
        $validator = Validator::make($inputs, [
            'latitude' => 'required',
            'longtitude' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendError(['error' => $validator->errors()], 'Fail to checkin',401);
        }

        $distance = Utility::vincentyGreatCircleDistance($inputs['latitude'], $inputs['longtitude'], $studio->latitude, $studio->longtitude);
        if ($distance <= 200) {
            return $this->sendError(['error' => 'Current location not accurate'], 'Fail to checkin',401);
        }

        $item = $this->coacherCheckinRepository->create([
            'studio_id' => $studio->id,
            'coacher_id' => $user->id,
            'date' => date('Y-m-d'),
            'latitude' => $request->latitude,
            'longtitude' => $request->longtitude
        ]);

        return $this->sendResponse(
            [],
            'Checkin successfully'
        );

    }

    public function salary(Request $request)
    {
        $user = Auth::guard('api')->user();

        $month = Carbon::today()->format('m');
        if ($request->month) {
            $month = $request->month;
        }

        $year = Carbon::today()->format('Y');
        if ($request->year) {
            $year = $request->month;
        }

        $count = $this->coacherCheckinRepository->countCheckinByMonth($user->id, $month, $year);

        $data = [
            'total' => $user->salary * $count,
            'numberOfSessions' => $count,
            'salary' => $user->salary,
        ];

        return $this->sendResponse(
            $data,
            'get success'
        );
    }
}
