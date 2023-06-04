<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class EnergyUsage extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'usage_from_date',
        'usage_to_date',
        'billing_date',
        'money',
        'amount',
        'converted_co2_amount',
        'energy_contract_id',
        'agency_id',
        'company_id',
        'facility_id',
        'building_id',
        'invoice_date',
        'files',
    ];

    protected $casts = [
        'files' => 'array',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * @return BelongsTo
     */
    public function contract(): BelongsTo
    {
        return $this->belongsTo(EnergyContract::class, 'energy_contract_id');
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

    public function files(): HasMany
    {
        return $this->hasMany(EnergyFile::class, 'usage_id', 'id');
    }


}
