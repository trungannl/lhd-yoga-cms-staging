<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CoacherResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'     => $this->id,
            'name'   => $this->name,
            'phone'  => $this->phone,
            'email'  => $this->email,
            'gender' => $this->gender,
            'active' => $this->active,
            'salary' => $this->salary,
            'avatar' => $this->avatar,
        ];
    }
}
