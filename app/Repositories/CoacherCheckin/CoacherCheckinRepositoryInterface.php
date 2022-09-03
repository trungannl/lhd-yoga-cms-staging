<?php


namespace App\Repositories\CoacherCheckin;


use App\Repositories\RepositoryInterface;

interface CoacherCheckinRepositoryInterface extends RepositoryInterface
{
    public function countCheckinByMonth($coacherId, $month, $year);
}
