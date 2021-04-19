<?php

namespace Tests\Feature\Http\Controllers\Api\VideoController;

use App\Models\Category;
use App\Models\Genre;
use App\Models\Video;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\TestResponse;
use Tests\TestCase;
use Tests\Traits\TestParameters;

class BaseVideoControllerTestCase extends TestCase
{
    use DatabaseMigrations, TestParameters;

    protected $video;
    protected $sendData;

    protected function setUp(): void
    {
        parent::setUp();

        $this->video = factory(Video::class)->create([
            'opened' => false,
        ]);
        $category = factory(Category::class)->create();
        $genre = factory(Genre::class)->create();
        $genre->categories()->sync($category->id);
        $this->sendData = [
            'title' => 'title',
            'description' => 'description',
            'year_launched' => 2010,
            'rating' => Video::RATING_LIST[0],
            'duration' => 90,
            'categories_id' => [$category->id],
            'genres_id' => [$genre->id],
        ];

        $this->routeUpdateParam = ['video' => $this->video->id];
    }

    protected function model()
    {
        return Video::class;
    }

    protected function route($routeName, array $params = [])
    {
        return route("api.videos.{$routeName}", $params);
    }

    protected function routeStore()
    {
        return $this->route('store');
    }

    protected function routeUpdate()
    {
        return $this->route('update', $this->routeUpdateParam);
    }

    protected function assertIfFileUrlExists(Video $video, TestResponse $response)
    {
        $fileFields = Video::$fileFields;
        $data = $response->json('data');
        $data = array_key_exists(0, $data) ? $data[0] : $data;
        foreach ($fileFields as $field) {
            $file = $video->{$field};
            $fileUrl = filled($file) ? \Storage::url($video->relativeFilePath($file)) : null;
            $this->assertEquals(
                $fileUrl,
                $data[$field.'_url']
            );
        }
    }
}
