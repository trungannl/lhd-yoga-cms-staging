<?php


namespace App\Repositories\Upload;

use App\Models\Upload;
use App\Repositories\BaseRepository;

class UploadRepository extends BaseRepository implements UploadRepositoryInterface
{
    public function getModel()
    {
        return Upload::class;
    }

    public function getByUuid($uuid = '')
    {
        return $this->model->where('uuid', $uuid)->first();
    }

    /**
     * @param $uuid
     */
    public function clear($uuid)
    {
        $uploadModel = $this->getByUuid($uuid);
        return $uploadModel->delete();
    }
}
