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
        'title' => $faker->text($maxNbChars = 200),
        'preview_text' => $faker->text,
        'content' => $faker->realText,
        'picture' => $faker->image($filepath,400,300,null,false),
        'created_at' => new DateTime,
        'updated_at' => new DateTime,
    ];
});
