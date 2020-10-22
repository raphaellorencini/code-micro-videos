<?php

namespace Tests\Feature\Models;

use App\Models\Category;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use DatabaseMigrations;

    public function testList()
    {
        factory(Category::class, 1)->create();
        $categories = Category::all();
        $this->assertCount(1, $categories);
        $categoriesKeys = array_keys($categories->first()->getAttributes());
        $this->assertEqualsCanonicalizing(['id', 'name', 'description', 'is_active', 'created_at', 'updated_at', 'deleted_at'], $categoriesKeys);
    }

    public function testCreate()
    {
        $obj = Category::create([
            'name' => 'teste1',
        ]);
        $obj->refresh();
        $this->assertEquals(36, strlen($obj->id));
        $this->assertEquals('teste1', $obj->name);
        $this->assertNull($obj->description);
        $this->assertTrue($obj->is_active);

        $obj = Category::create([
            'name' => 'teste'.rand(1000, 9999),
            'description' => null,
        ]);
        $this->assertNull($obj->description);

        $obj = Category::create([
            'name' => 'teste'.rand(1000, 9999),
            'description' => 'teste descrição',
        ]);
        $this->assertEquals('teste descrição', $obj->description);

        $obj = Category::create([
            'name' => 'teste'.rand(1000, 9999),
            'is_active' => false,
        ]);
        $this->assertFalse($obj->is_active);

        $obj = Category::create([
            'name' => 'teste'.rand(1000, 9999),
            'is_active' => true,
        ]);
        $this->assertTrue($obj->is_active);
    }

    public function testUpdate()
    {
        /** @var Category $obj */
        $obj = factory(Category::class)->create([
            'description' => 'test_description',
            'is_active' => false,
        ]);

        $data = [
            'name' => 'teste_name_updated',
            'description' => 'test_description_updated',
            'is_active' => true,
        ];
        $obj->update($data);

        foreach ($data as $key => $value) {
            $this->assertEquals($value, $obj->{$key});
        }
    }

    public function testDelete()
    {
        /** @var Category $obj */
        $obj = factory(Category::class)->create();
        $obj->delete();
        $this->assertNull(Category::find($obj->id));

        $obj->restore();
        $this->assertNotNull(Category::find($obj->id));
    }
}
