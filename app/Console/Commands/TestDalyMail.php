<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Mail;
class TestDalyMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Testing Email';

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
        $details = [
            'view' => 'emails.test-email',
            'subject' => 'Your Account Email Testing - Valuez',
            'title' => 'Email Testing',
            'email' => 'support@valuezschool.com',
            'pass' => 'test123',
        ];
        Mail::to('support@valuezschool.com')->send(new \App\Mail\TestMail($details));
    }
}
