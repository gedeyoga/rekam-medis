<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateMedicalRecordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $validation = [
            'new_patient' => 'required',
            'tanggal' => 'required',
            'medical_files' => 'required|array',
        ];

        if(boolval($this->get('new_patient'))) {
            $validation_patient = [
                'profile.name' => 'required|string',
                'profile.nik' => 'string|min:16|nullable',
                'profile.address' => 'required|string',
                'profile.phone' => 'string',
                'profile.place_of_birth' => 'required',
                'profile.date_of_birth' => 'required',
                'profile.gender' => 'required',
            ];

            $validation = array_merge($validation,  $validation_patient);
        }

        return $validation;
    }
}
