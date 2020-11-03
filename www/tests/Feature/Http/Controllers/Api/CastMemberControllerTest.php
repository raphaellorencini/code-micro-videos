<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\CastMember;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\Traits\TestSaves;
use Tests\Traits\TestValidations;

class CastMemberControllerTest extends TestCase
{
    use DatabaseMigrations, TestValidations, TestSaves;

    private $cast_member;

    protected function setUp(): void
    {
        parent::setUp();

        $this->cast_member = factory(CastMember::class)->create();
        $this->routeUpdateParam = ['cast_member' => $this->cast_member->id];
    }

    protected function model()
    {
        return CastMember::class;
    }

    protected function route($routeName, array $params = [])
    {
        return route("api.cast_members.{$routeName}", $params);
    }

    public function testIndex()
    {
        $response = $this->get($this->route('index'));
        $response
            ->assertStatus(200)
            ->assertJson([$this->cast_member->toArray()]);
    }

    public function testShow()
    {
        $response = $this->get($this->route('show', ['cast_member' => $this->cast_member->id]));
        $response
            ->assertStatus(200)
            ->assertJson($this->cast_member->toArray());
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
            'type' => '',
        ];
        $this->assertInvalidationStoreAction($data, 'required');
        $this->assertInvalidationUpdateAction($data, 'required');

        $data = [
            'type' => 'aaa',
        ];
        $this->assertInvalidationStoreAction($data, 'in');
        $this->assertInvalidationUpdateAction($data, 'in');
    }

    public function testStore()
    {
        $data = [
            ['name' => 'teste', 'type' => CastMember::TYPE_DIRECTOR],
            ['name' => 'teste2', 'type' => CastMember::TYPE_ACTOR],
        ];
        foreach ($data as $key => $value) {
            $response = $this->assertStore($value, $value + ['deleted_at' => null]);
            $response->assertJsonStructure(['created_at', 'updated_at']);
        }
    }

    public function testUpdate()
    {
        $data = [
            'name' => 'test',
            'type' => CastMember::TYPE_ACTOR,
        ];
        $response = $this->assertUpdate($data, $data + ['deleted_at' => null]);
        $response->assertJsonStructure(['created_at', 'updated_at']);
    }

    public function testDestroy()
    {
        $response = $this->json('DELETE', $this->route('destroy', ['cast_member' => $this->cast_member->id]));
        $response->assertStatus(204);
        $this->assertNull(CastMember::find($this->cast_member->id));
        $this->assertNotNull(CastMember::withTrashed()->find($this->cast_member->id));
    }
}
