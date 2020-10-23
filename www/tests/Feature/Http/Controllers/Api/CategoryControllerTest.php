<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Category;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\Traits\TestSaves;
use Tests\Traits\TestValidations;

class CategoryControllerTest extends TestCase
{
    use DatabaseMigrations, TestValidations, TestSaves;

    private $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->category = factory(Category::class)->create();
    }

    private function model()
    {
        return Category::class;
    }

    private function route($routeName, array $params = [])
    {
        return route("api.categories.{$routeName}", $params);
    }

    public function testIndex()
    {
        $response = $this->get(route('api.categories.index'));
        $response
            ->assertStatus(200)
            ->assertJson([$this->category->toArray()]);
    }

    public function testShow()
    {
        $response = $this->get(route('api.categories.show', ['category' => $this->category->id]));
        $response
            ->assertStatus(200)
            ->assertJson($this->category->toArray());
    }

    public function testInvalidationData()
    {
        $data = [
            'name' => '',
        ];
        $this->assertInvalidationStoreAction($data, 'required');
        $this->assertInvalidationUpdateAction($data, 'required');

        $data = [
            'name' => str_repeat('a', 256),
        ];
        $this->assertInvalidationStoreAction($data, 'max.string', ['max' => 255]);
        $this->assertInvalidationUpdateAction($data, 'max.string', ['max' => 255]);

        $data = [
            'is_active' => 'a',
        ];
        $this->assertInvalidationStoreAction($data, 'boolean');
        $this->assertInvalidationUpdateAction($data, 'boolean');
    }

    public function testStore()
    {
        $data = [
            'name' => 'teste',
        ];
        $this->assertStore($data, $data + ['description' => null, 'is_active' => true, 'deleted_at' => null]);

        $data = [
            'name' => 'test2',
            'description' => 'test_description',
            'is_active' => false,
        ];
        $this->assertStore($data, $data + ['description' => 'test_description', 'is_active' => false]);

        /*

        $response = $this->json('POST', route('api.categories.store'), [
            'name' => 'test2',
            'description' => 'test_description',
            'is_active' => false,
        ]);
        $response->assertJsonFragment([
            'description' => 'test_description',
            'is_active' => false,
        ]);*/
    }

    public function testUpdate()
    {
        $obj = factory(Category::class)->create([
            'description' => 'description',
            'is_active' => false,
        ]);
        $response = $this->json('PUT', route('api.categories.update', ['category' => $obj->id]), [
            'name' => 'test',
            'description' => 'test_description',
            'is_active' => true,
        ]);
        $id = $response->json('id');
        $obj = Category::find($id);

        $response->assertStatus(200)
            ->assertJson($obj->toArray())
            ->assertJsonFragment([
                'description' => 'test_description',
                'is_active' => true,
            ]);

        $response = $this->json('PUT', route('api.categories.update', ['category' => $obj->id]), [
            'name' => 'test',
            'description' => '',
        ]);

        $response
            ->assertJsonFragment([
                'description' => null,
            ]);
    }

    public function testDestroy()
    {
        $response = $this->json('DELETE', route('api.categories.destroy', ['category' => $this->category->id]));
        $response->assertStatus(204);
        $this->assertNull(Category::find($this->category->id));
        $this->assertNotNull(Category::withTrashed()->find($this->category->id));
    }
}
