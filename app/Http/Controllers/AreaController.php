<?php

namespace App\Http\Controllers;

use App\Repositories\Area\AreaRepositoryInterface;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    protected $areaRepository;

    public function __construct(AreaRepositoryInterface $areaRepository)
    {
        $this->areaRepository = $areaRepository;
    }

    public function district(Request $request)
    {
        $city = $request->city;
        $district = $this->areaRepository->getDistrictByCity($city);
        $data = [];
        foreach ($district as $item) {
            $data[$item->id] = $item->name;
        }

        return response()->json($data, 200);
    }

    public function ward(Request $request)
    {
        $district = $request->city;
        $ward = $this->areaRepository->getWardByDistrict($district);
        $data = [];
        foreach ($ward as $item) {
            $data[$item->id] = $item->name;
        }

        return response()->json($data, 200);
    }
}
