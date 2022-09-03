<?php

namespace App\Providers;

use App\Repositories\Area\AreaRepository;
use App\Repositories\Area\AreaRepositoryInterface;
use App\Repositories\Attendance\AttendanceRepository;
use App\Repositories\Attendance\AttendanceRepositoryInterface;
use App\Repositories\CoacherCheckin\CoacherCheckinRepository;
use App\Repositories\CoacherCheckin\CoacherCheckinRepositoryInterface;
use App\Repositories\CoacherClassData\CoacherClassDataInterface;
use App\Repositories\CoacherClassData\CoacherClassDataRepository;
use App\Repositories\Permission\PermissionRepository;
use App\Repositories\Permission\PermissionRepositoryInterface;
use App\Repositories\Role\RoleRepository;
use App\Repositories\Role\RoleRepositoryInterface;
use App\Repositories\Staff\StaffRepository;
use App\Repositories\Studio\StudioRepository;
use App\Repositories\Studio\StudioRepositoryInterface;
use App\Repositories\StudioUser\StudioUserRepository;
use App\Repositories\StudioUser\StudioUserRepositoryInterface;
use App\Repositories\User\UserRepository;
use App\Repositories\Staff\StaffRepositoryInterface;
use App\Repositories\Upload\UploadRepository;
use App\Repositories\Upload\UploadRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use App\Repositories\UserOtp\UserOtpRepository;
use App\Repositories\UserOtp\UserOtpRepositoryInterface;
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
        $this->app->singleton(
            AreaRepositoryInterface::class,
            AreaRepository::class,
        );

        $this->app->singleton(
            StaffRepositoryInterface::class,
            StaffRepository::class,
        );

        $this->app->singleton(
            UploadRepositoryInterface::class,
            UploadRepository::class,
        );

        $this->app->singleton(
            RoleRepositoryInterface::class,
            RoleRepository::class,
        );

        $this->app->singleton(
            PermissionRepositoryInterface::class,
            PermissionRepository::class,
        );

        $this->app->singleton(
            UserRepositoryInterface::class,
            UserRepository::class,
        );

        $this->app->singleton(
            UserOtpRepositoryInterface::class,
            UserOtpRepository::class,
        );

        $this->app->singleton(
            StudioUserRepositoryInterface::class,
            StudioUserRepository::class,
        );

        $this->app->singleton(
            AttendanceRepositoryInterface::class,
            AttendanceRepository::class,
        );

        $this->app->singleton(
            CoacherClassDataInterface::class,
            CoacherClassDataRepository::class,
        );

        $this->app->singleton(
            StudioRepositoryInterface::class,
            StudioRepository::class,
        );

        $this->app->singleton(
            CoacherCheckinRepositoryInterface::class,
            CoacherCheckinRepository::class,
        );
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
}
