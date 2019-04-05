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
        // $this->call(PostTableSeeder::class);
        // $this->call(CatTableSeeder::class);
        // $this->call(GroupsTableSeeder::class);
        // $this->call(UserGroupTableSeeder::class);
        // $this->call(PostCategoryTableSeeder::class);
        $this->call(UserPostTableSeeder::class);
        

    }
}

Class CatTableSeeder extends Seeder 
{
	public function run()
    {
        DB::table('categories')->insert([
            ['name' => 'Giải Trí'],
            ['name' => 'Thời Sự'],
            ['name' => 'Thể Thao'],
        ]);
    }
	
}
Class GroupsTableSeeder extends Seeder 
{
    public function run()
    {
        DB::table('groups')->insert([
            ['gname' => 'admin'],
            ['gname' => 'editor'],
            ['gname' => 'user'],
        ]);
    }
    
}
Class UserGroupTableSeeder extends Seeder 
{
    public function run()
    {
        DB::table('user_group')->insert([
            ['user_id' => '1', 'group_id'=>'1'],
            ['user_id' => '2', 'group_id'=>'2'],
            ['user_id' => '3', 'group_id'=>'3'],
        ]);
    }
    
}
Class PostCategoryTableSeeder extends Seeder 
{
    public function run()
    {
        DB::table('post_category')->insert([
            ['post_id' => '1', 'cat_id'=>'1'],
            ['post_id' => '2', 'cat_id'=>'1'],
            ['post_id' => '3', 'cat_id'=>'1'],
            ['post_id' => '4', 'cat_id'=>'1'],
            ['post_id' => '5', 'cat_id'=>'1'],
            ['post_id' => '6', 'cat_id'=>'2'],
            ['post_id' => '7', 'cat_id'=>'2'],
            ['post_id' => '8', 'cat_id'=>'2'],
            ['post_id' => '9', 'cat_id'=>'2'],
            ['post_id' => '10', 'cat_id'=>'2'],
            ['post_id' => '11', 'cat_id'=>'2'],
            ['post_id' => '12', 'cat_id'=>'3'],
            ['post_id' => '13', 'cat_id'=>'3'],
            ['post_id' => '14', 'cat_id'=>'3'],
            ['post_id' => '15', 'cat_id'=>'3'],
            ['post_id' => '16', 'cat_id'=>'3'],
            ['post_id' => '17', 'cat_id'=>'3'],
            ['post_id' => '18', 'cat_id'=>'3'],
            ['post_id' => '19', 'cat_id'=>'3'],
            ['post_id' => '20', 'cat_id'=>'3'],
            ['post_id' => '1', 'cat_id'=>'2'],
            ['post_id' => '2', 'cat_id'=>'3'],
            ['post_id' => '3', 'cat_id'=>'2'],
            ['post_id' => '4', 'cat_id'=>'2'],
            ['post_id' => '5', 'cat_id'=>'2'],
            ['post_id' => '8', 'cat_id'=>'3'],
            ['post_id' => '9', 'cat_id'=>'3'],
            ['post_id' => '10', 'cat_id'=>'3'],
            ['post_id' => '11', 'cat_id'=>'3'],

        ]);
    }
    
}
Class UserPostTableSeeder extends Seeder 
{
    public function run()
    {
        DB::table('user_post')->insert([
            ['post_id' => '1', 'user_id'=>'1'],
            ['post_id' => '2', 'user_id'=>'1'],
            ['post_id' => '3', 'user_id'=>'1'],
            ['post_id' => '4', 'user_id'=>'1'],
            ['post_id' => '5', 'user_id'=>'1'],
            ['post_id' => '6', 'user_id'=>'2'],
            ['post_id' => '7', 'user_id'=>'2'],
            ['post_id' => '8', 'user_id'=>'2'],
            ['post_id' => '9', 'user_id'=>'2'],
            ['post_id' => '10', 'user_id'=>'2'],
            ['post_id' => '11', 'user_id'=>'2'],
            ['post_id' => '12', 'user_id'=>'3'],
            ['post_id' => '13', 'user_id'=>'3'],
            ['post_id' => '14', 'user_id'=>'3'],
            ['post_id' => '15', 'user_id'=>'3'],
            ['post_id' => '16', 'user_id'=>'3'],
            ['post_id' => '17', 'user_id'=>'3'],
            ['post_id' => '18', 'user_id'=>'3'],
            ['post_id' => '19', 'user_id'=>'3'],
            ['post_id' => '20', 'user_id'=>'3'],
            ['post_id' => '1', 'user_id'=>'2'],
            ['post_id' => '2', 'user_id'=>'3'],
            ['post_id' => '3', 'user_id'=>'2'],
            ['post_id' => '4', 'user_id'=>'2'],
            ['post_id' => '5', 'user_id'=>'2'],
            ['post_id' => '8', 'user_id'=>'3'],
            ['post_id' => '9', 'user_id'=>'3'],
            ['post_id' => '10', 'user_id'=>'3'],
            ['post_id' => '11', 'user_id'=>'3'],
        ]);
    }
    
}