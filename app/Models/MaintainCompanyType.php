<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MaintainCompanyType extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'm_maintain_company_types';

    protected $fillable = [
        'name',
        'order'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
