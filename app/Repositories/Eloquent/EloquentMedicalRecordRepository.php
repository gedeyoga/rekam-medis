<?php

namespace App\Repositories\Eloquent;

use App\Events\UserWasCreated;
use App\Models\User;
use App\Repositories\MedicalRecordRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;

class EloquentMedicalRecordRepository extends EloquentBaseRepository implements MedicalRecordRepository
{

    public function list(array $params)
    {

        $medical_records = $this->model

        ->with(['patient.profile' , 'staff_created' , 'staff_updated'])

        ->when(isset($params['search']), function ($query) use ($params) {


            return $query->whereHas('patient' , function($patient) use ($params) {

                $patient->whereHas('profile', function ($profile) use ($params) {
                    $profile->where('name', 'like', '%' . $params['search'] . '%');
                })
                ->orWhere('code', 'like', '%' . $params['search'] . '%');

            })
            
            ->orWhere('code', 'like', '%' . $params['search'] . '%');


        })


        ->when(isset($params['tanggal']), function ($query) use ($params) {
            return $query->whereBetween('tanggal', $params['tanggal']);
        })
        ->orderBy('created_at', 'desc');

        return $medical_records->paginate(
            isset($params['per_page']) ? $params['per_page'] : 10,
            ['*'],
            'page',
            isset($params['current_page']) ? $params['current_page'] : null
        );
    }
    
}
