<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    protected $seeders = [
        UserTableSeeder::class,
        PostTableSeeder::class,
        GalleryTableSeeder::class
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach($this->seeders as $seeder)
        {
          $this->call($seeder);
        }
    }
}
