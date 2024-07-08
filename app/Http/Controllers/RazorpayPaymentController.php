<?php

namespace App\Http\Controllers;

use App\Models\{School, SchoolPayment, ClassroomSubscription};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use DataTables;
use Illuminate\Support\Facades\Validator;
use Mail;
use Illuminate\Support\Carbon;
use Razorpay\Api\Api;
use Session;
use Exception;
use GuzzleHttp\Client;
use App\Traits\OtpVerifyTraits;
use Razorpay\Api\Errors\SignatureVerificationError;

class RazorpayPaymentController extends Controller
{

    use OtpVerifyTraits;

    public function SchoolPayment(Request $request){
        $school_id = $request->input('school_id');
         $schoodata = School::where('id', $school_id)->first();
         return view('school.school-payment', compact('school_id', 'schoodata'));
    }

    public function SchoolPaymentPay(Request $request){
    
       $validator = Validator::make($request->all(),[
         'price' => 'required',
         'description' => 'required',
         'school_id' => 'required|exists:school,id',
         'email' => 'nullable',
        // 'milestone' => 'nullable|array',
        // 'milestone.*' => 'nullable'
       ]);
       
       if($validator->passes()){
          $schoolpayment = new SchoolPayment;
          $schoolpayment->barcode = $this->generateUniqueBarcode(new SchoolPayment, 1);
          $schoolpayment->price = $request->price;
          $schoolpayment->description = $request->description;
          $schoolpayment->school_id = $request->school_id;
          $schoolpayment->email = isset($request->email) ? $request->email : '';
          $schoolpayment->payment_date = Carbon::now()->addSeconds(60)->format('Y-m-d H:i:s');
          $schoolpayment->save();
            $payment_request = $this->payment($schoolpayment->school_id, $schoolpayment->id);
            $responseData = json_decode($payment_request, true);
            
           if(!empty($responseData['short_url'])){
                $schoolpayment->update([
                    'payment_status' => 1,
                    'payment_url' => $responseData['short_url'],
                ]);
                return response()->json([
                    'status' => true,
                    'message' => "School Payment successfully",
                ]);
           }else{
                return response()->json([
                    'status' => false,
                    'message' => "No Payment successfully",
                ]); 
           }

                
        }else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
                ]);
            }
    }

        public function store(Request $request)
        {
            $school_payment_data = SchoolPayment::find($request->school_payment_id);
            if (!$school_payment_data) {
                return response()->json(['error' => 'School payment not found'], 404);
            }
            $school_payment_data->update([
                'payment_status' => 1,
                'payment_link_id' => $request->razorpay_payment_id,
            ]);


            $keyId = 'rzp_live_nvVMrBwg0kJRIT';
            $keySecret = 'wdBgs2CPHRM2hYCJxr6mXFLd';
            $api = new Api($keyId, $keySecret);
            $paymentId = $school_payment_data->payment_link_id; 
            $payment = $api->payment->fetch($paymentId);

            $school_payment_data->update([
                'upi_id' => $payment->vpa,
                'payment_sucessfull_time' => date('Y-m-d H:i:s', $payment->created_at),
            ]);
            $schooldata = School::where('id', $school_payment_data->school_id)->first();
             $total_licence = $schooldata->licence + $school_payment_data->number_of_subscription;

            $schooldata->update(['licence' => $total_licence]);

            return response()->json(['success' => 'Payment successful']);
        }

        public function srStore(Request $request)
        {
            $subscriptionPaymentData = SchoolPayment::find($request->school_payment_id);

            if ($subscriptionPaymentData) {
                $paymentFailedInfo = [
                    'payment_failed' => $request->failed_data,
                    'amount' => $request->amount,
                ];
        
                // Convert the array to JSON before updating the database
                $subscriptionPaymentData->update([
                    'payment_failed_info' => json_encode($paymentFailedInfo)
                ]);
        
                return response()->json(['success' => 'Payment failed']);
            }


        }
}