<?php

namespace Database\Seeders;
use App\Models\EmailTemplate;
use Illuminate\Database\Seeder;

class CreateEmailTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $jsonString = file_get_contents(base_path('database/seeder-data/email-templates.json'));

        $dataArr = json_decode($jsonString, true);
        EmailTemplate::truncate();
        foreach ($dataArr as $data) {
            EmailTemplate::create([
                'template' => $data['template'],
                'group' => strtolower($data['group']),
                'category' => strtolower($data['category']),
                'subject' => $data['subject'],
                'module' => strtolower(str_replace(" ", "-", $data['template'])),
                'description' => $data['description'],
                'admin_body' => $data['admin_body'],
                'school_body' => $data['school_body'],
                'student_body' => $data['student_body'],
                'sequence' => (int)$data['sequence'],
            ]);
        }
    }
}
