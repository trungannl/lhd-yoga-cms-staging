<?php


namespace App\Repositories\Area;


use App\Repositories\RepositoryInterface;

interface AreaRepositoryInterface extends RepositoryInterface
{
    public function getCity();

    public function getDistrict();

    public function getWard();

    public function getDistrictByCity($city);

    public function getWardByDistrict($district);
}
