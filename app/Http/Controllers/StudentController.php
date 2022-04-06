<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\StudentUpdate;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Validator;


class StudentController extends Controller
{
    public function index(){

        $regstudents = User::paginate(5);

        $students = DB::table('tblSTUDENTS')
            ->where('STUDENTSOURCE','CURRENT')
            ->where('RegStud_Email', 'like', '%@students.tum.ac.ke%')
            ->select('colID','RegStud_Email', 'RegStud_No_PK', 'RegStud_phone', 'RegStud_IDNO', 'regStud_Name1', 'regStud_Name2',
                'regStud_Name3', 'regStud_Gender')
            ->get();
//        return $students;

        return view('index')->with(['students' => $students, 'regstudents' => $regstudents]);

//        return $students;
    }

    public function storeDetails(Request $request){
        $oldstudent = DB::table('tblstudents')
            ->where('RegStud_NO_PK', '=', $request->input('reg_number'))
            ->where('RegStud_Email', '=', $request->input('student_email'))
            ->select('regStud_Name1', 'regStud_Name2', 'regStud_Name3', 'RegStud_Phone', 'regStud_Gender')->first();

        if($oldstudent === null){
            return redirect('/')->with('error', 'Your Registration Number or Email does not exist');
        }else{
//            return $student;

            $this->validate($request, [
                'reg_number' => ['required', 'string'],
                'student_email' => ['required', 'string'],
//                'student_name' => ['required', 'string'],
//                'student_phone' => 'required|regex:/(0)[0-9]{9}/',
                'id_number' => 'required|numeric|max:59999999|digits_between:7,8|'
            ]);

//            return $oldstudent->regStud_Name1. " " .$oldstudent->regStud_Name2. " ". $oldstudent->regStud_Name3;

                $student = new User;
                $student->regStudentNumber = $request->input('reg_number');
                $student->regStudentEmail = $request->input('student_email');
                $student->regStudentName = $oldstudent->regStud_Name1. " " .$oldstudent->regStud_Name2. " ". $oldstudent->regStud_Name3;;
//                $student->regStudentPhone = $request->input('student_phone');
                $student->regStudentPhone = $request->input('student_phone');
                $student->regStudentGender = $oldstudent->regStud_Gender;
                $student->regStudentIDNO = $request->input('id_number');
                $student->save();

//            return User::create([
//                'regStudentNumber' => $request['reg_number'],
//                'regStudentEmail' => $request['student_email'],
//                'regStudentName' => $request['student_name'],
//                'regStudentPhone' => $request['student_phone'],
//                'regStudentIDNO' => $request['id_number'],
//
//            ]);


            return redirect('/')->with('success', 'Your student details updated successfully');
        }
    }
}
