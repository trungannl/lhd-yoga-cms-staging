<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StudioUserRequest;
use App\Http\Resources\ItemCollection;
use App\Http\Resources\StudioStudentResource;
use App\Repositories\Studio\StudioRepositoryInterface;
use App\Repositories\StudioUser\StudioUserRepositoryInterface;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\JWTException;

class StudioStudentApiController extends Controller
{
    protected $studioRepository;
    protected $studioUserRepository;

    public function __construct(StudioUserRepositoryInterface $studioUserRepository, StudioRepositoryInterface $studioRepository)
    {
        $this->studioUserRepository = $studioUserRepository;
        $this->studioRepository = $studioRepository;
    }

    public function index(StudioUserRequest $request)
    {
        $studio = Auth::guard('api')->user();
        $data = $this->studioUserRepository->getAllWithPagination($request->validated(), $studio->id);
        return $this->sendResponse(
            new ItemCollection($data),
            'get list register success',
            200
        );
    }

    public function show($classId, $studentId)
    {
        $item = $this->studioUserRepository->getStudentClass($classId, $studentId);
        return $this->sendResponse(
            StudioStudentResource::make($item),
            'get student register success',
            200
        );
    }

    public function store(StudioUserRequest $request)
    {
        try {
            $inputs = $request->validated();
            $inputs['user_id'] = $inputs['student_id'];

            $item = $this->studioUserRepository->getStudentClass($inputs['studio_id'], $inputs['user_id']);
            if ($item) {
                return $this->sendError(['error' => 'Student has been register'], 'Student has been register', 401);
            }

            $studio = $this->studioRepository->find($inputs['studio_id']);

            $startDateStudio = $studio->start_date;
            $endDateStudio = $studio->end_date;
            $startDateRegister = Carbon::createFromFormat('Y-m-d', $inputs['start_date']);
            $endDateRegister = Carbon::createFromFormat('Y-m-d',  $inputs['end_date']);

            if ($startDateRegister < $startDateStudio) {
                return $this->sendError(['error' => 'The start date must be a date after start date of studio'], 'Fail', 401);
            }

            if ($endDateStudio < $endDateRegister) {
                return $this->sendError(['error' => 'The end date must be a date before end date of studio'], 'Fail', 401);
            }

            $inputs['attend_date'] = $this->getAttendDate($studio->schedule, $inputs['start_date'], $inputs['end_date']);


            $item = $this->studioUserRepository->create($inputs);
            $item = $this->studioUserRepository->getStudentClass($item->studio_id, $item->user_id);

            return $this->sendResponse(
                StudioStudentResource::make($item),
                'add student register success',
                200
            );

        }
        catch (JWTException $th) {
            return $this->sendError(['error' => 'Unauthorized'], 'Fail to register', 401);
        }

    }

    public function update(StudioUserRequest $request)
    {
        try {
            $inputs = $request->validated();
            $inputs['user_id'] = $inputs['student_id'];

            $item = $this->studioUserRepository->getStudentClass($inputs['studio_id'], $inputs['user_id']);
            if (!$item) {
                return $this->sendError(['error' => 'Student has not been register'], 'Student has not been register', 401);
            }

            $studio = $this->studioRepository->find($inputs['studio_id']);
            $startDateStudio = $studio->start_date;
            $endDateStudio = $studio->end_date;
            $startDateRegister = Carbon::createFromFormat('Y-m-d', $inputs['start_date']);
            $endDateRegister = Carbon::createFromFormat('Y-m-d',  $inputs['end_date']);

            if ($startDateRegister < $startDateStudio) {
                return $this->sendError(['error' => 'The start date must be a date after start date of studio'], 'Fail', 401);
            }

            if ($endDateStudio < $endDateRegister) {
                return $this->sendError(['error' => 'The end date must be a date before end date of studio'], 'Fail', 401);
            }

            $inputs['attend_date'] = $this->getAttendDate($studio->schedule, $inputs['start_date'], $inputs['end_date']);

            unset($inputs['student_id']);
            if ($this->studioUserRepository->updateItem($inputs)) {
                $item = $this->studioUserRepository->getStudentClass($inputs['studio_id'], $inputs['user_id']);
                return $this->sendResponse(
                    StudioStudentResource::make($item),
                    'update student register success',
                    200
                );
            }

            return $this->sendError(['error' => 'Fail to update student register'], 'Fail to update student register', 401);
        }
        catch (JWTException $th) {
            return $this->sendError(['error' => 'Unauthorized'], 'Fail to register', 401);
        }

    }

    function getAttendDate($schedule, $startDate, $endDate)
    {
        $keyDayofWeek = [
            'mon' => 1,
            'tue' => 2,
            'wed' => 3,
            'thu' => 4,
            'fri' => 5,
            'sat' => 6,
            'sun' => 7,
        ];
        $idDayofWeek = [];
        foreach ($schedule as $key=>$item) {
            if ($item == 1) {
                array_push($idDayofWeek, $keyDayofWeek[$key]);
            }
        }
        $period = CarbonPeriod::create($startDate, $endDate);
        $dates = [];
        foreach ($period as $date) {
            if (in_array($date->dayOfWeek, $idDayofWeek)) {
                array_push($dates, $date->format('Y-m-d'));
            }
        }

        return json_encode($dates);
    }
}
