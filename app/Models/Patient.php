<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Alfa6661\AutoNumber\AutoNumberTrait;

class Patient extends Model
{
    use HasFactory , AutoNumberTrait;

    protected $fillable = ['code' , 'nik' , 'user_id' , 'pengobatan_type', 'no_bpjs' , 'created_by'];

    public static function boot() {
        parent::boot();

        parent::creating(function($model) {
            $model->created_by = Auth::user()->id;
        });
    }

    public function getAutoNumberOptions()
    {
        return [
            'code' => [
                'format' => function () {
                    return 'P?';
                },
                'length' => 5
            ]
        ];
    }

    public function staff()
    {
        return $this->belongsTo(User::class , 'created_by');
    }

    public function profile()
    {
        return $this->belongsTo(User::class , 'user_id');
    }


}
