<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EnergyType extends Model
{
    use SoftDeletes, HasFactory;

    protected $table = 'm_energy_types';

    protected $fillable = [
        'name',
        'unit',
        'order'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
