<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class AiModulesDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /* $jsonString = file_get_contents(base_path('database/seeder-data/ai-modules.json'));

        $dataArr = json_decode($jsonString, true);

        DB::table('aimodules')->insert($dataArr); */
        $jsonString = file_get_contents(base_path('database/seeder-data/ai-modules.json'));
        $dataArr = json_decode($jsonString, true);

        // Convert arrays and nested objects to JSON strings
        foreach ($dataArr as &$item) {
            if (isset($item['content'])) {
                $item['content'] = json_encode($item['content']);
            }
            // Similarly, convert other nested data as needed
        }
        DB::table('aimodules')->truncate();
        // Insert the modified data into the database
        DB::table('aimodules')->insert($dataArr);
    }
}
