<?php

use App\Http\Controllers\AgencyController;
use \App\Http\Controllers\AuthController;
use App\Http\Controllers\BuildingController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\EnergyContractController;
use App\Http\Controllers\EnergyFileController;
use App\Http\Controllers\EnergyUsageController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\FacilityController;
use App\Http\Controllers\MaintainCompanyController;
use App\Http\Controllers\MasterConfigureController;
use App\Http\Controllers\AlarmController;
use App\Http\Controllers\PrefectureController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('api')->group(function () {
    // auth api
    Route::prefix('auth')->controller(AuthController::class)->group(function () {
        Route::post('login', 'login');
        Route::post('register', 'register');
        Route::post('activate', 'activate');
        Route::post('forgotpass', 'forgotpass');
        Route::post('resetpass', 'resetpass');
        Route::get('get-all-roles', 'getAllRoles');
        Route::post('refresh-token', 'refresh');

        Route::middleware('auth')->group(function () {
            Route::post('me', 'me');
            Route::post('logout', 'logout');
            Route::post('changepass', 'changepass');
            Route::get('role', 'getRole');
        });
    });

    // private api
    Route::middleware('auth')->group(function () {
        Route::prefix('user')->controller(UserController::class)->group(function () {
            Route::post('list', 'list');
            Route::post('list-screen-data', 'listScreenData');
            Route::post('create', 'create');
            Route::post('update/{id}', 'update');
            Route::get('detail/{id}', 'detail');
            Route::delete('delete/{id}', 'delete');
        });

        Route::prefix('company')->controller(CompanyController::class)->group(function () {
            Route::post('list', 'list');
            Route::get('detail/{id}', 'detail');
            Route::post('create', 'create');
            Route::post('update/{id}', 'update');
            Route::get('all-company-name', 'getAllCompanyName');
            Route::get('get-companies-by-agency/{agency_id}', 'getCompanyByAgency');
            Route::delete('delete-company/{id}', 'delete');
        });

        Route::prefix('facility')->controller(FacilityController::class)->group(function () {
            Route::post('list', 'list');
            Route::get('detail/{id}', 'detail');
            Route::post('create', 'create');
            Route::post('update/{id}', 'update');
            Route::get('all-facility-name', 'getAllFacilityName');
            Route::get('filter-facility-by-parent/{agency_id}/{company_id}', 'filterFacilityByParent');
            Route::delete('delete-facility/{id}', 'delete');
        });

        Route::prefix('agency')->controller(AgencyController::class)->group(function () {
            Route::post('list', 'list')->name('list_agency');
            Route::post('create', 'create')->name('create_agency');
            Route::get('detail/{id}', 'detail')->name('detail_agency');
            Route::post('update/{id}', 'update')->name('update_agency');
            Route::get('all-agency-name', 'getAllAgencyName');
            Route::delete('delete-agency/{id}', 'delete');
        });

        Route::group(['prefix' => 'building'], function () {
            Route::post('/list', [BuildingController::class, 'list']);
            Route::get('/detail/{id}', [BuildingController::class, 'detail']);
            Route::get('/list-building-type', [BuildingController::class, 'listBuildingType']);
            Route::get('/list-construction', [BuildingController::class, 'listConstruction']);
            Route::post('/create', [BuildingController::class, 'create']);
            Route::post('/update/{id}', [BuildingController::class, 'update']);
            Route::delete('delete-building/{id}', [BuildingController::class, 'delete']);
            Route::post('/list-building', [BuildingController::class, 'listBuilding']);
        });

        Route::group(['prefix' => 'energy-contract'], function () {
            Route::post('list-energy-contract', [EnergyContractController::class, 'listContract']);
            Route::get('contract-detail/{id}', [EnergyContractController::class, 'detailContract']);
            Route::get('list-energy-type', [EnergyContractController::class, 'listEnergyType']);
            Route::post('download-file/{id}', [EnergyContractController::class, 'downloadFile']);
            Route::post('create-contract', [EnergyContractController::class, 'createContract']);
            Route::post('upload-file', [EnergyContractController::class, 'uploadFile']);
            Route::post('delete-file/{id}', [EnergyContractController::class, 'deleteFile']);
            Route::post('update-contract/{id}', [EnergyContractController::class, 'updateContract']);
            Route::get('get-contract', [EnergyContractController::class, 'getContractByCondition']);
        });

        Route::group(['prefix' => 'energy-usage'], function () {
            Route::post('list-energy-usage', [EnergyUsageController::class, 'listUsage']);
            Route::get('energy-usage-detail/{usageId}', [EnergyUsageController::class, 'usageDetail']);
            Route::post('download-file/{id}', [EnergyUsageController::class, 'downloadFile']);
            Route::post('create-usage', [EnergyUsageController::class, 'create']);
            Route::post('update-usage/{usageId}', [EnergyUsageController::class, 'update']);
            Route::post('upload-file', [EnergyUsageController::class, 'uploadFile']);
            Route::post('delete-file/{id}', [EnergyUsageController::class, 'deleteFile']);
            Route::delete('delete-usage/{id}', [EnergyUsageController::class, 'delete']);
        });

        Route::group(['prefix' => 'storage'], function () {
            Route::post('upload-file', [EnergyFileController::class, 'uploadFile']);
            Route::get('download-file/{fileId}', [EnergyFileController::class, 'downloadFile']);
            Route::delete('delete-file/{fileId}', [EnergyFileController::class, 'deleteFile']);
        });

        Route::group(['prefix' => 'master'], function () {
            Route::get('get-list-master-table', [MasterConfigureController::class, 'getListMasterTable']);
            Route::post('get-master-table', [MasterConfigureController::class, 'getMasterTable']);
            Route::post('update-record-master-table', [MasterConfigureController::class, 'updateRecordMasterTable']);
            Route::post('create-record-master-table', [MasterConfigureController::class, 'createRecordMasterTable']);
            Route::post('delete-record-master-table', [MasterConfigureController::class, 'deleteRecordMasterTable']);
            Route::get('division-tree-view', [MasterConfigureController::class, 'getDivisionTreeView']);
        });

        Route::group(['prefix' => 'equipment'], function () {
            Route::post('list-equipment', [EquipmentController::class, 'listEquipment']);
            Route::get('equipment-detail/{equipment_id}', [EquipmentController::class, 'equipmentDetail']);
            Route::post('create-equipment', [EquipmentController::class, 'createEquipment']);
            Route::post('update-equipment/{equipment_id}', [EquipmentController::class, 'updateEquipment']);
            Route::post('create-maintain-history', [EquipmentController::class, 'createMaintainHistory']);
            Route::get('get-list-maintain-history/{equipment_id}', [EquipmentController::class, 'getListMaintainHistory']);
            Route::post('upload-maintain-history-files', [EquipmentController::class, 'uploadMaintainHistoryFiles']);
            Route::get('get-history-detail/{history_id}', [EquipmentController::class, 'getHistoryDetail']);
            Route::post('delete-history-file', [EquipmentController::class, 'deleteHistoryFile']);
            Route::post('update-maintain-history/{history_id}', [EquipmentController::class, 'updateMaintainHistory']);
            Route::delete('delete-equipment/{id}', [EquipmentController::class, 'delete']);
        });

        Route::prefix('map')->controller(MapController::class)->group(function () {
            Route::post('list-map', 'list');
            Route::get('list-drawing-type', 'listDrawingType');
            Route::get('detail-map/{id}', 'detail');
            Route::post('download-file/{id}', 'downloadFile');
            Route::post('upload-file', 'uploadFile');
            Route::post('create-map', 'create');
            Route::post('update-map/{id}', 'update');
            Route::post('delete-file/{id}', 'deleteFile');
            Route::delete('delete-map/{id}', 'delete');
        });

        Route::group(['prefix' => 'maintain-company'], function () {
            Route::post('maintain-company-list', [MaintainCompanyController::class, 'list']);
            Route::get('maintain-company-detail/{maintain_id}', [MaintainCompanyController::class, 'detail']);
            Route::post('create-maintain-company', [MaintainCompanyController::class, 'store']);
            Route::post('update-maintain-company/{maintain_id}', [MaintainCompanyController::class, 'update']);
            Route::get('get-type', [MaintainCompanyController::class, 'getTypeList']);
            Route::get('get-detail-type', [MaintainCompanyController::class, 'getDetailTypeList']);
            Route::get('get-all-maintain-company', [MaintainCompanyController::class, 'getAllMaintainCompany']);
            Route::delete('maintain-company-delete/{id}', [MaintainCompanyController::class, 'delete']);
        });

        Route::prefix('alarm')->controller(AlarmController::class)->group(function () {
            Route::post('alarm-list', 'list');
        });
    });

    // Public API
    Route::prefix('prefecture')->controller(PrefectureController::class)->group(function () {
        Route::get('all', 'all');
    });
});
