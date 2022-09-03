<?php


namespace App\Http\Controllers;


use App\Http\Requests\UploadRequest;
use App\Repositories\Upload\UploadRepositoryInterface;
use Prettus\Validator\Exceptions\ValidatorException;

class UploadController extends Controller
{
    private $uploadRepository;

    public function __construct(UploadRepositoryInterface $uploadRepository)
    {
        $this->uploadRepository = $uploadRepository;
    }

    public function store(UploadRequest $request)
    {
        $input = $request->all();
        try {
            $upload = $this->uploadRepository->create($input);
            $upload->addMedia($input['file'])
                ->withCustomProperties(['uuid' => $input['uuid'], 'user_id' => auth()->id()])
                ->toMediaCollection($input['field']);
        } catch (ValidatorException $e) {
            return $this->sendResponse(false, $e->getMessage());
        }
    }
}
