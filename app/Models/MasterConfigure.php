<?php

namespace App\Models;

use App\Enums\MasterConfigureEnums;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterConfigure extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'status'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function scopeShow($query)
    {
        return $query->where('status', MasterConfigureEnums::STATUS_SHOW);
    }
}
