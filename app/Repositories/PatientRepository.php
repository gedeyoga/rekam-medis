<?php 

namespace App\Repositories;

use App\Models\User;

interface PatientRepository {
    public function list(array $data);
    
}