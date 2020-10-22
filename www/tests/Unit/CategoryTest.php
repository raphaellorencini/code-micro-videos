<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\Traits\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use PHPUnit\Framework\TestCase;

class CategoryTest extends TestCase
{
    use DatabaseMigrations;

    public function testFillableAttribute()
    {
        Category::create(['name' => 'aaa']);

        $expectedFillable = [
            'name',
            'description',
            'is_active',
        ];
        $category = new Category();
        $this->assertEquals($expectedFillable, $category->getFillable());
    }

    public function testCastsAttribute()
    {
        $expected = 'string';
        $category = new Category();
        $this->assertEquals($expected, $category->getKeyType());
    }

    public function testIncrementingAttribute()
    {
        $expected = false;
        $category = new Category();
        $this->assertFalse($category->incrementing);
    }

    public function testDatesAttribute()
    {
        $dates = ['created_at', 'updated_at', 'deleted_at'];
        $category = new Category();
        $categoryDates = $category->getDates();
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
