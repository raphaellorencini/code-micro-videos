<?php

namespace Tests\Stubs\Models\Traits;

use App\Models\Traits\UploadFiles;
use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UploadFileOldStub extends Model
{
    use UploadFiles;

    public static $fileFields = ['file', 'file2'];
    protected $fillable = ["name", 'file', 'file2'];
    protected $table = 'upload_file_stubs';

    public static function makeTable()
    {

        Schema::create('upload_file_stubs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string("name");
            $table->text("file")->nullable();
            $table->text("file2")->nullable();
            $table->timestamps();
        });
    }


    public static function dropTable()
    {
        Schema::dropIfExists('upload_file_stubs');
    }

    protected function uploadDir()
    {
        return "1";
    }

    public function getFileUrlAttribute()
    {
        return $this->getFileUrl($this->file);
    }

    public static function create(array $attributes)
    {
        $files = self::extracFiles($attributes);
        try {
            DB::beginTransaction();
            $obj = static::query()->create($attributes);

            $obj->uploadFiles($files);
            DB::commit();

            return $obj;
        } catch (\Exception $e) {
            if (isset($obj)) {
                $obj->deleteFiles($files);
            }
            DB::rollBack();
            throw $e;
        }

    }

    public function update(array $attributes = [], array $options = [])
    {
        $files = self::extracFiles($attributes);
        try {
            DB::beginTransaction();
            $saved = parent::update($attributes, $options);

            if ($saved) {
                $this->uploadFiles($files);
            }

            DB::commit();

            if ($saved && count($files)) {
                $this->deleteOldFiles();
            }
            return $saved;
        } catch (\Exception $e) {
            $this->deleteFiles($files);
            DB::rollBack();
            throw $e;
        }

    }
}
