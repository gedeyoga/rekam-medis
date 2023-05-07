<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePatientRequest extends FormRequest
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
        return [
            'id' => 'required',
            'profile.name' => 'required|string',
            'nik' => 'string|min:16|nullable',
            'profile.address' => 'required|string',
            'profile.phone' => 'string',
            'profile.place_of_birth' => 'required',
            'profile.date_of_birth' => 'required',
            'profile.gender' => 'required',
        ];
    }
}
