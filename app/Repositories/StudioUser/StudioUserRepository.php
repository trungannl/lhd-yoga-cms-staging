<?php


namespace App\Repositories\StudioUser;

use App\Models\StudioUser;
use App\Repositories\BaseRepository;

class StudioUserRepository extends BaseRepository implements StudioUserRepositoryInterface
{
    public function getModel()
    {
        return StudioUser::class;
    }

    public function getStudentClass($studioId, $studentId)
    {
        return $this->model->where('studio_id', $studioId)->where('user_id', $studentId)->first();
    }

    public function getAllWithPagination(array $request, $studioId): object
    {
        $perPage = $request['per_page'] ?? 10;
        $page = $request['page'] ?? 1;

        $data = $this->model
            ->query()
            ->select('users.name as studentName', 'users.phone', 'studio_user.approve', 'studios.name as className', 'studio_user.is_paid as isPaid')
            ->join('users', 'studio_user.user_id', '=', 'users.id')
            ->join('studios', 'studio_user.studio_id', '=', 'studios.id')
            ->where('studios.owner_id', $studioId);

        if (isset($request['name']) && $request['name']) {
            $data->where('users.name', 'like', '%' . $request['name'] . '%');
        }

        return $data
            ->orderBy('studio_user.updated_at', 'DESC')
            ->paginate(
                $perPage,
                ['*'],
                'page',
                $page
            );
    }

    public function updateItem($inputs)
    {
        return $this->model->query()
            ->where('studio_id', $inputs['studio_id'])->where('user_id', $inputs['user_id'])
            ->update($inputs);
    }
}
