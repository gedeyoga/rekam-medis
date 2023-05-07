<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreatePatientRequest;
use App\Http\Requests\UpdatePatientRequest;
use App\Http\Resources\PatientTransformer;
use App\Models\Patient;
use App\Repositories\PatientRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;

class PatientController extends Controller
{

    protected $patient_repo;
    protected $user_repo;

    public function __construct()
    {
        $this->patient_repo = app(PatientRepository::class);
        $this->user_repo = app(UserRepository::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = $this->patient_repo->list($request->all());

        return PatientTransformer::collection($data);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreatePatientRequest $request)
    {
        $data = $request->get('profile');
        $data['email'] = time() . '@testing.test';
        $data['password'] = '12345678';

        $user = $this->user_repo->create($data);

        $data_patient = $request->except('profile');

        $data_patient['user_id'] = $user->id;
        

        $patient = $this->patient_repo->create($data_patient);

        return response()->json([
            'message' => 'Pasien berhasil dibuat!',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Patient $patient)
    {
        $patient->load(['profile' , 'staff']);
        return new PatientTransformer($patient);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePatientRequest $request, Patient $patient)
    {
        $data = $request->get('profile');

        $user = $this->user_repo->find($request->get('user_id'));
        $this->user_repo->update($user , $data);

        $data_patient = $request->except('profile');
        $data['user_id'] = $user->id;

        $this->patient_repo->update($patient, $data_patient);

        return response()->json([
            'message' => 'Pasien berhasil diperbaharui!',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Patient $patient)
    {
        $user = $this->user_repo->find($patient->user_id);
        $this->user_repo->destroy($user);

        return response()->json([
            'message' => 'Pasien berhasil dihapus!'
        ]);
    }
}
