<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StudentRequest;
use App\Http\Requests\StudioRequest;
use App\Http\Resources\StudioResource;
use App\Http\Resources\UserCollection;
use App\Http\Resources\StudioCollection;
use App\Http\Resources\UserResource;
use App\Repositories\Studio\StudioRepository;
use App\Repositories\StudioUser\StudioUserRepositoryInterface;
use App\Repositories\Upload\UploadRepository;
use App\Repositories\User\UserRepositoryInterface;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class StudioApiController extends Controller
{
    protected $studioRepository;
    protected $uploadRepository;
    protected $studioUserRepository;
    protected $userRepository;

    public function __construct(StudioRepository $studioRepository, UploadRepository $uploadRepository, StudioUserRepositoryInterface $studioUserRepository, UserRepositoryInterface $userRepository)
    {
        $this->studioRepository = $studioRepository;
        $this->uploadRepository = $uploadRepository;
        $this->studioUserRepository = $studioUserRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(StudioRequest $request)
    {
        $user = Auth::guard('api')->user();

        try {
            $input = $request->validated();
            $input['owner_id'] = $user->id;
            $studio = $this->studioRepository->create($input);
            if($request->hasFile('image')){
                $studio->addMedia($input['image'])
                    ->toMediaCollection('image');
            }
        }
        catch (JWTException $th) {
            return $this->sendError(['error' => 'Unauthorized'], 'Fail to register', 401);
        }

        return $this->sendResponse(
            new StudioResource($studio),
            'Studio successfully create',
            200
        );
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(StudioRequest $request)
    {
        $studio = $this->studioRepository->getAllWithPagination($request->validated());
        return $this->sendResponse(
            new StudioCollection($studio),
            'get success',
            200
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $studio = $this->studioRepository->find($id);

        if (empty($studio)) {
            return $this->sendError(['error' => 'Not found!'], 'cannot found this studio', 404);
        }

        return $this->sendResponse(
            new StudioResource($studio),
            200
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StudioRequest $request, $id)
    {
        $studio = $this->studioRepository->find($id);
        $input  = $request->validated();

        if (empty($studio)) {
            return $this->sendError(['error' => 'Not found!'], 'cannot found this studio', 404);
        }

        try {
            $studio->update($input);
            if($request->hasFile('image')){
                $studio->addMedia($input['image'])
                    ->toMediaCollection('image');
            }
        } catch (\Exception $e) {
            return $this->sendError(['error' => 'system error'], $e->getMessage(), 401);
        }

        return $this->sendResponse(
            new StudioResource($studio),
            200
        );
    }

    /**
     * add student to class
     *
     * @param StudioRequest $request
     * @param [type] $id
     * @return void
     */
    public function addStudent(StudioRequest $request, $id)
    {
        $studio = $this->studioRepository->find($id);
        $input  = $request->validated();

        if (empty($studio)) {
            return $this->sendError(['error' => 'Class not found!'], 'cannot found this studio', 404);
        }

        $student = $this->userRepository->getStudentForPhone($input['phone_student']);

        if (empty($student)) {
            return $this->sendError(['error' => 'Student not found!'], 'cannot found this student', 400);
        }

        $item = $this->studioUserRepository->getStudentClass($id, $student->id);
        if ($item) {
            return $this->sendError(['error' => 'This student has been registered'], 'This student has been registered', 400);
        }

        try {
            $this->studioUserRepository->create(['studio_id' => $id, 'user_id' => $student->id]);
        } catch (\Exception $e) {
            return $this->sendError(['error' => 'system error'], $e->getMessage(), 401);
        }

        return $this->sendResponse(
            '',
            200
        );
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getStudents(StudioRequest $request, $id)
    {
        try {
            $student = $this->studioRepository->getStudentsWithPagination($request->validated(), $id);
        } catch (\Exception $e) {
            return $this->sendError(['error' => 'system error'], $e->getMessage(), 401);
        }

        return $this->sendResponse(
            new UserCollection($student),
            'get success',
            200
        );
    }

    public function registerStudent(StudentRequest $studentRequest, $id)
    {
        try {
            $input = array_merge(
                $studentRequest->validated(),
                ['password' => Hash::make('123456'), 'is_student' => 1]
            );
            $student = $this->userRepository->create($input);
            if($studentRequest->hasFile('avatar')){
                $student->addMedia($input['avatar'])
                    ->toMediaCollection('avatar');
            }

            $this->studioUserRepository->create(['studio_id' => $id, 'user_id' => $student->id]);

            return $this->sendResponse(
                UserResource::make($student),
                'Student successfully register'
            );
        }
        catch (JWTException $th) {
            return $this->sendError(['error' => 'Unauthorized'], 'Fail to register student', 401);
        }
    }

}
