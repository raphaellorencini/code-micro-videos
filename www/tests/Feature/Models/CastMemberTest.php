<?php

namespace Tests\Feature\Models;

use App\Models\CastMember;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CastMemberTest extends TestCase
{
    use DatabaseMigrations;

    public function testList()
    {
        factory(CastMember::class, 1)->create();
        $castMembers = CastMember::all();
        $this->assertCount(1, $castMembers);
        $castMembersKeys = array_keys($castMembers->first()->getAttributes());
        $this->assertEqualsCanonicalizing(['id', 'name', 'type', 'created_at', 'updated_at', 'deleted_at'], $castMembersKeys);
    }

    public function testCreate()
    {
        $obj = CastMember::create([
            'name' => 'teste1',
            'type' => 1,
        ]);
        $obj->refresh();
        $this->assertEquals(36, strlen($obj->id));
        $this->assertRegExp('/[0-9a-f]{8}\b-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-\b[0-9a-f]{12}/', $obj->id);
        $this->assertEquals('teste1', $obj->name);
        $this->assertIsInt($obj->type);

        $obj = CastMember::create([
            'name' => 'teste'.rand(1000, 9999),
            'type' => 1,
        ]);
        $this->assertEquals(1, $obj->type);

        $obj = CastMember::create([
            'name' => 'teste'.rand(1000, 9999),
            'type' => 2,
        ]);
        $this->assertEquals(2, $obj->type);
    }

    public function testUpdate()
    {
        /** @var CastMember $obj */
        $obj = factory(CastMember::class)->create([
            'type' => 1,
        ]);

        $data = [
            'name' => 'teste_name_updated',
            'type' => 2,
        ];
        $obj->update($data);

        foreach ($data as $key => $value) {
            $this->assertEquals($value, $obj->{$key});
        }
    }

    public function testDelete()
    {
        /** @var CastMember $obj */
        $obj = factory(CastMember::class)->create();
        $obj->delete();
        $this->assertNull(CastMember::find($obj->id));

        $obj->restore();
        $this->assertNotNull(CastMember::find($obj->id));
    }
}
