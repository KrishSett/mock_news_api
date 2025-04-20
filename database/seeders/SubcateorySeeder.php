<?php

namespace Database\Seeders;

use App\Models\API\Subcategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubcateorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jsonData = file_get_contents(database_path() . '/data/sub-categories.json');
        $data = json_decode($jsonData, true);

        foreach ($data as $item) {
            Subcategory::create($item);
        }
    }
}
