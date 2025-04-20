<?php

namespace Database\Seeders;

use App;
use App\Models\API\HeaderHash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HeaderHashSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jsonData = file_get_contents(database_path() . '/data/header-hash.json');
        $data = json_decode($jsonData, true);

        foreach ($data as $item) {
            HeaderHash::create([
                'route_prefix' => $item['name'],
                'hash' => md5(App::environment("HASH_KEY_SALT") . $item['key']),
            ]);
        }
    }
}
