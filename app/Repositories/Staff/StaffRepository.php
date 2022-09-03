<?php


namespace App\Repositories\Staff;


use App\Models\Staff;
use App\Repositories\BaseRepository;

class StaffRepository extends BaseRepository implements StaffRepositoryInterface
{
    public function getModel()
    {
        return Staff::class;
    }
}
