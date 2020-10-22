<?php

namespace Tests\Unit\Models;

use App\Models\Genre;
use App\Models\Traits\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;
use PHPUnit\Framework\TestCase;

class GenrerTest extends TestCase
{
    private $genrer;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->genrer = new Genre();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    public function testFillableAttribute()
    {
        $expectedFillable = [
            'name',
            'is_active',
        ];
        $this->assertEquals($expectedFillable, $this->genrer->getFillable());
    }

    public function testCastsAttribute()
    {
        $expected = [
            'id' => 'string',
            'is_active' => 'boolean',
        ];
        $this->assertEquals($expected, $this->genrer->getCasts());
    }

    public function testIncrementingAttribute()
    {
        $expected = false;
        $this->assertFalse($this->genrer->incrementing);
    }

    public function testDatesAttribute()
    {
        $dates = ['created_at', 'updated_at', 'deleted_at'];
        $genrerDates = $this->genrer->getDates();
        foreach ($dates as $date) {
            $this->assertContains($date, $genrerDates);
        }
        $this->assertCount(count($dates), $genrerDates);
    }

    public function testIfUseTraits()
    {
        $traits = [
            SoftDeletes::class,
            Uuid::class,
        ];
        $genrerTraits = array_keys(class_uses(Genre::class));
        $this->assertEquals($traits, $genrerTraits);
    }
}
