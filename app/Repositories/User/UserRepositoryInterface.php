<?php


namespace App\Repositories\User;


use App\Repositories\RepositoryInterface;

interface UserRepositoryInterface extends RepositoryInterface
{
    public function getUserByNameColumn($name, $value);

    public function checkVerifyCode($phone, $code);

    public function getAllStudentWithPaginator(array $request);
    public function getAllCoacherWithPaginator(array $request);
    public function getStudentForPhone($phone);
    public function getCoacherForPhone($phone);
    public function searchStudentFromPhone($number, $limit = 10);
    public function searchCoacherFromPhone($number, $limit = 10);
}
