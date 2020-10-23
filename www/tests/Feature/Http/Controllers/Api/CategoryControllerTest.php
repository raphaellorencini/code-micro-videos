<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Category;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function testIndex()
    {
        $obj = factory(Category::class)->create();
        $response = $this->get(route('api.categories.index'));

        $response
            ->assertStatus(200)
            ->assertJson([$obj->toArray()]);
    }

    public function testShow()
    {
        $obj = factory(Category::class)->create();
        $response = $this->get(route('api.categories.show', ['category' => $obj->id]));

        $response
            ->assertStatus(200)
            ->assertJson($obj->toArray());
    }

    public function testInvalidationData()
    {
        $response = $this->json('POST', route('api.categories.store'), []);
        $this->assertInvalidationRequired($response);

        $response = $this->json('POST', route('api.categories.store'), [
            'name' => str_repeat('a', 256),
            'is_active' => 'a',
        ]);
        $this->assertInvalidationMax($response);
        $this->assertInvalidationBoolean($response);


        $obj = factory(Category::class)->create();
        $response = $this->json('PUT', route('api.categories.update', ['category' => $obj->id]), []);
        $this->assertInvalidationRequired($response);

        $response = $this->json('PUT', route('api.categories.update', ['category' => $obj->id]), [
            'name' => str_repeat('a', 256),
            'is_active' => 'a',
        ]);
        $this->assertInvalidationMax($response);
        $this->assertInvalidationBoolean($response);
    }

    private function assertInvalidationRequired(TestResponse $response)
    {
        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors(['name'])
            ->assertJsonMissingValidationErrors(['is_active'])
            ->assertJsonFragment([
                trans('validation.required', ['attribute' => 'name']),
            ]);
    }

    private function assertInvalidationMax(TestResponse $response)
    {
        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors(['name'])
            ->assertJsonFragment([
                trans('validation.max.string', ['attribute' => 'name', 'max' => 255]),
            ]);
    }

    private function assertInvalidationBoolean(TestResponse $response)
    {
        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors(['is_active'])
            ->assertJsonFragment([
                trans('validation.boolean', ['attribute' => 'is active']),
            ]);
    }

    public function testStore()
    {
        $response = $this->json('POST', route('api.categories.store'), [
            'name' => 'test',
        ]);
        $id = $response->json('id');
        $obj = Category::find($id);

        $response->assertStatus(201)
            ->assertJson($obj->toArray());
        $this->assertTrue($response->json('is_active'));
        $this->assertNull($response->json('description'));

        $response = $this->json('POST', route('api.categories.store'), [
            'name' => 'test2',
            'description' => 'test_description',
            'is_active' => false,
        ]);
        $response->assertJsonFragment([
            'description' => 'test_description',
            'is_active' => false,
        ]);
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
}
