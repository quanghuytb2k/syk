<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class MaintainHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'equipment_id',
        'date',
        'money',
        'maintenance_person_name',
        'content',
        'files',
        'next_maintenance_date',
        'next_alarm_date',
        'maintain_company_id'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $casts = [
        'files' => 'array',
    ];

    /**
     * @return HasOne
     */
    public function maintainCompany(): HasOne
    {
        return $this->hasOne(MaintainCompany::class, 'id', 'maintain_company_id');
    }

    public function equipment(): HasOne
    {
        return $this->hasOne(Equipment::class, 'id', 'equipment_id');
    }
}
