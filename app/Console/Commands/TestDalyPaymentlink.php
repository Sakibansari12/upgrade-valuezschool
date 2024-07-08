<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Mail;
use App\Models\{TestPayments, AppSetting};
class TestDalyPaymentlink extends Command
{
   
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:paymentlink';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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

      $schoolpayment = new TestPayments;
      $schoolpayment->orderid = $this->generateUniquetestId(new TestPayments, 4);
      $schoolpayment->payment_amount = 1;
     // $schoolpayment->email = 'support@valuezschool.com';
      $schoolpayment->phone_number = '8826708801';
      $schoolpayment->save();
      $paymentdata = TestPayments::where('id', $schoolpayment->id)->first();

      /* otp */
      
      $apiUrl = 'https://enterprise.smsgupshup.com/GatewayAPI/rest';
      $otpnumber = random_int(1000, 9999);
      $phone_number = '8826708801';
      $postData = [
          'userid' => 2000215380,
          'password' => 'D5cgrl4y4',
          'method' => 'SendMessage',
          'msg' => $otpnumber . ' is your one-time password (OTP) for Valuez Account. This will be valid for 2 mins.',
          'format' => 'text',
          'v' => '1.1',
          'send_to' => $phone_number,
      ];
      $ch = curl_init($apiUrl);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
      $response = curl_exec($ch);
      if (strpos($response, 'success') !== false) {
            $paymentdata->update([
                'otp' => $otpnumber,
                'phone_number' => '8826708801',
            ]);
      }
      curl_close($ch);
    /* Mail */
    $details = [
        'view' => 'emails.test-email',
        'subject' => 'Your Account Email Testing - Valuez',
        'title' => 'Email Testing',
        'email' => 'support@valuezschool.com',
        'pass' => 'test123',
    ];
    Mail::to('support@valuezschool.com')->send(new \App\Mail\TestMail($details));
    $paymentdata->update([
        'email' => 'support@valuezschool.com',
    ]);

      $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.razorpay.com/v1/payment_links',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode(array(
                "amount" => $paymentdata->payment_amount * 100,
                "currency" => "INR",
                "accept_partial" => true,
                "first_min_partial_amount" => 100,
                "expire_by" => 1897043600,
                "reference_id" => $paymentdata->orderid,
                "description" => 'Create Testing Payment Link',
                "customer" => array(
                    "name" => 'Create Testing Payment Link',
                    "contact" => $paymentdata->phone_number,
                    "email" => 'support@valuezschool.com',
                ),
                "notify" => array(
                    "sms" => true,
                    "email" => true
                ),
                "reminder_enable" => true,
                "notes" => array(
                    "policy_name" => "Jeevan Bima"
                ),
                "callback_url" => "https://example-callback-url.com/",
                "callback_method" => "get"
            )),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Basic cnpwX2xpdmVfbnZWTXJCd2cwa0pSSVQ6d2RCZ3MyQ1BIUk0yaFlDSnhyNm1YRkxk'
               
            ),
        ));
        
        $response = curl_exec($curl);
        
        curl_close($curl);

        $shortUrls = collect($response)
        ->map(function ($response) {
            $data = json_decode($response, true);
            $shortUrl = $data['short_url'] ?? null;
            $id = $data['id'] ?? null; 
            return compact('shortUrl', 'id');
        })
        ->filter()
        ->toArray();
    if(!empty($shortUrls[0])){
        $paymentdata = TestPayments::where('id', $schoolpayment->id)->first();
        $paymentdata->update([
            'payment_url' => $shortUrls[0]['shortUrl'],
            'payment_link_id' => $shortUrls[0]['id'],
            'payment_status' => 1,
            'link_created_at' => now()->format('Y-m-d H:i:s'),
        ]);
    }

/* chat-gpt */
    $apiKey = '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';
    $model = 'gpt-3.5-turbo';
    $response = Http::withHeaders([
        'Content-Type' => 'application/json',
        'Authorization' => 'Bearer ' . $apiKey,
    ])->post('https://api.openai.com/v1/chat/completions', [
        'model' => $model,
        'messages' => [
            [
                'role' => 'system',
                'content' => 'You are a helpful assistant.',
            ],
            [
                'role' => 'user',
                'content' => 'Explain the rules of cricket to 8 year old.',
            ],
        ],
    ]);
    if ($response->successful()) {
        $result = $response->json();
       // $completion = $result['choices'][0]['message']['content'];
        $paymentdata = TestPayments::where('id', $schoolpayment->id)->first();
        $paymentdata->update([
            'chat_gpt_status' => 1,
        ]);
    }
    /* dally */
            $apiKey = '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';
            $model = 'dall-e-2';
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $apiKey,
            ])->post('https://api.openai.com/v1/images/generations', [
                "model" => $model,
                "prompt"=> 'A cute cat looking at herself in mirror near the beach on a sunny day',
                "n"=> 1,
                "size"=> "512x512"
                
            ]);
            // dd($response);
            if ($response->successful()) {
               // $result = $response->json();
               // $completion = $result['data'][0]['url'];
               $paymentdata = TestPayments::where('id', $schoolpayment->id)->first();
               $paymentdata->update([
                'dally_status' => 1,
               ]);
                
            }

    }

    private function generateUniquetestId(Model $target, int $type)
    {
      
        $settingArr = AppSetting::where('type', $type)->get()->toArray();

        $singleArray = array();

        foreach ($settingArr as $setting) {
            $singleArray[$setting['field']] = $setting['value'];
        }

        $status = true;
        $prefix = (string)$singleArray['prefix_string'];
        $number = (int)$singleArray['next_number'];
        $perfixLen = strlen($prefix);
        $padLen = (int)$singleArray['number_length'];

        if ($padLen > $perfixLen) {
            $padLen = $padLen - $perfixLen;
        } else {
            $prefix = substr($prefix, 0, ($padLen - 2));
            $perfixLen = strlen($prefix);
            if ($padLen > $perfixLen) {
                $padLen = $padLen - $perfixLen;
            }
        }

        $barcode = strtoupper(Str::random(10));

        while ($status === true) {
            $barcode = (string)$prefix . str_pad($number, $padLen, '0', STR_PAD_LEFT);

            $dataArr = $target::where('orderid', $barcode)->exists();

            if (!$dataArr) {
                $status = false;
                AppSetting::where('type', $type)
                    ->where('field', 'next_number')
                    ->update(['value' => $number + 1]);
            } else {
                ++$number;
            }
        }

        return $barcode;
    }
}
