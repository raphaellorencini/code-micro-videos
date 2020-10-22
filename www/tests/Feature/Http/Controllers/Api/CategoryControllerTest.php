<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Category;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
}
