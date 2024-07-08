<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class AppSchoolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $jsonString = file_get_contents(base_path('database/seeder-data/app-settings.json'));

        $dataArr = json_decode($jsonString, true);
        DB::table('app_settings')->truncate();
        DB::table('app_settings')->insert($dataArr);
    }
}
