<?php

namespace Tests\Unit\Models;

use App\Models\CastMember;
use App\Models\Traits\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;
use PHPUnit\Framework\TestCase;

class CastMemberUnitTest extends TestCase
{
    private $castMember;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->castMember = new CastMember();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    public function testFillableAttribute()
    {
        $expectedFillable = [
            'name',
            'type',
        ];
        $this->assertEquals($expectedFillable, $this->castMember->getFillable());
    }

    public function testCastsAttribute()
    {
        $expected = [
            'id' => 'string',
            'type' => 'int',
        ];
        $this->assertEquals($expected, $this->castMember->getCasts());
    }

    public function testIncrementingAttribute()
    {
        $this->assertFalse($this->castMember->incrementing);
    }

    public function testDatesAttribute()
    {
        $dates = ['created_at', 'updated_at', 'deleted_at'];
        $castMemberDates = $this->castMember->getDates();
        foreach ($dates as $date) {
            $this->assertContains($date, $castMemberDates);
        }
        $this->assertCount(count($dates), $castMemberDates);
    }

    public function testIfUseTraits()
    {
        $traits = [
            SoftDeletes::class,
            Uuid::class,
        ];
        $castMemberTraits = array_keys(class_uses(CastMember::class));
        $this->assertEquals($traits, $castMemberTraits);
    }
}
