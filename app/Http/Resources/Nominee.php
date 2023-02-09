<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Nominee extends JsonResource
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
                'type' => 'nominee',
                'nom_id' => $this->id,
                'attributes' => [
                    'name' => $this->name,
                    'description' => $this->description,
                    'path' => optional($this->image)->url() ?? 'http://127.0.0.1:8000/default_profile_image.png',
                ],
                "voters_count" => $this->voters_count,
            ],
            'links' => [
                'self' => url('/Nominee/'.$this->id),
            ]
        ];
    }
}
