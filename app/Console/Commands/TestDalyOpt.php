<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestDalyOpt extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'student:otp';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Student OTP';

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
       $apiUrl = 'https://enterprise.smsgupshup.com/GatewayAPI/rest';
            $postData = [
                'userid' => 2000215380,
                'password' => 'D5cgrl4y4',
                'method' => 'SendMessage',
                'msg' => 2222 . ' is your one-time password (OTP) for Valuez Account. This will be valid for 2 mins.',
                'format' => 'text',
                'v' => '1.1',
                'send_to' => '8826708801',
            ];
            $ch = curl_init($apiUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
            $response = curl_exec($ch);
            curl_close($ch);
            return $response;
    }
}
