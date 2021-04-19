<?php

namespace Tests\Stubs\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class VideoStub extends Model
{
    const NO_RATING = 'L';
    const RATING_LIST = [self::NO_RATING, '10', '12', '14', '16', '18'];

    protected $table = 'videos_stubs';
    protected $fillable = [
        'title',
        'description',
        'year_launched',
        'rating',
        'duration',
    ];

    public static function createTable()
    {
        Schema::create('videos_stubs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->text('description');
            $table->smallInteger('year_launched');
            $table->string('rating', 3);
            $table->smallInteger('duration');
            $table->timestamps();
        });
    }

    public static function dropTable()
    {
        Schema::dropIfExists('videos_stubs');
    }
}
