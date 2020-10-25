<?php

namespace Tests\Feature\Models;

use App\Models\Video;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class VideoTest extends TestCase
{
    use DatabaseMigrations;

    public function testList()
    {
        factory(Video::class, 1)->create();
        $videos = Video::all();
        $this->assertCount(1, $videos);
        $videosKeys = array_keys($videos->first()->getAttributes());
        $this->assertEqualsCanonicalizing([
            'id',
            'title',
            'description',
            'year_launched',
            'opened',
            'rating',
            'duration',
            'created_at',
            'updated_at',
            'deleted_at',
        ], $videosKeys);
    }

    public function testCreate()
    {
        $obj = Video::create([
            'title' => 'title2',
            'description' => 'description',
            'year_launched' => 2020,
            'rating' => Video::RATING_LIST[1],
            'duration' => 30,
        ]);
        $obj->refresh();
        $this->assertEquals(36, strlen($obj->id));
        $this->assertRegExp('/[0-9a-f]{8}\b-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-\b[0-9a-f]{12}/', $obj->id);
        $this->assertEquals('title2', $obj->title);
        $this->assertEquals('description', $obj->description);
        $this->assertEquals(2020, $obj->year_launched);
        $this->assertEquals(Video::RATING_LIST[1], $obj->rating);
        $this->assertEquals(30, $obj->duration);
        $this->assertFalse($obj->opened);


        $obj = Video::create([
            'title' => 'title',
            'description' => 'description',
            'year_launched' => 2010,
            'rating' => Video::RATING_LIST[0],
            'duration' => 90,
            'opened' => true,
        ]);
        $obj->refresh();
        $this->assertEquals('title', $obj->title);
        $this->assertNotNull($obj->description);
        $this->assertIsInt($obj->year_launched);
        $this->assertEquals(Video::RATING_LIST[0], $obj->rating);
        $this->assertEquals(90, $obj->duration);
        $this->assertTrue($obj->opened);
    }

    public function testUpdate()
    {
        /** @var Video $obj */
        $obj = factory(Video::class)->create([
            'title' => 'title2',
            'description' => 'description2',
            'year_launched' => 2019,
            'rating' => Video::RATING_LIST[1],
            'duration' => 30,
        ]);

        $data = [
            'title' => 'title3',
            'description' => 'description3',
            'year_launched' => 2019,
            'rating' => Video::RATING_LIST[2],
            'duration' => 60,
        ];
        $obj->update($data);

        foreach ($data as $key => $value) {
            $this->assertEquals($value, $obj->{$key});
        }
    }

    public function testDelete()
    {
        /** @var Video $obj */
        $obj = factory(Video::class)->create();
        $obj->delete();
        $this->assertNull(Video::find($obj->id));

        $obj->restore();
        $this->assertNotNull(Video::find($obj->id));
    }
}
