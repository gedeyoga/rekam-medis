<?php

namespace App\Repositories\Eloquent;

use App\Events\UserWasCreated;
use App\Models\User;
use App\Repositories\PatientRepository;
use Illuminate\Http\Request;

class EloquentPatientRepository extends EloquentBaseRepository implements PatientRepository
{

    public function list(array $params)
    {
        $relations  = [];
        if(isset($params['relations'])){
            $relations = explode(',' , $params['relations']);
        }
        $patients = $this->model->with($relations)
        
        ->when(isset($params['search']) , function($query) use ($params) {
            return $query->whereHas('profile' , function($q) use ($params) {
                $q->where('name' , 'like' , '%'.$params['search'].'%');
            })
            ->orWhere('code' , 'like' , '%'.$params['search'].'%')
            ->orWhere('nik' , 'like' , '%'.$params['search'].'%');
        })

        ->when(isset($params['gender']) , fn($q) => $q->whereHas('profile' , function($query) use($params) {
            return $query->whereIn('gender' , $params['gender']);
        }))

        ->when(isset($params['pengobatan_type']) , fn($q) => $q->whereIn('pengobatan_type' , $params['pengobatan_type']))
        
        ->when(isset($params['created_at']) , function($query) use ($params) {
            $betweens = [
                date('Y-m-01' , strtotime($params['created_at'])),
                date('Y-m-t 23:59:59' , strtotime($params['created_at'])),
            ];

            return $query->whereBetween('created_at', $betweens);
        });

        if(!isset($params['per_page'])) {
            return $patients->get();
        }
        return $patients->paginate(
            isset($params['per_page']) ? $params['per_page'] : 10,
            ['*'],
            'page',
            isset($params['current_page']) ? $params['current_page'] : null
        );
    }
}
