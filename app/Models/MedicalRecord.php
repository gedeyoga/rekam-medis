<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Alfa6661\AutoNumber\AutoNumberTrait;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class MedicalRecord extends Model implements HasMedia
{
    use HasFactory, AutoNumberTrait , InteractsWithMedia;

    protected $fillable = ['code' , 'patient_id' , 'created_by' , 'updated_by'];

    public static function boot()
    {
        parent::boot();

        parent::creating(function ($model) {
            $model->created_by = Auth::user()->id;
        });
        parent::updating(function ($model) {
            $model->updated_by = Auth::user()->id;
        });
    }

    public function getAutoNumberOptions()
    {
        return [
            'code' => [
                'format' => function () {
                    return 'RM' . date('Ym') . '?';
                },
                'length' => 5
            ]
        ];
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class , 'patient_id');
    }
    
    public function staff_created()
    {
        return $this->belongsTo(User::class , 'created_by');
    }

    public function staff_updated()
    {
        return $this->belongsTo(User::class , 'updated_by');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('medical_record');
    }
}
