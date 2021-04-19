<?php

namespace App\Models;

use App\Models\Traits\UploadFiles;
use App\Models\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Video extends Model
{
    use SoftDeletes, Uuid, UploadFiles;

    const NO_RATING = 'L';
    const RATING_LIST = [self::NO_RATING, '10', '12', '14', '16', '18'];

    const THUMB_FILE_MAX_SIZE = 5120;//1024 * 5 - 5MB
    const BANNER_FILE_MAX_SIZE = 10240;//1024 * 10 - 10MB;
    const TRAILER_FILE_MAX_SIZE = 1048576;//1024 * 1024 - 1GB;
    const VIDEO_FILE_MAX_SIZE = 52428800;//1024 * 1024 * 50 - 50GB;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'id' => 'string',
        'opened' => 'boolean',
        'year_launched' => 'int',
        'duration' => 'int',
    ];

    protected $fillable = [
        'title',
        'description',
        'year_launched',
        'opened',
        'rating',
        'duration',
        'video_file',
        'thumb_file',
        'trailer_file',
        'banner_file',
    ];

    public static $fileFields = [
        'video_file',
        'thumb_file',
        'trailer_file',
        'banner_file',
    ];

    public static function create(array $attributes = [])
    {
        $obj = null;
        $files = self::extractFiles($attributes);
        try {
            \DB::beginTransaction();
            /** @var Video $obj */
            $obj = static::query()->create($attributes);
            static::handleRelations($obj, $attributes);
            $obj->uploadFiles($files);
            \DB::commit();
        } catch (\Exception $e) {
            if(filled($obj)) {
                $obj->deleteFiles($files);
            }
            \DB::rollBack();
            throw $e;
        }
        return $obj;
    }

    public function update(array $attributes = [], array $options = [])
    {
        $saved = false;
        $files = self::extractFiles($attributes);
        try {
            \DB::beginTransaction();
            $saved = parent::update($attributes, $options);
            static::handleRelations($this, $attributes);
            if($saved) {
                $this->uploadFiles($files);
            }
            \DB::commit();

            if($saved && count($files)){
                $this->deleteOldFiles();
            }
        } catch (\Exception $e) {
            $this->deleteFiles($files);
            \DB::rollBack();
            throw $e;
        }
        return $saved;
    }

    public static function handleRelations(Video $video, array $attributes)
    {
        $attributes = collect($attributes);
        if($attributes->has('categories_id')) {
            $video->categories()->sync($attributes->get('categories_id'));
        }
        if($attributes->has('genres_id')) {
            $video->genres()->sync($attributes->get('genres_id'));
        }
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class)->withTrashed();
    }

    public function genres()
    {
        return $this->belongsToMany(Genre::class)->withTrashed();
    }

    protected function uploadDir()
    {
        return $this->id;
    }

    public function getThumbFileUrlAttribute()
    {
        return $this->thumb_file ? $this->getFileUrl($this->thumb_file) : null;
    }

    public function getBannerFileUrlAttribute()
    {
        return $this->banner_file ? $this->getFileUrl($this->banner_file) : null;
    }

    public function getTrailerFileUrlAttribute()
    {
        return $this->trailer_file ? $this->getFileUrl($this->trailer_file) : null;
    }

    public function getVideoFileUrlAttribute()
    {
        return $this->video_file ? $this->getFileUrl($this->video_file) : null;
    }
}
