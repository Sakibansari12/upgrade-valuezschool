<?php

namespace App\Traits;
use App\Models\{School, SchoolPayment};
trait OtpVerifyTraits
{

    protected static function OtpSentMessage($studentPhoneNumber,$studentotp)
    {
            $apiUrl = 'https://enterprise.smsgupshup.com/GatewayAPI/rest';
            $postData = [
                'userid' => 2000215380,
                'password' => 'D5cgrl4y4',
                'method' => 'SendMessage',
                'msg' => $studentotp . ' is your one-time password (OTP) for Valuez Account. This will be valid for 2 mins.',
                'format' => 'text',
                'v' => '1.1',
                'send_to' => $studentPhoneNumber,
            ];
            $ch = curl_init($apiUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
            $response = curl_exec($ch);
            curl_close($ch);
            return $response;

    }

    public static function payment($school_id, $payment_id)
    {
      //  dd($school_id);
        $paymentdata = SchoolPayment::where('id', $payment_id)->first();
        $schooldata = School::where('id', $school_id)->first();
        
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
                "description" => $paymentdata->description,
                "customer" => array(
                    "name" => $schooldata->school_name,
                    "contact" => $paymentdata->phone_number,
                    "email" => $paymentdata->email,
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
        return $response;
        
    }



   /*  public static function paymentupdate($school_id, $payment_id)
    {
        $paymentdata = SchoolPayment::where('id', $payment_id)->first();
        $schooldata = School::where('id', $school_id)->first();
        
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.razorpay.com/v1/payment_links/{$paymentdata->payment_link_id}",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'PATCH',
            CURLOPT_POSTFIELDS => json_encode(array(
                "amount" => $paymentdata->payment_amount * 100,
                "currency" => "INR",
                "accept_partial" => true,
                "first_min_partial_amount" => 100,
                "expire_by" => 1897043600,
                "reference_id" => $paymentdata->orderid,
                "description" => $paymentdata->description,
                "customer" => array(
                    "name" => $schooldata->school_name,
                    "contact" => $paymentdata->phone_number,
                    "email" => $paymentdata->email,
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
        return $response;
        
    } */

    public static function paymentupdate($school_id, $payment_id)
{
     $paymentdata = SchoolPayment::where('id', $payment_id)->first();
    $schooldata = School::where('id', $school_id)->first();

    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.razorpay.com/v1/payment_links/{$paymentdata->payment_link_id}",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => 'PATCH',
        /* CURLOPT_POSTFIELDS => json_encode(array(
            "amount" => $paymentdata->payment_amount * 100,
            "description" => $paymentdata->description,
            "customer" => array(
                "name" => $schooldata->school_name,
                "contact" => $paymentdata->phone_number,
                "email" => $paymentdata->email,
            ),
        )), */
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Authorization: Basic cnpwX2xpdmVfbnZWTXJCd2cwa0pSSVQ6d2RCZ3MyQ1BIUk0yaFlDSnhyNm1YRkxk'
        ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    return $response;     
}

    public static function paymentdeactivate($school_id, $payment_id)
    {
            $paymentdata = SchoolPayment::where('id', $payment_id)->first();
           // dd($paymentdata->payment_link_id);
            $curl = curl_init();

            curl_setopt_array($curl, array(
            CURLOPT_URL =>"https://api.razorpay.com/v1/payment_links/{$paymentdata->payment_link_id}/cancel",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Basic cnpwX2xpdmVfbnZWTXJCd2cwa0pSSVQ6d2RCZ3MyQ1BIUk0yaFlDSnhyNm1YRkxk'
            ),
            ));
            $response = curl_exec($curl);

            curl_close($curl);
            return $response;

    }

}