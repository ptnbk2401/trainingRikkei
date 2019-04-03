<?php

use App\Model\Post\PostIndex;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\Storage;

$factory->define(PostIndex::class, function (Faker $faker) {
	$filepath = storage_path('app/public/media/files/posts');
	if(!file_exists($filepath)){
        Storage::makeDirectory($filepath);
    }
    return [
        'pname' => $faker->text,
        'preview_text' => $faker->text,
        'cat_id' => $faker->numberBetween($min = 1, $max = 3) ,
        'picture' => $faker->image($filepath,400,300,null,false),
        'created_at' => new DateTime,
    ];
});
