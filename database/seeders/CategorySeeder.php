<?php

namespace Database\Seeders;

use App\Models\API\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jsonData = file_get_contents(database_path() . '/data/categories.json');
        $data = json_decode($jsonData, true);

        foreach ($data as $item) {
            Category::create([
                'slug' => strtolower($item['slug']),
                'name' => $item['name'],
                'active' => (bool) $item['active']
            ]);
        }
    }
}
