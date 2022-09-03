<?php


namespace App\Repositories\Upload;


use App\Repositories\RepositoryInterface;

interface UploadRepositoryInterface extends RepositoryInterface
{
    public function getByUuid($uuid = '');

    public function clear($uuid);
}
