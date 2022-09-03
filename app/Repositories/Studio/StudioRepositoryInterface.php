<?php


namespace App\Repositories\Studio;


use App\Repositories\RepositoryInterface;

interface StudioRepositoryInterface extends RepositoryInterface
{
    public function getAllWithPagination(array $request): object;
    public function getStudentsWithPagination(array $request, $id): object;
    public function getStudioIdForStudent($userId);
    public function getStudioFromArr($ids);
    public function getStudioNotInArr($ids, $requestStudio);
    public function getListStudioFromCoacher($coacherId);
}
