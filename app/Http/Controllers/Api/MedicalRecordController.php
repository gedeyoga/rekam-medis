<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateMedicalRecordRequest;
use App\Http\Requests\UpdateMedicalRecordRequest;
use App\Http\Resources\MedicalRecordTransformer;
use App\Models\MedicalRecord;
use App\Repositories\MedicalRecordRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;

class MedicalRecordController extends Controller
{

    protected $medical_record_repo;
    protected $user_repo;
    protected $medical_repo;

    public function __construct()
    {
        $this->medical_record_repo = app(MedicalRecordRepository::class);
        $this->user_repo = app(UserRepository::class);
        $this->medical_repo = app(UserRepository::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = $this->medical_repo->list($request->all());

        return MedicalRecordTransformer::collection($data);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateMedicalRecordRequest $request)
    {
        $data = $request->except(['files']);

        $medical_record = $this->medical_repo->create($data);

        $medical_record->addMultipleMediaFromRequest('files')->toMediaCollection('medical_records');

        return response()->json([
            'message' => 'Rekam medis berhasil dibuat!',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(MedicalRecord $medical_record)
    {
        $medical_record->load(['patient.profile' , 'staff_created', 'staff_updated']);
        return new MedicalRecordTransformer($medical_record);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateMedicalRecordRequest $request, MedicalRecord $medical_record)
    {
        $data = $request->except(['files']);

        $this->medical_repo->update( $medical_record, $data);

        if($request->get('files')) {
            if(count($request->files) > 0) {
                $medical_record->addMultipleMediaFromRequest('files')->toMediaCollection('medical_records');
            }
        }

        return response()->json([
            'message' => 'Rekam medis berhasil diperbaharui!',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(MedicalRecord $medical_record)
    {
        $this->medical_record_repo->destroy($medical_record);

        return response()->json([
            'message' => 'Rekam Medis berhasil dihapus!'
        ]);
    }
}
