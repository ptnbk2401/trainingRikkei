<?php

use App\Model\Post\PostIndex;
use Illuminate\Database\Seeder;

class PostTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(PostIndex::class, 20)->create();
    }
}
