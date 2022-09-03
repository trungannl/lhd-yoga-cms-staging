<?php


namespace App\Repositories\CoacherClassData;


use App\Repositories\RepositoryInterface;

interface CoacherClassDataInterface extends RepositoryInterface
{
    public function getData($studioId, $userId);
}
