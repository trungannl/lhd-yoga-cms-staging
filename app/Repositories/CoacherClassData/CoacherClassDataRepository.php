<?php


namespace App\Repositories\CoacherClassData;

use App\Models\CoacherClassData;
use App\Repositories\BaseRepository;

class CoacherClassDataRepository extends BaseRepository implements CoacherClassDataInterface
{
    public function getModel()
    {
        return CoacherClassData::class;
    }

    public function getData($studioId, $userId)
    {
        return $this->model->where('studio_id', $studioId)->where('user_id', $userId)->get();
    }
}
