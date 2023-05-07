<?php 

namespace App\Repositories;

use App\Models\User;

interface MedicalRecordRepository {
    public function list(array $data);
}