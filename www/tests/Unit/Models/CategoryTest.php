<?php

namespace Tests\Unit\Models;

use App\Models\Category;
use App\Models\Traits\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;
use PHPUnit\Framework\TestCase;

class CategoryTest extends TestCase
{
    private $category;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
    }


    protected function setUp(): void
    {
        parent::setUp();

        $this->category = new Category();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    public function testFillableAttribute()
    {
        $expectedFillable = [
            'name',
            'description',
            'is_active',
        ];
        $category = new Category();
        $this->assertEquals($expectedFillable, $this->category->getFillable());
    }

    public function testCastsAttribute()
    {
        $expected = [
            'id' => 'string',
            'is_active' => 'boolean',
        ];
        $this->assertEquals($expected, $this->category->getCasts());
    }

    public function testIncrementingAttribute()
    {
        $expected = false;
        $this->assertFalse($this->category->incrementing);
    }

    public function testDatesAttribute()
    {
        $dates = ['created_at', 'updated_at', 'deleted_at'];
        $categoryDates = $this->category->getDates();
        foreach ($dates as $date) {
            $this->assertContains($date, $categoryDates);
        }
        $this->assertCount(count($dates), $categoryDates);
    }

    public function testIfUseTraits()
    {
        $traits = [
            SoftDeletes::class,
            Uuid::class,
        ];
        $categoryTraits = array_keys(class_uses(Category::class));
        $this->assertEquals($traits, $categoryTraits);
    }
}
