<?php


namespace App\Repositories\Area;


use App\Models\Area;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Cache;

class AreaRepository extends BaseRepository implements AreaRepositoryInterface
{
    const TYPE_CITY = 1;
    const TYPE_DISTRICT = 2;
    const TYPE_WARD = 3;

    public function getModel()
    {
        return Area::class;
    }

    /**
     * @return array
     */
    public function getCity()
    {
        $value = Cache::remember('city', 3600, function () {
            return $this->model->where('type', self::TYPE_CITY)->get()->toArray();
        });

        return $value;
    }

    /**
     * @return array
     */
    public function getDistrict()
    {
        $value = Cache::remember('district', 3600, function () {
            return $this->model->where('type', self::TYPE_DISTRICT)->get()->toArray();
        });

        return $value;
    }

    /**
     * @return array
     */
    public function getWard()
    {
        $value = Cache::remember('ward', 3600, function () {
            return $this->model->where('type', self::TYPE_WARD)->get()->toArray();
        });

        return $value;
    }

    public function getDistrictByCity($city)
    {
        return $this->model->where('parent_id', $city)->get();
    }

    public function getWardByDistrict($district)
    {
        return $this->model->where('parent_id', $district)->get();
    }
}
