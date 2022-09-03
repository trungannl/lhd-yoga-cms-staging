<?php


namespace App\Http\Controllers\V1;


use App\Http\Controllers\Controller;
use App\Repositories\Upload\UploadRepositoryInterface;
use Illuminate\Support\Facades\File;
use Illuminate\Http\File as httpFile;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Prettus\Validator\Exceptions\ValidatorException;

class UploadApiController extends Controller
{
    private $uploadRepository;

    public function __construct(UploadRepositoryInterface $uploadRepository)
    {
        $this->uploadRepository = $uploadRepository;
    }

    public function store(Request $request)
    {
        try {
            $uuid = (string) Str::uuid();
            $input = $request->all();
            $imgdata = base64_decode($input['file']);
            $mimetype = $this->getImageMimeType($imgdata);
            $filename = $uuid . '.' . $mimetype;
            file_put_contents(public_path($filename), $imgdata);
            $file = new httpFile(public_path($filename));

            $upload = $this->uploadRepository->create([
                'uuid' => $uuid
            ]);
            $upload->addMedia($file)
                ->withCustomProperties(['uuid' => $uuid])
                ->toMediaCollection('image');

            if (File::exists($filename)) {
                File::delete($filename);
            }

            return $this->sendResponse([
                'uuid' => $upload->uuid,
                'image' => $upload->getFirstMediaUrl('image'),
            ], 'Upload successfully');

        } catch (ValidatorException $e) {
            return $this->sendError(['error' => 'Fail to upload'], 'Fail to upload');
        }
    }

    function getBytesFromHexString($hexdata)
    {
        for($count = 0; $count < strlen($hexdata); $count+=2)
            $bytes[] = chr(hexdec(substr($hexdata, $count, 2)));

        return implode($bytes);
    }

    function getImageMimeType($imagedata)
    {
        $imagemimetypes = array(
            "jpeg" => "FFD8",
            "png" => "89504E470D0A1A0A",
            "gif" => "474946",
            "bmp" => "424D",
            "tiff" => "4949",
            "tiff" => "4D4D"
        );

        foreach ($imagemimetypes as $mime => $hexbytes)
        {
            $bytes = $this->getBytesFromHexString($hexbytes);
            if (substr($imagedata, 0, strlen($bytes)) == $bytes)
                return $mime;
        }

        return NULL;
    }

    public function health()
    {
        return $this->sendResponse([], 'health check');
    }
}
