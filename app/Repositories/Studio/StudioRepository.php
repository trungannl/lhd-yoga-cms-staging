<?php


namespace App\Repositories\Studio;


use App\Models\Studio;
use App\Models\StudioUser;
use App\Repositories\BaseRepository;

class StudioRepository extends BaseRepository implements StudioRepositoryInterface
{
    public function getModel()
    {
        return Studio::class;
    }

    public function getAllWithPagination(array $request): object
    {
        return $this->model
            ->query()
            ->name($request)
            ->paginate(
                $request['per_page'],
                ['*'],
                'page',
                $request['page']
            );
    }

    public function getStudentsWithPagination(array $request, $id): object
    {
        $studio = $this->model->findOrFail($id);
        return $studio
            ->students()
            ->paginate(
                $request['per_page'],
                ['*'],
                'page',
                $request['page']
            );
    }

    public function getStudioIdForStudent($userId)
    {
        return StudioUser::select('*')->where('user_id', $userId)->get();
    }

    public function getStudioFromArr($ids)
    {
        return $this->model->whereIn('id', $ids)
            ->where('status', 1)
            ->where('end_date', '>=', now())
            ->get();
    }

    public function getStudioNotInArr($ids, $requestStudio)
    {
        $data = $this->model->whereNotIn('id', $ids);

        if (!empty($requestStudio)) {
            $data->whereIn('owner_id', $requestStudio);
        }

        return $data->where('end_date', '>=', now())->get();
    }

    public function getListStudioFromCoacher($coacherId)
    {
        return $this->model
            ->where('coach_id', $coacherId)
            ->where('status', 1)
            ->where('end_date', '>=', now())
            ->get();
    }
}
