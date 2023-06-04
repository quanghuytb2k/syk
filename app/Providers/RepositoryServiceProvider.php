<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{

    public $bindings = [
        \App\Repositories\User\UserRepositoryInterface::class => \App\Repositories\User\UserRepository::class,
        \App\Repositories\Facility\FacilityRepositoryInterface::class => \App\Repositories\Facility\FacilityRepository::class,
        \App\Repositories\Agency\AgencyRepositoryInterface::class => \App\Repositories\Agency\AgencyRepository::class,
        \App\Repositories\Company\CompanyRepositoryInterface::class => \App\Repositories\Company\CompanyRepository::class,
        \App\Repositories\Prefecture\PrefectureRepositoryInterface::class => \App\Repositories\Prefecture\PrefectureRepository::class,
        \App\Repositories\Building\BuildingRepositoryInterface::class => \App\Repositories\Building\BuildingRepository::class,
        \App\Repositories\BuildingType\BuildingTypeRepositoryInterface::class => \App\Repositories\BuildingType\BuildingTypeRepository::class,
        \App\Repositories\BuildingConstructionType\BuildingConstructionTypeRepositoryInterface::class => \App\Repositories\BuildingConstructionType\BuildingConstructionTypeRepository::class,
        \App\Repositories\EnergyType\EnergyTypeRepositoryInterface::class => \App\Repositories\EnergyType\EnergyTypeRepository::class,
        \App\Repositories\EnergyContract\EnergyContractRepositoryInterface::class => \App\Repositories\EnergyContract\EnergyContractRepository::class,
        \App\Repositories\EnergyContractFile\EnergyFileRepositoryInterface::class => \App\Repositories\EnergyContractFile\EnergyFileRepository::class,
        \App\Repositories\EnergyUsage\EnergyUsageRepositoryInterface::class => \App\Repositories\EnergyUsage\EnergyUsageRepository::class,
        \App\Repositories\MasterConfigure\MasterConfigureRepositoryInterface::class => \App\Repositories\MasterConfigure\MasterConfigureRepository::class,
        \App\Repositories\Equipment\EquipmentRepositoryInterface::class => \App\Repositories\Equipment\EquipmentRepository::class,
        \App\Repositories\DrawingType\DrawingTypeRepository::class => \App\Repositories\DrawingType\DrawingTypeRepositoryEloquent::class,
        \App\Repositories\Map\MapRepository::class => \App\Repositories\Map\MapRepositoryEloquent::class,
        \App\Repositories\MaintainCompany\MaintainCompanyRepositoryInterface::class => \App\Repositories\MaintainCompany\MaintainCompanyRepository::class,
        \App\Repositories\MaintainCompany\MaintainCompanyTypeRepositoryInterface::class => \App\Repositories\MaintainCompany\MaintainCompanyTypeRepository::class,
        \App\Repositories\MaintainCompany\MaintainCompanyDetailTypeRepositoryInterface::class => \App\Repositories\MaintainCompany\MaintainCompanyDetailTypeRepository::class,
        \App\Repositories\MaintainHistory\MaintainHistoryRepositoryInterface::class => \App\Repositories\MaintainHistory\MaintainHistoryRepository::class,
        \App\Repositories\Role\RoleRepository::class => \App\Repositories\Role\RoleRepositoryEloquent::class,
    ];

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
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
