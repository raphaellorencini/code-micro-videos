<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Video;
use Faker\Generator as Faker;

$factory->define(Video::class, function (Faker $faker) {
    $rating = Video::RATING_LIST[array_rand(Video::RATING_LIST)];
    return [
        'title' => $faker->unique()->sentence(3),
        'description' => $faker->sentence(10),
        'year_launched' => rand(1985, 2020),
        'opened' => rand(0, 1),
        'rating' => $rating,
        'duration' => rand(60, 120),
//        'thumb_file' => null,
//        'banner_file' => null,
//        'trailer_file' => null,
//        'video_file' => null,
//        'published' => rand(0, 1),
    ];
});
