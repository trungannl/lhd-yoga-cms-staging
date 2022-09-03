<?php


namespace App\Repositories\CoacherCheckin;

use App\Models\CoacherCheckin;
use App\Repositories\BaseRepository;

class CoacherCheckinRepository extends BaseRepository implements CoacherCheckinRepositoryInterface
{
    public function getModel()
    {
        return CoacherCheckin::class;
    }

    public function countCheckinByMonth($coacherId, $month, $year)
    {
        return $this->model
            ->where('coacher_id', $coacherId)
            ->whereYear('date', '=', $year)
            ->whereMonth('date', '=', $month)
            ->count();
    }
}
