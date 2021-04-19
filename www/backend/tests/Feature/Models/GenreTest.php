<?php

namespace Tests\Feature\Models;

use App\Models\Genre;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GenreTest extends TestCase
{
    use DatabaseMigrations;

    public function testList()
    {
        factory(Genre::class, 1)->create();
        $objs = Genre::all();
        $this->assertCount(1, $objs);
        $objsKeys = array_keys($objs->first()->getAttributes());
        $this->assertEqualsCanonicalizing(['id', 'name', 'is_active', 'created_at', 'updated_at', 'deleted_at'], $objsKeys);
    }

    public function testCreate()
    {
        $obj = Genre::create([
            'name' => 'teste1',
        ]);
        $obj->refresh();
        $this->assertEquals(36, strlen($obj->id));
        $this->assertRegExp('/[0-9a-f]{8}\b-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-\b[0-9a-f]{12}/', $obj->id);
        $this->assertEquals('teste1', $obj->name);
        $this->assertTrue($obj->is_active);

        $obj = Genre::create([
            'name' => 'teste'.rand(1000, 9999),
            'is_active' => false,
        ]);
        $this->assertFalse($obj->is_active);

        $obj = Genre::create([
            'name' => 'teste'.rand(1000, 9999),
            'is_active' => true,
        ]);
        $this->assertTrue($obj->is_active);
    }

    public function testUpdate()
    {
        /** @var Genre $obj */
        $obj = factory(Genre::class)->create([
            'is_active' => false,
        ]);

        $data = [
            'name' => 'teste_name_updated',
            'is_active' => true,
        ];
        $obj->update($data);

        foreach ($data as $key => $value) {
            $this->assertEquals($value, $obj->{$key});
        }
    }

    public function testDelete()
    {
        /** @var Genre $obj */
        $obj = factory(Genre::class)->create();
        $obj->delete();
        $this->assertNull(Genre::find($obj->id));

        $obj->restore();
        $this->assertNotNull(Genre::find($obj->id));
    }
}
