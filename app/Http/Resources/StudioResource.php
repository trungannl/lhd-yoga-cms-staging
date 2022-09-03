<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StudioResource extends JsonResource
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
            'latitude'     => $this->latitude,
            'longitude'     => $this->longitude,
            'created_at'  => $this->created_at->format('Y-m-d H:i:s'),
            'status'      => $this->getStatus(),
            'status_id'   => $this->status,
            'start_date'  => $this->start_date ? $this->start_date->format('Y-m-d') : '',
            'end_date'    => $this->end_date ? $this->end_date->format('Y-m-d') : '',
            'start_time'  => $this->start_time ? substr($this->start_time, 0, -3) : '',
            'end_time'    => $this->end_time ? substr($this->end_time, 0, -3) : '',
            'price'       => $this->price,
            'schedule'    => $this->schedule ?: $this->resource::DEFAULT_SCHEDULE,
            'coacher'       => new UserResource($this->coacher),
            'owner'       => new UserResource($this->owner),
            'image'       => $this->getFirstMediaUrl('image'),
            'countStudent'       => $this->countStudent(),
        ];
    }
}
