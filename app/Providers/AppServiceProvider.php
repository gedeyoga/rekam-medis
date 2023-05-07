<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\MedicalRecord;
use App\Models\Patient;
use App\Models\Post;
use App\Models\Role;
use App\Models\User;
use App\Repositories\CategoryRepository;
use App\Repositories\Eloquent\EloquentCategoryRepository;
use App\Repositories\Eloquent\EloquentMedicalRecordRepository;
use App\Repositories\Eloquent\EloquentPatientRepository;
use App\Repositories\Eloquent\EloquentPostRepository;
use App\Repositories\Eloquent\EloquentRoleRepository;
use App\Repositories\Eloquent\EloquentUserRepository;
use App\Repositories\MedicalRecordRepository;
use App\Repositories\PatientRepository;
use App\Repositories\PostRepository;
use App\Repositories\RoleRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerBindings();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    private function registerBindings()
    {
        $this->app->bind(UserRepository::class, function (){

            $repository = new EloquentUserRepository(new User());

            return $repository;

        });

        $this->app->bind(RoleRepository::class, function (){

            $repository = new EloquentRoleRepository(new Role());

            return $repository;

        });

        $this->app->bind(PatientRepository::class, function (){

            $repository = new EloquentPatientRepository(new Patient());

            return $repository;

        });

        $this->app->bind(MedicalRecordRepository::class, function (){

            $repository = new EloquentMedicalRecordRepository(new MedicalRecord());

            return $repository;

        });
    }
}
