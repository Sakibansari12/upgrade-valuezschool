<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NotificationModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use DataTables;
use Illuminate\Support\Carbon;
use DB;
use Illuminate\Support\Facades\Validator;
use App\Models\{LogsModel, TermsPrivacy,Reminder,Invoice};


class InvoiceController extends Controller
{
    /* Invoice */
    public function indexInvoice(Request $request)
    {
        if ($request->ajax()) {
            $data = Invoice::query()->orderBy("id", "DESC");
                return Datatables::of($data)
                ->addColumn('index', function ($row) {
                    static $index = 0;
                    return ++$index;
                })
                ->editColumn('invoice_number', function ($row) {
                    return $row->invoice_number ?? '';
                })
                ->editColumn('invoice_date', function ($row) {
                    return $row->invoice_date ?? '';
                })
                ->editColumn('hsn_code', function ($row) {
                    return $row->hsn_code ?? '';
                })
                ->editColumn('cgst', function ($row) {
                    return isset($row->cgst) ? $row->cgst . '%' : '';
                })
                
                ->editColumn('sgst', function ($row) {
                    return isset($row->sgst) ? $row->sgst . '%' : '';
                })
                ->editColumn('igst', function ($row) {
                    return isset($row->igst) ? $row->igst . '%' : '';
                })
                ->editColumn('address', function ($row) {
                    return $row->address ?? '';
                })
                ->editColumn('description', function ($row) {
                    $strippedDescription = strip_tags($row->description); // Remove HTML tags
                    $formattedDescription = strlen($strippedDescription) > 10 ? substr($strippedDescription, 0, 10) . '...' : $strippedDescription;
                    return $formattedDescription;
                })
                
                ->editColumn('created_at', function ($row) {
                    $formattedDate = date('d/m/Y', strtotime($row->created_at));
                    $formattedTime = date('H:i', strtotime($row->created_at));
                    return "$formattedDate | $formattedTime";
                })

                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" data-id=' . $row->id . ' class="edit btn btn-danger btn-sm remove_school_data">Delete</a>';
                    return $btn;
                })


                ->addColumn('action', function ($row) {
                    $editLink = '<a href="' . route('invoice.edit', ['id' => $row->id]) . '"
                                    class="waves-effect waves-light btn btn-sm btn-outline btn-info mb-5">Edit</a>';
                    $deleteLink = '<a href="javascript:void(0);" data-id="' . $row->id . '"
                                    class="waves-effect waves-light remove_school_data btn btn-sm btn-outline btn-danger mb-5">Delete</a>';

                    //$deleteLink = '<a href="javascript:void(0)" data-id=' . $row->id . ' class="edit btn btn-danger btn-sm remove_school_data">Delete</a>';

                    return $editLink . ' ' . $deleteLink;
                })



                ->rawColumns(['action'])
                ->make(true); 
        }
        return view('invoice.index');
    }

    public function addInvoice(Request $request)
    {
        return view('invoice.create');
    }
    public function EditInvoice(Request $request)
    {
        $data = Invoice::select('*')->where('id',$request->id)->first();
        return view('invoice.edit', compact('data'));
    }

    public function invoiceCreate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'invoice_number' => 'required',
            'invoice_date' => 'required',
            'hsn_code' => 'required',
            'cgst' => 'required',
            'sgst' => 'required',
            'igst' => 'required',
            'address' => 'required',
            'description' => 'required',
        ]);
        if($validator->passes()){
            $Invoicecreate = new Invoice();
            $Invoicecreate->invoice_number = $request->invoice_number;
            $Invoicecreate->invoice_date = $request->invoice_date;
            $Invoicecreate->hsn_code = $request->hsn_code;
            $Invoicecreate->cgst = $request->cgst;
            $Invoicecreate->sgst = $request->sgst;
            $Invoicecreate->igst = $request->igst;
            $Invoicecreate->address = isset($request->address) ? $request->address : '';
            $Invoicecreate->description = isset($request->description) ? $request->description : '';
            $Invoicecreate->status = 1;
            $Invoicecreate->save();
        return response()->json([
            'status' => true,
            'message' => "Invoice create successfully!",
        ], 201);

        }else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
             ]);
        
         }
    }


    public function updateInvoice(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'invoice_number' => 'required',
            'invoice_date' => 'required',
            'hsn_code' => 'required',
            'cgst' => 'required',
            'sgst' => 'required',
            'igst' => 'required',
            'address' => 'required',
            'description' => 'required',
        ]);
        if($validator->passes()){
            //$updateInvoice = new Invoice();
            $Invoice_update = Invoice::findOrFail($request->invoice_id);

            $Invoice_update->invoice_number = $request->invoice_number;
            $Invoice_update->invoice_date = $request->invoice_date;
            $Invoice_update->hsn_code = $request->hsn_code;
            $Invoice_update->cgst = $request->cgst;
            $Invoice_update->sgst = $request->sgst;
            $Invoice_update->igst = $request->igst;
            $Invoice_update->address = isset($request->address) ? $request->address : '';
            $Invoice_update->description = isset($request->description) ? $request->description : '';
            $Invoice_update->status = 1;
            $Invoice_update->save();
        return response()->json([
            'status' => true,
            'message' => "Invoice update successfully!",
        ], 201);

        }else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
             ]);
        
         }
    }

    public function VerifyInvoice(Request $request)
    {
        $userPass = $request->input('userpass');
        if (Auth::check()) {
            $user = Auth::user();
             if (Hash::check($userPass, $user->password)) {
                return response()->json(['success' => true, 'msg' => 'Account Verify successfully!']);
            } else {
                return response()->json(['success' => false, 'msg' => 'Entered Password Incorrect.']);
            }
        } else {
            return response()->json(['success' => false, 'msg' => 'Somenthing Went Wrong!']);
        }
    }

    public function destroyInvoice(Request $request)
    {
        $reminder_id = $request->reminder_id;
        $user = Auth::user();
        if (($user) && $user->usertype == "superadmin") {
            Invoice::where('id', $reminder_id)->delete();
            echo "removed";
        } else {
            echo "Something went wrong.";
        }
    }
}
