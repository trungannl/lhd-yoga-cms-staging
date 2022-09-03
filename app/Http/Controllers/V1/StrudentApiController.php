<?php


namespace App\Http\Controllers\V1;


use App\Http\Controllers\Controller;
use App\Http\Requests\StudentRequest;
use App\Http\Resources\StudentCollection;
use App\Http\Resources\StudioResource;
use App\Http\Resources\UserResource;
use App\Repositories\Attendance\AttendanceRepositoryInterface;
use App\Repositories\Studio\StudioRepository;
use App\Repositories\StudioUser\StudioUserRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use App\Service\SendOtp;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\JWTException;

class StrudentApiController extends Controller
{
    protected $userRepository;
    protected $studioRepository;
    protected $studioUserRepository;
    protected $attandanceRepository;

    public function __construct(UserRepositoryInterface $userRepository, StudioRepository $studioRepository, StudioUserRepositoryInterface $studioUserRepository, AttendanceRepositoryInterface $attandanceRepository)
    {
        $this->userRepository = $userRepository;
        $this->studioRepository = $studioRepository;
        $this->studioUserRepository = $studioUserRepository;
        $this->attandanceRepository = $attandanceRepository;
    }

    public function index(StudentRequest $studentRequest)
    {
        $data = $this->userRepository->getAllStudentWithPaginator($studentRequest->all());
        return $this->sendResponse(
            new StudentCollection($data),
            'get success',
            200
        );
    }

    public function store(StudentRequest $studentRequest)
    {
        try {
            $password = rand(pow(10, 5), pow(10, 6)-1);
            $input = array_merge(
                $studentRequest->validated(),
                ['password' => Hash::make($password), 'is_student' => 1]
            );

            $sendOtp = new SendOtp();
            $status = $sendOtp->send($input['phone'], 'password: '.$password);
            if (!$status) {
                return $this->sendError([], 'Fail to send otp');
            }

            $student = $this->userRepository->create($input);
            if($studentRequest->hasFile('avatar')){
                $student->addMedia($input['avatar'])
                    ->toMediaCollection('avatar');
            }

            return $this->sendResponse(
                UserResource::make($student),
                'Student successfully create'
            );
        }
        catch (JWTException $th) {
            return $this->sendError(['error' => 'Unauthorized'], 'Fail to register', 401);
        }
    }

    public function update(StudentRequest $request, $id)
    {
        $student = $this->userRepository->find($id);

        if (empty($student)) {
            return $this->sendError(['error' => 'Not found!'], 'cannot found this student', 404);
        }

        $input  = $request->validated();

        try {
            $student = $this->userRepository->update($id, $input);
            if($request->hasFile('avatar')){
                $student->addMedia($input['avatar'])->toMediaCollection('avatar');
            }

            return $this->sendResponse(
                UserResource::make($student),
                'Student successfully update'
            );

        } catch (\Exception $e) {
            return $this->sendError(['error' => 'system error'], $e->getMessage(), 401);
        }
    }

    public function destroy($id)
    {
        try {
            $student = $this->userRepository->find($id);

            if (empty($student)) {
                return $this->sendError(['error' => 'Not found!'], 'cannot found this student', 404);
            }

            $this->userRepository->destroy($student->id);

            return $this->sendResponse(null, 'Student successfully removed',200);
        }
        catch (JWTException $th) {
            return $this->sendError(['error' => 'Student remove fail'], 'Fail to remove', 401);
        }
    }

    public function show($id)
    {
        try {
            $student = $this->userRepository->find($id);

            if (empty($student)) {
                return $this->sendError(['error' => 'Not found!'], 'cannot found this student', 404);
            }

            return $this->sendResponse(
                UserResource::make($student),
                'Show student'
            );
        }
        catch (JWTException $th) {
            return $this->sendError(['error' => 'cannot found this student'], 'Fail to show', 401);
        }
    }

    public function class()
    {
        $user = Auth::guard('api')->user();
        $studioIds = [];
        $items = $this->studioRepository->getStudioIdForStudent($user->id);
        foreach ($items as $item) {
            array_push($studioIds, $item->studio_id);
        }

        $studios = $this->studioRepository->getStudioFromArr($studioIds);

        return $this->sendResponse(
            StudioResource::collection($studios),
            'get success'
        );
    }

    public function allClass(Request $request)
    {
        $user = Auth::guard('api')->user();
        $studioIds = [];
        $items = $this->studioRepository->getStudioIdForStudent($user->id);
        foreach ($items as $item) {
            array_push($studioIds, $item->studio_id);
        }

        $requestStudio = $request->studio ?? [];
        $studios = $this->studioRepository->getStudioNotInArr($studioIds, $requestStudio);

        return $this->sendResponse(
            StudioResource::collection($studios),
            'get success'
        );
    }

    public function registerClass(StudentRequest $studentRequest)
    {
        try {
            $user = Auth::guard('api')->user();
            $input = $studentRequest->validated();
            $this->studioUserRepository->create(['studio_id' => $input['studio_id'], 'user_id' => $user->id]);

            return $this->sendResponse(
                '',
                200
            );

        } catch (\Exception $e) {
            return $this->sendError(['error' => 'system error'], $e->getMessage(), 401);
        }
    }

    public function attend(StudentRequest $studentRequest)
    {
        try {
            $user = Auth::guard('api')->user();
            $input = $studentRequest->validated();
            $this->attandanceRepository->create(['studio_id' => $input['studio_id'], 'user_id' => $user->id, 'date' => date('Y-m-d')]);

            return $this->sendResponse(
                '',
                200
            );

        } catch (\Exception $e) {
            return $this->sendError(['error' => 'system error'], $e->getMessage(), 401);
        }
    }

    public function showClassAttend($idStudio)
    {
        $user = Auth::guard('api')->user();
        $studio = $this->studioRepository->find($idStudio);

        if (empty($studio)) {
            return $this->sendError(['error' => 'Not found!'], 'cannot found this studio', 404);
        }

        $item = $this->studioUserRepository->getStudentClass($studio->id, $user->id);
        if (empty($item)) {
            return $this->sendError(['error' => 'This student has not been registered'], 'Fail', 400);
        }

        $dayAttend = [];
        $attendances = $this->attandanceRepository->getAttendance($studio->id, $user->id);
        foreach ($attendances as $attend) {
            array_push($dayAttend, $attend->date);
        }
        $process = [];
        $attendDate = json_decode($item->attend_date);
        $canAttend = false;
        if (!empty($attendDate)) {
            foreach ($attendDate as $date) {
                $dateFormat = Carbon::createFromFormat('Y-m-d', $date)->startOfDay();
                $today = Carbon::today();
                if ($dateFormat->lt($today)) {
                    $process[] = [
                        'date' => $date,
                        'hasAttend' => in_array($date, $dayAttend) ? 'attended' : 'no_attend'
                    ];
                }
                if ($dateFormat->eq($today)) {
                    $process[] = [
                        'date' => $date,
                        'hasAttend' => 'prepare_attend'
                    ];
                    $canAttend = true;
                }
            }
        }


        $data = [
            'studioImage' => $studio->getFirstMediaUrl('image'),
            'studentName' => $user->name,
            'studentAvatar' => $user->avatar,
            'numberOfSessions' => $item->number_of_sessions,
            'dayOfWeek' => getDayOfWeek($studio->schedule),
            'startDate' => $item->start_date,
            'endDate' => $item->end_date,
            'startTime' => $studio->start_time,
            'endTime' => $studio->end_time,
            'address' => $studio->address,
            'description' => $studio->description,
            'process' => $process,
            'canAttend' => $canAttend,
        ];

        return $this->sendResponse(
            $data,
            'show class register'
        );
    }

    public function showClassRegister($idStudio)
    {
        $user = Auth::guard('api')->user();
        $studio = $this->studioRepository->find($idStudio);

        if (empty($studio)) {
            return $this->sendError(['error' => 'Not found!'], 'cannot found this studio', 404);
        }

        $item = $this->studioUserRepository->getStudentClass($studio->id, $user->id);
        if (empty($item)) {
            return $this->sendError(['error' => 'This student has not been registered'], 'Fail', 400);
        }

        $data = [
            'studioImage' => $studio->getFirstMediaUrl('image'),
            'studentName' => $user->name,
            'studentAvatar' => $user->avatar,
            'numberOfSessions' => $studio->number_of_sessions,
            'dayOfWeek' => getDayOfWeek($studio->schedule),
            'startTime' => $studio->start_time,
            'endTime' => $studio->end_time,
            'address' => $studio->address,
            'description' => $studio->description
        ];

        return $this->sendResponse(
            $data,
            'show class register'
        );
    }
}
