<?php

namespace Tests\Feature\Models\Video;

use App\Models\Category;
use App\Models\Genre;
use App\Models\Video;
use Illuminate\Database\Events\TransactionCommitted;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\Exceptions\TestException;

class VideoUploadTest extends BaseVideoTestCase
{
    public function testCreateWithFiles()
    {
        \Storage::fake();
        $files = $this->getFiles();
        $video = Video::create(
            $this->data + $files
        );

        \Storage::assertExists("{$video->id}/{$video->thumb_file}");
        \Storage::assertExists("{$video->id}/{$video->video_file}");
        //\Storage::assertExists("{$video->id}/{$video->banner_file}");
        //\Storage::assertExists("{$video->id}/{$video->trailer_file}");

        //$this->assertEquals("/storage/{$video->id}/{$files['video_file']->hashName()}", $video->video_file_url);
        //$this->assertEquals("/storage/{$video->id}/{$files['thumb_file']->hashName()}", $video->thumb_file_url);
        //$this->assertEquals("/storage/{$video->id}/{$files['banner_file']->hashName()}", $video->banner_file_url);
        //$this->assertEquals("/storage/{$video->id}/{$files['trailer_file']->hashName()}", $video->trailer_file_url);


    }

    /*public function testFileUrlsWithLocalDriver()
    {
        $fileFields = [];
        $files = $this->getFiles();
        foreach (Video::$fileFields as $field) {
            $fileFields[$field] = $files[$field]->hashName();
        }
        $video = factory(Video::class)->create($fileFields + $files);
        $localDriver = config("filesystems.default");

        $baseUrl = config("filesystems.disks." . $localDriver)["url"];

        foreach ($fileFields as $field => $value) {
            $fileUrl = $video->{"{$field}_url"};

            $this->assertEquals("{$baseUrl}/$video->id/$value", $fileUrl);
        }
    }*/

    /*public function testFileUrlsWithGcsDriver()
    {
        $fileFields = [];
        $files = $this->getFiles();
        foreach (Video::$fileFields as $field) {
            $fileFields[$field] = $files[$field]->hashName();
        }
        $video = factory(Video::class)->create($fileFields + $files);

        $baseUrl = config("filesystems.disks.gcs.storage_api_uri");
        \Config::set("filesystems.default", 'gcs');

        foreach ($fileFields as $field => $value) {
            $fileUrl = $video->{"{$field}_url"};

            $this->assertEquals("{$baseUrl}/$video->id/$value", $fileUrl);
        }
    }*/

    /*public function testUpdateWithFiles()
    {
        \Storage::fake();
        $files = [
            'thumb_file' => UploadedFile::fake()->image('thumb.jpg'),
            'video_file' => UploadedFile::fake()->image('video.mp4'),
            //'banner_file' => UploadedFile::fake()->image('banner_file.jpg'),
            //'trailer_file' => UploadedFile::fake()->image('trailer_file.mp4'),
        ];

        $video = factory(Video::class)->create();
        $video->update($this->data + $files);
        \Storage::assertExists("{$video->id}/{$video->thumb_file}");
        \Storage::assertExists("{$video->id}/{$video->video_file}");
        //\Storage::assertExists("{$video->id}/{$video->banner_file}");
        //\Storage::assertExists("{$video->id}/{$video->trailer_file}");

        $newVideoFile = UploadedFile::fake()->image("video2.mp4");
        $video->update([
            "video_file" => $newVideoFile
        ]);

        \Storage::assertExists("{$video->id}/{$files['thumb_file']->hashName()}");
        \Storage::assertExists("{$video->id}/{$newVideoFile->hashName()}");
        \Storage::assertMissing("{$video->id}/{$files['video_file']->hashName()}");
    }*/

    public function testCreateIfRollbackFiles()
    {
        \Storage::fake();
        \Event::listen(TransactionCommitted::class, function () {
            throw new TestException();
        });
        $hasError = false;

        try {
            Video::create(
                $this->data + [
                    'thumb_file' => UploadedFile::fake()->image('thumb.jpg'),
                    'video_file' => UploadedFile::fake()->image('video.mp4'),
                    //'banner_file' => UploadedFile::fake()->image('banner_file.jpg'),
                    //'trailer_file' => UploadedFile::fake()->image('trailer_file.mp4'),
                ]
            );
        } catch (TestException $e) {
            $this->assertCount(0, \Storage::allFiles());
            $hasError = true;
        }


        $this->assertTrue($hasError);

    }

    /*public function testUpdateIfRollbackFiles()
    {
        \Storage::fake();
        \Event::listen(TransactionCommitted::class, function () {
            throw new TestException();
        });

        $hasError = false;
        $video = factory(Video::class)->create();
        try {
            $files = [
                'thumb_file' => UploadedFile::fake()->image('thumb.jpg'),
                'video_file' => UploadedFile::fake()->image('video.mp4'),
                //'banner_file' => UploadedFile::fake()->image('banner_file.jpg'),
                //'trailer_file' => UploadedFile::fake()->image('trailer_file.mp4'),
            ];

            $video->update($this->data + $files);
        } catch (TestException $e) {
            $this->assertCount(0, \Storage::allFiles());
            $hasError = true;
        }


        $this->assertTrue($hasError);

    }*/


    private function getFiles()
    {
        return [
            'thumb_file' => UploadedFile::fake()->image('thumb.jpg'),
            'video_file' => UploadedFile::fake()->image('video.mp4'),
            //'banner_file' => UploadedFile::fake()->image('banner_file.jpg'),
            //'trailer_file' => UploadedFile::fake()->image('trailer_file.mp4'),
        ];
    }
}
