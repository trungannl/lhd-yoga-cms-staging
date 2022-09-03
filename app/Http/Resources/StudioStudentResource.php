<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StudioStudentResource extends JsonResource
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
            'approve' => $this->approve,
            'isPaid' => $this->is_paid,
            'studentName' => $this->student->name,
            'phone' => $this->student->phone,
            'className' => $this->studio->name,
            'numberOfSessions' => $this->number_of_sessions ?? 0,
            'startDate'  => $this->start_date ?? '',
            'endDate'    => $this->end_date ?? '',
            'price' => $this->price ?? 0,
        ];
    }
}
