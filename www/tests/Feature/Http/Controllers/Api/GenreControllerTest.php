<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Genre;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GenreControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function testIndex()
    {
        $obj = factory(Genre::class)->create();
        $response = $this->get(route('api.genres.index'));

        $response
            ->assertStatus(200)
            ->assertJson([$obj->toArray()]);
    }

    public function testShow()
    {
        $obj = factory(Genre::class)->create();
        $response = $this->get(route('api.genres.show', ['genre' => $obj->id]));

        $response
            ->assertStatus(200)
            ->assertJson($obj->toArray());
    }

    public function testInvalidationData()
    {
        $response = $this->json('POST', route('api.genres.store'), []);
        $this->assertInvalidationRequired($response);

        $response = $this->json('POST', route('api.genres.store'), [
            'name' => str_repeat('a', 256),
            'is_active' => 'a',
        ]);
        $this->assertInvalidationMax($response);
        $this->assertInvalidationBoolean($response);


        $obj = factory(Genre::class)->create();
        $response = $this->json('PUT', route('api.genres.update', ['genre' => $obj->id]), []);
        $this->assertInvalidationRequired($response);

        $response = $this->json('PUT', route('api.genres.update', ['genre' => $obj->id]), [
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
        $response = $this->json('POST', route('api.genres.store'), [
            'name' => 'test',
        ]);
        $id = $response->json('id');
        $obj = Genre::find($id);

        $response->assertStatus(201)
            ->assertJson($obj->toArray());
        $this->assertTrue($response->json('is_active'));

        $response = $this->json('POST', route('api.genres.store'), [
            'name' => 'test2',
            'is_active' => false,
        ]);
        $response->assertJsonFragment([
            'is_active' => false,
        ]);
    }

    public function testUpdate()
    {
        $obj = factory(Genre::class)->create([
            'is_active' => false,
        ]);
        $response = $this->json('PUT', route('api.genres.update', ['genre' => $obj->id]), [
            'name' => 'test',
            'is_active' => true,
        ]);
        $id = $response->json('id');
        $obj = Genre::find($id);

        $response->assertStatus(200)
            ->assertJson($obj->toArray())
            ->assertJsonFragment([
                'is_active' => true,
            ]);
    }

    public function testDestroy()
    {
        $obj = factory(Genre::class)->create();
        $response = $this->json('DELETE', route('api.genres.destroy', ['genre' => $obj->id]));
        $response->assertStatus(204);
        $this->assertNull(Genre::find($obj->id));
        $this->assertNotNull(Genre::withTrashed()->find($obj->id));
    }
}
