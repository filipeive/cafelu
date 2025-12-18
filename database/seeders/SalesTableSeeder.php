<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class SalesTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('sales')->delete();
        $json = File::get(database_path('seeders/json/sales.json'));
        $data = json_decode($json, true);
        foreach ($data as $item) {
            foreach ($item as $key => $value) {
                if ($value === 'NULL') {
                    $item[$key] = null;
                }
            }
            DB::table('sales')->insert($item);
        }
    }
}
