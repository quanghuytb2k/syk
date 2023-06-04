<?php

namespace App\Services;

use App\Models\EquipmentType;
use App\Repositories\BuildingConstructionType\BuildingConstructionTypeRepositoryInterface;
use App\Repositories\BuildingType\BuildingTypeRepositoryInterface;
use App\Repositories\DrawingType\DrawingTypeRepository;
use App\Repositories\EnergyType\EnergyTypeRepositoryInterface;
use App\Repositories\MaintainCompany\MaintainCompanyDetailTypeRepositoryInterface;
use App\Repositories\MaintainCompany\MaintainCompanyTypeRepositoryInterface;
use App\Repositories\Prefecture\PrefectureRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MasterConfigureService
{
    public function __construct(
        protected EnergyTypeRepositoryInterface $energyTypeRepository,
        protected BuildingTypeRepositoryInterface $buildingTypeRepository,
        protected BuildingConstructionTypeRepositoryInterface $buildingConstructionTypeRepository,
        protected PrefectureRepositoryInterface $prefectureRepository,
        protected DrawingTypeRepository $drawingTypeRepository,
        protected MaintainCompanyTypeRepositoryInterface $maintainCompanyTypeRepository,
        protected MaintainCompanyDetailTypeRepositoryInterface $maintainCompanyDetailTypeRepository
    )
    {}

    /**
     * @param string $table
     * @return array
     */
    public function getMasterTable(string $table): array
    {
        $columns = [];
        switch ($table) {
            case 'm_energy_types':
                $columns = $this->energyTypeRepository->getFillable();
                break;
            case 'm_building_types':
                $columns = $this->buildingTypeRepository->getFillable();
                break;
            case 'm_building_construction_types':
                $columns = $this->buildingConstructionTypeRepository->getFillable();
                break;
            case 'm_prefectures':
                $columns = $this->prefectureRepository->getFillable();
                break;
            case 'm_drawing_types':
                $columns = $this->drawingTypeRepository->getFillable();
                break;
            case 'm_maintain_company_types':
                $columns = $this->maintainCompanyTypeRepository->getFillable();
                break;
            case 'm_maintain_company_detail_types':
                $columns = $this->maintainCompanyDetailTypeRepository->getFillable();
                break;
        }

        return [
            'column' => $columns,
            'data_table' => DB::table($table)->select(array_merge($columns, ['id']))->orderBy('order')->get()
        ];
    }

    /**
     * @param string $table
     * @param int $id
     * @param array $dataUpdate
     * @return bool
     */
    public function updateRecordMasterTable(string $table, int $id, array $dataUpdate): bool
    {
        try {
            DB::table($table)->where('id', $id)->update($dataUpdate);
            return true;
        } catch (\Exception $exception) {
            Log::error($exception);
            return false;
        }
    }

    /**
     * @param string $table
     * @param array $insertData
     * @return bool
     */
    public function createRecordMasterTable(string $table, array $insertData): bool
    {
        try {
            $order = DB::table($table)->select('order')->orderBy('order', 'desc')->first();
            $insertData['order'] = ($order->order ?? 0) + 1;

            DB::table($table)->insert($insertData);
            return true;
        } catch (\Exception $exception) {
            Log::error($exception);
            return false;
        }
    }

    /**
     * @param string $table
     * @param int $id
     * @return bool
     */
    public function deleteRecordMasterTable(string $table, int $id): bool
    {
        try {
            DB::table($table)->delete($id);
            return true;
        } catch (\Exception $exception) {
            Log::error($exception);
            return false;
        }
    }

    /**
     * @return array
     */
    public function getDivisionTreeView(): array
    {
        $divisions = EquipmentType::all()->toArray();
        return self::buildTree($divisions);
    }

    /**
     * build tree item
     * @param $categories
     * @param $parent
     * @return array
     */
    public static function buildTree($categories, $parent = null): array
    {
        $data = [];
        foreach ($categories as $category){
            $item = [];
            if ($category['parent'] == $parent){
                $item = [
                    'value' => $category['id'],
                    'title' => $category['name']
                ];
                if (self::hasChild($categories, $category['id'])){
                    $item['children'] = self::buildTree($categories, $category['id']);
                }
            }
            if ($item){
                $data[] = $item;
            }
        }
        return $data;
    }

    /**
     * check is has child category
     * @param $categories
     * @param $id
     * @return boolean
     */
    public static function hasChild($categories, $id): bool
    {
        foreach ($categories as $category){
            if ($category['parent'] == $id){
                return true;
            }
        }
        return false;
    }
}
