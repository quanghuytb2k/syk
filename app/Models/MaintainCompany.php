<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class MaintainCompany extends Model
{
    use HasFactory, SoftDeletes;

    const COMPANY_TYPE =
        [
            'construction' => 0,
            'maintain' => 1,
        ];

    protected $fillable = [
        'name',
        'size_of_employee',
        'ownership',
        'license_number',
        'construction_area',
        'construction_range',
        'prefecture_id',
        'contact_person',
        'contact_person_role',
        'contact_person_phone',
        'email',
        'memo',
        'maintain_company_type_id',
        'maintain_company_detail_type_id',
        'code',
        'street',
        'building',
        'municipality',
        'created_by',
        'company_type',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'address' => 'array'
    ];

    /**
     * @return BelongsTo
     */
    public function prefecture(): BelongsTo
    {
        return $this->belongsTo(Prefecture::class, 'prefecture_id');
    }

    /**
     * @return BelongsTo
     */
    public function type(): BelongsTo
    {
        return $this->belongsTo(MaintainCompanyType::class, 'maintain_company_type_id');
    }

    /**
     * @return BelongsTo
     */
    public function detailType(): BelongsTo
    {
        return $this->belongsTo(MaintainCompanyDetailType::class, 'maintain_company_detail_type_id');
    }
}
