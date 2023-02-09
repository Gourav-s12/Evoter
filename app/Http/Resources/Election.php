<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\User as UserResource;

class Election extends JsonResource
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
            'data' => [
                'type' => 'election',
                'ele_id' => $this->id,
                'attributes' => [
                    'name' => $this->name,
                    'start' => optional($this->start)->diffForHumans(),
                    'end' =>  optional($this->end)->diffForHumans(),
                    'voted' => $this->user_id ? true : false,
                ]
            ],
            'links' => [
                'self' => url('/Election/'.$this->id),
            ]
        ];
    }
}
