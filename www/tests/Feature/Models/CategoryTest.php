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
        $category = Category::create([
            'name' => 'teste1',
        ]);
        $category->refresh();
        $this->assertEquals('teste1', $category->name);
        $this->assertNull($category->description);
        $this->assertTrue($category->is_active);

        $category = Category::create([
            'name' => 'teste'.rand(1000, 9999),
            'description' => null,
        ]);
        $this->assertNull($category->description);

        $category = Category::create([
            'name' => 'teste'.rand(1000, 9999),
            'description' => 'teste descrição',
        ]);
        $this->assertEquals('teste descrição', $category->description);

        $category = Category::create([
            'name' => 'teste'.rand(1000, 9999),
            'is_active' => false,
        ]);
        $this->assertFalse($category->is_active);

        $category = Category::create([
            'name' => 'teste'.rand(1000, 9999),
            'is_active' => true,
        ]);
        $this->assertTrue($category->is_active);
    }

    public function testUpdate()
    {
        /** @var Category $category */
        $category = factory(Category::class, 1)->create([
            'description' => 'test_description',
            'is_active' => false,
        ])->first();

        $data = [
            'name' => 'teste_name_updated',
            'description' => 'test_description_updated',
            'is_active' => true,
        ];
        $category->update($data);

        foreach ($data as $key => $value) {
            $this->assertEquals($value, $category->{$key});
        }


    }
}
