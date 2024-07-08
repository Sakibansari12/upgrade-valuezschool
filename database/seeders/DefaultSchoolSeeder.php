<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\School;

class DefaultSchoolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        School::create([
            "school_name" => "Valuez Public School",
            "primary_person" => "Valuez Primary Person", 
            "primary_email" => "valuez@gmail.com",
            "primary_mobile" => "1111111111",
            "second_email" => "valuezsecond@gmail.com",
            "second_mobile" => "2222222222",
            "mobile" => "3333333333",
            "access_code" => "vz1234",
            "address" => "Gaur City",
            "licence" => "25",
            "school_desc" => "valuez description",
            //"school_logo" => ,
            "package_start" => "2024-01-01",
            "package_end" => "2030-01-01",
            "state_id" => 2,
            "city_id" => 36,
            "pincode" => 6567,
            "is_deleted" => 0,
            "created_at" => now(),
            "status" => 1,
        ]);
    }
}
