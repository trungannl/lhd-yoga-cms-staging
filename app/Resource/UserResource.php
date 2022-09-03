<?php


namespace App\Resource;


use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "phone" => $this->phone,
            "email" => $this->email,
            "gender" => $this->gender,
            "birthday" => $this->birthday,
            "avatar" => $this->avatar
        ];
    }
}
