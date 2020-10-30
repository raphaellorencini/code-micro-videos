<?php

namespace Tests\Feature\Models\Traits;

use Illuminate\Http\UploadedFile;
use Storage;
use Tests\Stubs\Models\Traits\UploadFileStub;
use Tests\TestCase;

class UploadFilesTest extends TestCase
{
    private $obj;

    protected function setUp(): void
    {
        parent::setUp();
        $this->obj = new UploadFileStub();
        UploadFileStub::dropTable();
        UploadFileStub::makeTable();
    }

    public function testMakeOldFilesOnSaving()
    {
        Storage::fake();
        $this->obj->fill([
            "name" => "test",
            "file" => "test1.mp4",
            "file2" => "test2.mp4",
        ]);

        $this->obj->save();
        $this->assertCount(0, $this->obj->oldFiles);

        $this->obj->update([
            "name" => "test_name",
            "file2" => "test3.mp4"
        ]);

        $this->assertEqualsCanonicalizing(["test2.mp4"], $this->obj->oldFiles);
    }

    public function testMakeOldFilesNullOnSaving()
    {
        Storage::fake();
        $this->obj->fill([
            "name" => "test",

        ]);

        $this->obj->save();


        $this->obj->update([
            "name" => "test_name",
            "file2" => "test3.mp4"
        ]);

        $this->assertEqualsCanonicalizing([], $this->obj->oldFiles);
    }

    /*public function testGetFilesUrlNull()
    {
        Storage::fake();
        $this->obj->fill([
            "name" => "test"
        ]);

        $this->obj->save();

        $this->assertNull($this->obj->file_url);
    }*/

    /*public function testFilesUrlExists()
    {
        Storage::fake();

        $file = UploadedFile::fake()->image("file.jpg");
        $obj = UploadFileStub::create([
            "name" => "test",
            "file" => $file,
        ]);

        Storage::assertExists("1/{$obj->file}");
        $this->assertEquals("/storage/1/{$file->hashName()}", $obj->file_url);

    }*/
}
