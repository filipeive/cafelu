<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ClientsTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('clients')->delete();
        $json = File::get(database_path('seeders/json/clients.json'));
        $data = json_decode($json, true);
        foreach ($data as $item) {
            foreach ($item as $key => $value) {
                if ($value === 'NULL') {
                    $item[$key] = null;
                }
            }
            DB::table('clients')->insert($item);
        }
    }
}
