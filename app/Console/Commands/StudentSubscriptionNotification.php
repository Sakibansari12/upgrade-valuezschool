<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use App\Models\{Student,NotificationModel};
class StudentSubscriptionNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'student:notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'student notification';

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
       $students = Student::where('student_status', 'Paid')->whereNotNull('end_date') 
       ->whereDate('end_date', '>', Carbon::now()->format('Y-m-d'))
       ->get();
       foreach ($students as $student) {
           $end_date = isset($student->end_date) ? Carbon::parse($student->end_date)->format('Y-m-d') : '';
           $current_date = now()->format('Y-m-d');
           if (!empty($end_date)) {
               $daysDifference = Carbon::parse($end_date)->diffInDays($current_date);
               if($daysDifference == 15){
                $studentupdate = Student::where('id', $student->id)->first();
                $notifyData = [
                    'title' => 'Hi ' . $student->name . ' ' . $student->last_name,
                    'status' => 1,
                    'show_data_header' => "Hi $student->name! Hope we are able to serve you well! It would be our honor to continue bringing you new content,new values, and new age technology updates. Your subscription expires in 15 days on $end_date. Please use deal code ...... to renew now.",
                    'student_id' => $student->id,
                    'subcrition_noty_type' => 'student',
                    'description' => "Hope we are able to serve you well! It would be our honor to continue bringing you new content, new values, and new age technology updates. Your subscription expires in 15 days on $end_date. Please use deal code ...... to renew now."
                ];
                $studentupdate->update([
                    'student_noty_subcrition' => $notifyData
                ]);
               }elseif($daysDifference == 5){
                $studentupdate = Student::where('id', $student->id)->first();
                $notifyData = [
                 'title' => 'Hi ' . $student->name . ' ' . $student->last_name,
                 'status' => 1,
                 'show_data_header' => "Hi $student->name! The clock is ticking! We have loved bringing you new relevant values and new age technology updates,and it would be an honor to continue with even more zeal. Your subscription expires in 5 days on $end_date. Please use special exclusive deal code ...... to renew now",
                 'student_id' => $student->id, 
                 'subcrition_noty_type' => 'student', 
                 'description' => "The clock is ticking! We have loved bringing you new relevant values and new age technology updates, and it would be an honor to continue. Your subscription expires in 5 days on $end_date. Please use special exclusive deal code ...... to renew now."
                 ];
                 $studentupdate->update([
                    'student_noty_subcrition' => $notifyData
                ]);
            }elseif($daysDifference == 1){
                $studentupdate = Student::where('id', $student->id)->first();
                $notifyData = [
                 'title' => 'Hi ' . $student->name . ' ' . $student->last_name,
                 'status' => 1,
                 'student_id' => $student->id,
                 'show_data_header' => "Hi $student->name! Don't miss the Valuez Express! Keep up the friendship built with Sonali Ma'am, Bindiya,Tejas, Alberto and Roboto. Don't miss out on an experience which keeps you ahead! Your subscription expires in 1  day on $end_date. Please use special exclusive deal code ...... to renew now.", 
                 'subcrition_noty_type' => 'student', 
                 'description' => "Don't miss the Valuez Express! Keep up the friendship built with Sonali Ma'am, Bindiya,Tejas, Alberto and Roboto. Don't miss out on an experience which keeps you ahead! Your subscription expires in 1 day on $end_date. Please use special exclusive deal code ...... to renew now."
                 ];
                 $studentupdate->update([
                    'student_noty_subcrition' => $notifyData
                ]);
            }else{
                return "error";
            }
            
               
           }
       }
        $this->info('Notification sent sucessfully');
    }
}
