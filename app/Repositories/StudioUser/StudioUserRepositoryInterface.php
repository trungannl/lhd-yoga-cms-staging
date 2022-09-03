<?php


namespace App\Repositories\StudioUser;


use App\Repositories\RepositoryInterface;

interface StudioUserRepositoryInterface extends RepositoryInterface
{
    public function getStudentClass($studioId, $studentId);
    public function getAllWithPagination(array $request, $studioId);
    public function updateItem($inputs);
}
