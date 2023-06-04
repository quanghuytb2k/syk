<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use SoftDeletes, HasFactory;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'companies';

    public $timestamps = true;
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'name',
        'post_code',
        'prefecture_id',
        'building',
        'municipality',
        'street',
        'business_type',
        'is_stock_listing',
        'job_title',
        'department',
        'phone',
        'email',
        'memo',
        'status',
        'is_export_report',
        'homepage',
        'agency_id',
        'contact_person_name'
    ];
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function prefecture(): HasOne
    {
        return $this->hasOne(Prefecture::class, 'id', 'prefecture_id');
    }

    public function user(): HasMany
    {
        return $this->hasMany(User::class, 'company_id', 'id');
    }
}
