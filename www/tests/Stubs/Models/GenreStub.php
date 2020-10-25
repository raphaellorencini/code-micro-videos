<?php

namespace Tests\Stubs\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class GenreStub extends Model
{
    protected $table = 'genres_stubs';
    protected $fillable = [
        'name',
    ];

    public static function createTable()
    {
        Schema::create('genres_stubs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->unique();
            $table->timestamps();
        });
    }

    public static function dropTable()
    {
        Schema::dropIfExists('genres_stubs');
    }
}
