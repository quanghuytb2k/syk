<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EnergyFile extends Model
{
    use SoftDeletes, HasFactory;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'energy_files';

    public $timestamps = true;
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'contract_id',
        'usage_id',
        'name',
        'path'
    ];
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
