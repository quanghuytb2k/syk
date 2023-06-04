<?php

namespace App\Models;

use App\Enums\EquipmentEnums;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Equipment extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'equipments';

    protected $fillable = [
        'name',
        'equipment_type_id',
        'maker',
        'model',
        'capacity',
        'installation_detail_area',
        'installation_date',
        'contract_type',
        'memo',
        'agency_id',
        'company_id',
        'facility_id',
        'building_id',
        'equipment_lease_company',
        'equipment_buy_company',
        'next_maintenance_period',
        'maintenance_time'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'equipment_lease_company' => 'array',
        'equipment_buy_company' => 'array'
    ];

    protected $appends = [
        'contract_type_text'
    ];

    /**
     * @return string
     */
    public function getContractTypeTextAttribute(): string
    {
        return EquipmentEnums::getContractTypeParams($this->contract_type);
    }

    /**
     * @return BelongsTo
     */
    public function agency(): BelongsTo
    {
        return $this->belongsTo(Agency::class, 'agency_id');
    }

    /**
     * @return BelongsTo
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    /**
     * @return BelongsTo
     */
    public function facility(): BelongsTo
    {
        return $this->belongsTo(Facility::class, 'facility_id');
    }

    /**
     * @return BelongsTo
     */
    public function building(): BelongsTo
    {
        return $this->belongsTo(Building::class, 'building_id');
    }

    /**
     * @return BelongsTo
     */
    public function equipment_type(): BelongsTo
    {
        return $this->belongsTo(EquipmentType::class, 'equipment_type_id');
    }
}
