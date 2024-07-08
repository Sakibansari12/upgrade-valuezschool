<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Models\{Student,NotificationModel};
class StudentSubscription extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'student:subscription';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Student Subscription Expire';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
       // return 0;
       $students = Student::where('student_status', 'Paid')
        ->where('end_date', '<', Carbon::now()->format('Y-m-d H:i:s'))
        ->get();

   foreach ($students as $student) {
       $student->update([
           'student_status' => 'Demo',
       ]);
       $studentupdate = Student::where('id', $student->id)->first();
       $notifyData = [
        'title' => 'Hi ' . $student->name . ' ' . $student->last_name,
        'status' => 1, 
        'student_id' => $student->id,
        'show_data_header' => "Hi $student->name! Even though the subscription has expired, you can still hop on board the Valuez Express as its leaving the station! Your friends Sonali Ma'am, Bindiya, Tejas, Alberto,and Roboto can help you catch the train. Don't miss the knowledge journey. Renew now with the exclusive deal code .........",
        'student_noty' => 'student', 
        'description' => "Valuez Express has left the station and you are stranded on the platform! Still there is a chance that your friends Sonali Ma'am, Bindiya,Tejas, Alberto and Roboto can stop, for you to hop on-board. Don't miss out on a knowledge journey which surges you ahead! Please use special exclusive deal code ...... to renew now."
    ];
    $studentupdate->update([
        'student_noty_subcrition' => json_encode($notifyData)
    ]);
   }
   $this->info('Update successful for selected students');
   // dd($student_data);
   }

    
    
}
