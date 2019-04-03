<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(PostTableSeeder::class);
        $this->call(CatTableSeeder::class);
    }
}

Class CatTableSeeder extends Seeder 
{
	public function run()
    {
        DB::table('cats')->insert([
            ['cname' => 'Giải Trí'],
            ['cname' => 'Thời Sự'],
            ['cname' => 'Thể Thao'],
        ]);
    }
	
}