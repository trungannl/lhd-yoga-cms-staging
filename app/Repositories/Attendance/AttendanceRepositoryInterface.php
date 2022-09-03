<?php


namespace App\Repositories\Attendance;


use App\Repositories\RepositoryInterface;

interface AttendanceRepositoryInterface extends RepositoryInterface
{
    public function getAttendance($studioId, $studentId);
}
