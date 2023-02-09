<?php

namespace App\Http\Resources;

use App\Models\Election;
use Illuminate\Http\Resources\Json\JsonResource;

class Result extends JsonResource
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
                'type' => 'election_result',
                'ele_id' => $this->id,
                'attributes' => [
                    'name' => $this->name,
                    'start' => optional($this->start)->diffForHumans(),
                    'end' =>  optional($this->end)->diffForHumans(),
                    "total_voters_count" => $this->total_voters_count,
                    'nominees' => new NomineeCollection(Election::findOrFail($this->id)->nominees()->withCount(['voters'])->get()),
                ]
            ],
            'links' => [
                'self' => url('/result/'.$this->id),
            ]
        ];
    }
}
