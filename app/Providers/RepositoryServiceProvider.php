<?php

namespace App\Providers;

use App\Repositories\AdminRepository;
use App\Repositories\Interfaces\AdminRepositoryInterface;
use App\Repositories\Interfaces\MediaRepositoryInterface;
use App\Repositories\Interfaces\RoleRepositoryInterface;
use App\Repositories\Interfaces\SettingRepositoryInterface;
use App\Repositories\MediaRepository;
use App\Repositories\RoleRepository;
use App\Repositories\SettingRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(
            AdminRepositoryInterface::class, 
            AdminRepository::class
        );

        $this->app->bind(
            RoleRepositoryInterface::class, 
            RoleRepository::class
        );

        $this->app->bind(
            SettingRepositoryInterface::class, 
            SettingRepository::class
        );

        $this->app->bind(
            MediaRepositoryInterface::class, 
            MediaRepository::class
        );
    }

    public function boot()
    {
        //
    }
}