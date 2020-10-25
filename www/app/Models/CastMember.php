<?php

namespace App\Models;

use App\Models\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CastMember extends Model
{
    use SoftDeletes, Uuid;

    const TYPE_DIRECTOR = 1;
    const TYPE_ACTOR = 2;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'id' => 'string',
        'type' => 'int',
    ];

    protected $fillable = [
        'name',
        'type',
    ];

    public static function types()
    {
        return [
            self::TYPE_DIRECTOR,
            self::TYPE_ACTOR,
        ];
    }
}
