<?php

namespace Database\Seeders;

use App\Models\API\Page;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jsonData = file_get_contents(database_path() . '/data/pages.json');
        $data = json_decode($jsonData, true);

        foreach ($data as $item) {
            Page::create([
                'slug'        => strtolower($item['slug']),
                'name'        => $item['name'],
                'description' => $item['description'],
                'active'      => (bool) $item['active'],
                'list_order'  => (int) $item['list_order'],
                'footer_link' => $item['footer_link'],
                'meta_title'  => $item['meta_title']
            ]);
        }
    }
}
