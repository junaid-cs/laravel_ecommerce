<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::create([
            'image' => '123.png',
            'name' => 'seeder category',
            'slug' => 'seeder-category',
            'items' => 0,
            'status' => 0,
        ]);
    }
}
