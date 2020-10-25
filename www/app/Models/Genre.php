<?php

namespace App\Models;

use App\Models\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Genre extends Model
{
    use SoftDeletes, Uuid;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'id' => 'string',
        'is_active' => 'boolean',
    ];

    protected $fillable = [
        'name',
        'is_active',
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class)->withTrashed();
    }
}
