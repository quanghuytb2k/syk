<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Building extends Model
{
    use SoftDeletes, HasFactory;

    protected $table = 'buildings';

    protected $fillable = [
        'name',
        'agency_id',
        'company_id',
        'building_type_id',
        'building_construction_type_id',
        'is_direct_profit',
        'build_area_square',
        'area_square',
        'employee_count',
        'memo',
        'floor_count',
        'facility_id',
        'status'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * @return HasOne
     */
    public function agency(): HasOne
    {
        return $this->hasOne(Agency::class, 'id', 'agency_id');
    }

    /**
     * @return HasOne
     */
    public function company(): HasOne
    {
        return $this->hasOne(Company::class, 'id', 'company_id');
    }

    /**
     * @return HasOne
     */
    public function facility(): HasOne
    {
        return $this->hasOne(Facility::class, 'id', 'facility_id');
    }

    /**
     * @return HasOne
     */
    public function buildingType(): HasOne
    {
        return $this->hasOne(BuildingType::class, 'id', 'building_type_id');
    }

    /**
     * @return HasOne
     */
    public function construction(): HasOne
    {
        return $this->hasOne(BuildingConstructionType::class, 'id', 'building_construction_type_id');
    }
}
