<?php

namespace Tests\Stubs\Models;

use App\Models\Traits\UploadFiles;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UploadFilesStub extends Model
{
    use UploadFiles;

    /*public static function createTable()
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
    }*/

    protected function uploadDir()
    {
        return "1";
    }
}
