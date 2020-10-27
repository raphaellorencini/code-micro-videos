<?php

namespace Tests\Feature\Models\Video;

use App\Models\Video;

class VideoCrudTest
{
    public function testList()
    {
        factory(Video::class)->create();
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
            'video_file',
            'created_at',
            'updated_at',
            'deleted_at',
        ], $videosKeys);
    }
}
