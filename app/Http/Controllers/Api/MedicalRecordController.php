<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateMedicalRecordRequest;
use App\Http\Requests\UpdateMedicalRecordRequest;
use App\Http\Resources\MedicalRecordTransformer;
use App\Models\MedicalRecord;
use App\Repositories\MedicalRecordRepository;
use App\Repositories\PatientRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MedicalRecordController extends Controller
{

    protected $medical_record_repo;
    protected $user_repo;
    protected $medical_repo;
    protected $patient_repo;

    public function __construct()
    {
        $this->medical_record_repo = app(MedicalRecordRepository::class);
        $this->user_repo = app(UserRepository::class);
        $this->medical_repo = app(MedicalRecordRepository::class);
        $this->patient_repo = app(PatientRepository::class);
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
        $data = $request->except(['files' , 'profile' , 'new_patient']);
        
        DB::beginTransaction();
        try {
            if (boolval($request->get('new_patient'))) {
                $profile = $request->get('profile');
                $profile['email'] = time() . '@testing.test';
                $profile['password'] = '12345678';

                $user = $this->user_repo->create($profile);

                $profile['user_id'] = $user->id;
                $patient = $this->patient_repo->create($profile);

                $data['patient_id'] = $patient->id;
            }


            $medical_record = $this->medical_repo->create($data);

            if ($request->hasFile('medical_files')) {
                $medical_record->addMultipleMediaFromRequest(['medical_files'])
                ->each(function ($fileAdder) {
                    $fileAdder->toMediaCollection('medical_records');
                });
            }
            DB::commit();
            return response()->json([
                'message' => 'Rekam medis berhasil dibuat!',
            ]);
        } catch (\Exception $th) {
            DB::rollBack();

            return response()->json([
                'message' => $th->getMessage(),
            ], 500);
        }

        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(MedicalRecord $medicalrecord)
    {
        $medicalrecord->load(['patient.profile' , 'staff_created', 'staff_updated']);
        return new MedicalRecordTransformer($medicalrecord);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateMedicalRecordRequest $request, MedicalRecord $medicalrecord)
    {
        $data = $request->only(['code', 'tanggal', 'patient_id']);

        //Menghapus file yang telah dihapus oleh user
        $get_new_media = array_map(fn($media) => (int) $media['id'] , $request->medical_record_files); 

        foreach ($medicalrecord->getMedia('medical_records') as $media) {
            if(!in_array($media->id , $get_new_media)) {
                $media->delete();
            }
        }

        $medicalrecord = $this->medical_repo->update( $medicalrecord, $data);

        if ($request->hasFile('medical_files')) {
            $medicalrecord->addMultipleMediaFromRequest(['medical_files'])
            ->each(function ($fileAdder) {
                $fileAdder->toMediaCollection('medical_records');
            });
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
    public function destroy(MedicalRecord $medicalrecord)
    {
        $this->medical_record_repo->destroy($medicalrecord);

        return response()->json([
            'message' => 'Rekam Medis berhasil dihapus!'
        ]);
    }
}
