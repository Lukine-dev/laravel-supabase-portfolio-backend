<?php
// database/seeders/ServicesTableSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServicesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('services')->insert([
            [
                'name' => 'Web Development',
                'slug' => 'web-development',
                'description' => 'Web development projects and services',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Creatives',
                'slug' => 'creatives',
                'description' => 'Creative projects and services',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}