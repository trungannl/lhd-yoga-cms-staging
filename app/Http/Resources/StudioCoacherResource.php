<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StudioCoacherResource extends JsonResource
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
            'id'          => $this->id,
            'name'        => $this->name,
            'description' => $this->description,
            'address'     => $this->address,
            'start_time'  => $this->start_time ? substr($this->start_time, 0, -3) : '',
            'end_time'    => $this->end_time ? substr($this->end_time, 0, -3) : '',
            'numberOfSessions' => $this->number_of_sessions,
            'dayOfWeek' => getDayOfWeek($this->schedule),
            'image'       => $this->getFirstMediaUrl('image'),
            'countStudent'       => $this->countStudent(),
        ];
    }
}
