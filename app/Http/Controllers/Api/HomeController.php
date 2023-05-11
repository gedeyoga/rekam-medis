<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MedicalRecord;
use App\Models\Patient;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $patient_all = Patient::all();
        $medical_record_all = MedicalRecord::all();

        return response()->json([
            'patient_all' => $patient_all->count(),
            'medical_record_all' => $medical_record_all->count(),
            'patient_day' => Patient::whereDate('created_at', date('Y-m-d'))->count(),
            'medical_record_day' => MedicalRecord::whereDate('tanggal', date('Y-m-d'))->count(),
        ]);
    }
}
