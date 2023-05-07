<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MedicalRecordTransformer extends JsonResource
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
            'patient_id' => $this->patient_id,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by, 
            'patient' => new PatientTransformer($this->whenLoaded('patient')),
            'staff_created' => new UserTransformer($this->whenLoaded('staff_created')),
            'staff_updated' => new UserTransformer($this->whenLoaded('staff_updated')),
            'medical_record_files' => $this->getMedia('medical_records')
        ];
    }
}
