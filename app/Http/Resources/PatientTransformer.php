<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PatientTransformer extends JsonResource
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
            'id' => $this->id,
            'code' => $this->code, 
            'nik' => $this->nik, 
            'user_id' => $this->user_id, 
            'pengobatan_type' => $this->pengobatan_type, 
            'no_bpjs' => $this->no_bpjs, 
            'created_by' => $this->created_by,
            'staff' => new UserTransformer($this->whenLoaded('staff')),
            'profile' => new UserTransformer($this->whenLoaded('profile')),
        ];
    }
}
