<?php


namespace App\Repositories\Attendance;

use App\Models\Attendance;
use App\Repositories\BaseRepository;

class AttendanceRepository extends BaseRepository implements AttendanceRepositoryInterface
{
    public function getModel()
    {
        return Attendance::class;
    }

    public function getAttendance($studioId, $studentId)
    {
        return $this->model->where('studio_id', $studioId)->where('user_id', $studentId)->get();
    }
}
