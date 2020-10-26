<?php

namespace App\Models;

use App\Models\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Video extends Model
{
    use SoftDeletes, Uuid;

    const NO_RATING = 'L';
    const RATING_LIST = [self::NO_RATING, '10', '12', '14', '16', '18'];

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
    ];

    public static function create(array $attributes = [])
    {
        $obj = null;
        try {
            \DB::beginTransaction();
            $obj = static::query()->create($attributes);
            static::handleRelations($obj, $attributes);
            \DB::commit();
        } catch (\Exception $e) {
            if(filled($obj)) {

            }
            \DB::rollBack();
            throw $e;
        }
        return $obj;
    }

    public function update(array $attributes = [], array $options = [])
    {
        $saved = false;
        try {
            \DB::beginTransaction();
            $saved = parent::update($attributes, $options);
            static::handleRelations($this, $attributes);
            \DB::commit();
        } catch (\Exception $e) {
            if($saved) {

            }
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
}
