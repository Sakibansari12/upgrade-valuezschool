<?php
namespace App\Imports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
class StudetsImport implements ToModel, WithHeadingRow
{

    public function model(array $row)
    {
        //
    }


    /* public function model(array $row)
    {
       
        $email = $row['email'];

        $existingStudent = Student::where('email', $email)->first();

        if ($existingStudent) {
            // Student with the same email already exists
            return null; // Skipping this record
        }
        $lowerGradeName = strtolower($row['grade']);

        // Map class_name values to corresponding IDs
        $masterClassNames = [
            'grade 1' => 1,
            'grade 2' => 2,
            'grade 3' => 3,
            'grade 4' => 4,
            'grade 5' => 5,
            'pre school' => 6,
            'grade 6-10' => 7,
        ];

        if (isset($masterClassNames[$lowerGradeName])) {
            $masterClassId = $masterClassNames[$lowerGradeName];

            $student = new Student([
                'name' => $row['name'],
                'email' => $row['email'],
                'phone' => $row['phone'],
            ]);

            // Set the 'grade' field with the mapped master class ID
            $student->grade = $masterClassId;

            return $student;
        } else {
            // If gradeName is not found in the mapping array, skip the row
            return null;
        }
    } */


    /* public function model(array $request)
    { 
       dd($request->all());
       $existingStudent = Student::where('phone_number', $request['phone_number'])->first();
       $validator = Validator::make($request->all(), [
        'grade' => 'required',
        'name' => 'required',
        'student_status' => 'required',
        'phone_number' => 'nullable|unique:students',
        'email' => 'nullable|email',
        //'school' => 'required|exists:school,id',
    ]);

    if($validator->passes()) {
         if(!empty($request->phone_number)){
             $username = substr($request->name, 0, 3) . substr($request->phone_number,  -3);
         }else{
             $user_name = Str::random(3);
             $username = substr($request->name, 0, 3) . substr($user_name,  -3);
         }
         $passwordgenrate = Str::random(10);
         $StudentCreate = new Student;
         $StudentCreate->grade = $request->grade;
         $StudentCreate->name = $request->name;
         $StudentCreate->student_status = $request->student_status;
         $StudentCreate->phone_number = $request->phone_number;
         $StudentCreate->email = $request->email;
         //$StudentCreate->school_id = $request->school;
         $StudentCreate->username = $username;
         $StudentCreate->status = 1;
         $StudentCreate->password = $passwordgenrate;
         $StudentCreate->confirm_password = $passwordgenrate;
         $StudentCreate->view_password = $passwordgenrate;
         $StudentCreate->save();
         return response()->json([
             'status' => true,
             'message' => "Create Student successfully",
         ]);
         }else{
             return response()->json([
                 'status' => false,
                 'errors' => $validator->errors(),
                 ]);
        }
    } */
}

