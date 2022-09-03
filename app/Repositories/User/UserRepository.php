<?php


namespace App\Repositories\User;


use App\Models\User;
use App\Repositories\BaseRepository;
use Carbon\Carbon;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function getModel()
    {
        return User::class;
    }

    public function getUserByNameColumn($name, $value)
    {
        return $this->model->where($name, $value)->first();
    }

    public function checkVerifyCode($phone, $code)
    {
        return $this->model->where('phone', $phone)
            ->where('password_reset_otp', $code)
            ->whereDate('otp_time_expire', '>=', Carbon::now()->toDateString())
            ->first();
    }

    public function getAllStudentWithPaginator(array $request): object
    {
        $perPage = $request['per_page'] ?? 10;
        $page = $request['page'] ?? 1;

        $data = $this->model
            ->query()
            ->where('is_student', 1);

        if (isset($request['name']) && $request['name']) {
            $data->where('name', 'like', '%' . $request['name'] . '%');
        }
        return $data
            ->orderBy('updated_at', 'DESC')
            ->paginate(
                $perPage,
                ['*'],
                'page',
                $page
            );
    }

    public function getAllCoacherWithPaginator(array $request): object
    {
        $perPage = $request['per_page'] ?? 10;
        $page = $request['page'] ?? 1;

        $data = $this->model
            ->query()
            ->where('is_coacher', 1);

        if (isset($request['name']) && $request['name']) {
            $data->where('name', 'like', '%' . $request['name'] . '%');
        }
        return $data
            ->orderBy('updated_at', 'DESC')
            ->paginate(
                $perPage,
                ['*'],
                'page',
                $page
            );
    }

    public function getStudentForPhone($phone)
    {
        return $this->model->where('phone', $phone)->where('is_student', 1)->first();
    }

    public function getCoacherForPhone($phone)
    {
        return $this->model->where('phone', $phone)->where('is_coacher', 1)->first();
    }

    public function searchStudentFromPhone($number, $limit = 10)
    {
        return $this->model->where('phone', 'like', $number . '%')->where('is_student', 1)->limit($limit)->get();
    }

    public function searchCoacherFromPhone($number, $limit = 10)
    {
        return $this->model->where('phone', 'like', $number . '%')->where('is_coacher', 1)->limit($limit)->get();
    }
}
