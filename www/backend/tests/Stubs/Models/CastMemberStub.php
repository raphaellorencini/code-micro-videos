<?php

namespace Tests\Stubs\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CastMemberStub extends Model
{
    const TYPE_DIRECTOR = 1;
    const TYPE_ACTOR = 2;

    protected $table = 'cast_members_stubs';
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

    public static function createTable()
    {
        Schema::create('cast_members_stubs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->unique();
            $table->smallInteger('type');
            $table->timestamps();
        });
    }

    public static function dropTable()
    {
        Schema::dropIfExists('cast_members_stubs');
    }
}
