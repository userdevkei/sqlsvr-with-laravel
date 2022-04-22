<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\StudentUpdate;
use App\Models\Tempuser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Twilio\Rest\Client;
use Validator;



class StudentController extends Controller
{
    public function index(){

        $regstudents = DB::connection('mysql')->table('users')->orderBy('created_at', 'DESC')->paginate(10);

        return view('index')->with('regstudents', $regstudents);
    }

    public function storeDetails(Request $request){

    $oldstudent = DB::connection('sqlsrv')->table('tblstudents')
            ->where('RegStud_NO_PK', '=', $request->input('reg_number'))
            ->where('RegStud_Email', '=', $request->input('student_email'))
            ->select('regStud_Name1', 'regStud_Name2', 'regStud_Name3', 'RegStud_Phone', 'regStud_Gender')->first();

        if($oldstudent === null){
            return redirect('/')->with('error', 'Your Registration Number or Email Does Not Exist');
        }else {
            $newstudent = DB::connection('mysql')->table('users')
                ->where('regStudentNumber', '=', $request->input('reg_number'))
                ->where('regStudentEmail', '=', $request->input('student_email'))
                ->where('isVerified', '=', 1)
                ->first();



            if ($newstudent === null){

                $verifystudent = DB::connection('mysql')->table('users')
                    ->where('regStudentNumber', '=', $request->input('reg_number'))
                    ->where('regStudentEmail', '=', $request->input('student_email'))
                    ->where('isVerified', '=', 0)
                    ->first();

                if ($verifystudent === null){

                    $this->validate($request, [
                        'reg_number' => ['required', 'string'],
                        'student_email' => ['required', 'string'],
                        'student_phone' => 'required|regex:/(0)[0-9]{9}/',
                        'id_number' => 'required|numeric|max:59999999|digits_between:7,8|'
                    ]);

                    $student = new User;
                    $student->regStudentNumber = $request->input('reg_number');
                    $student->regStudentEmail = $request->input('student_email');
                    $student->regStudentName = $oldstudent->regStud_Name1 . " " . $oldstudent->regStud_Name2 . " " . $oldstudent->regStud_Name3;
                    $student->regStudentPhone = preg_replace('/0/', '+254', $request->input('student_phone'), 1);
                    $student->regStudentGender = $oldstudent->regStud_Gender;
                    $student->regStudentIDNO = $request->input('id_number');
                    $student->save();


                    $token = getenv("TWILIO_AUTH_TOKEN");
                    $twilio_sid = getenv("TWILIO_SID");
                    $twilio_verify_sid = getenv("TWILIO_VERIFY_SID");
                    $twilio = new Client($twilio_sid, $token);
                    $twilio->verify->v2->services($twilio_verify_sid)
                        ->verifications
                        ->create(preg_replace('/0/', '+254', $request['student_phone'], 1), "sms");

                    return redirect()->route('openVerify')->with(['success' => 'Please verify your phone number', 'student_phone' => preg_replace('/0/', '+254', $request['student_phone'], 1)]);
                }else{

                    $token = getenv("TWILIO_AUTH_TOKEN");
                    $twilio_sid = getenv("TWILIO_SID");
                    $twilio_verify_sid = getenv("TWILIO_VERIFY_SID");
                    $twilio = new Client($twilio_sid, $token);
                    $twilio->verify->v2->services($twilio_verify_sid)
                        ->verifications
                        ->create(preg_replace('/0/', '+254', $request['student_phone'], 1), "sms");

                    return view('re-verify')->with(['success' => 'OTP send successfully', 'student' => $verifystudent]);
                }

                } else{
                    return redirect('/')->with('success', 'Your account is already created and verified');
                }


//            if ($newstudent === null) {

//
//            }
//
//            $student = User::where('regStudentNumber', '=', $request->input('reg_number'))
//                    ->where('regStudentEmail', '=', $request->input('student_email'))
//                    ->where('isVerified', '=', 0)
//                    ->first();
//
//                if ($student === true) {
//

//                }
//            }
//            return redirect()->route('openVerify')->with(['success' => 'Verify your phone number', 'student_phone' => preg_replace('/0/', '+254', $request['student_phone'], 1)]);
        }
        }

    protected function verify(Request $request)
    {
        $this->validate($request, [
            'verification_code' => ['required', 'numeric'],
            'student_phone' => ['required', 'string'],
        ]);
        /* Get credentials from .env */
        $token = getenv("TWILIO_AUTH_TOKEN");
        $twilio_sid = getenv("TWILIO_SID");
        $twilio_verify_sid = getenv("TWILIO_VERIFY_SID");
        $twilio = new Client($twilio_sid, $token);
        $verification = $twilio->verify->v2->services($twilio_verify_sid)
            ->verificationChecks
            ->create($request['verification_code'], array('to' => preg_replace('/0/', '+254', $request['student_phone'], 1)));
        if ($verification->valid) {
            $student = tap(User::where('regStudentPhone', preg_replace('/0/', '+254', $request['student_phone'], 1)))->update(['isVerified' => true]);
            /* Authenticate user */
            return redirect('/')->with(['success' => 'Phone number verified']);
        }
        return back()->with(['student_phone' => $request['student_phone'], 'error' => 'Invalid verification code entered!']);
    }

    protected function requestOTP($id){

        $student = User::find($id);

        $token = getenv("TWILIO_AUTH_TOKEN");
        $twilio_sid = getenv("TWILIO_SID");
        $twilio_verify_sid = getenv("TWILIO_VERIFY_SID");
        $twilio = new Client($twilio_sid, $token);
        $twilio->verify->v2->services($twilio_verify_sid)
            ->verifications
            ->create($student->regStudentPhone, "sms");

        return view('re-verify')->with(['success' => 'OTP was send to your phone number', 'student' => $student]);
    }

    public function viewStudents (Request $request){
        $oldstudent = DB::connection('sqlsrv')->table('tblstudents')->orderBy('colID', 'DESC')
            ->where('studentsource', 'current')
            ->select('regStud_NO_PK','regStud_email','regStud_Name1', 'regStud_Name2', 'regStud_Name3', 'RegStud_Phone', 'regStud_Gender')
            ->get();

        return $oldstudent;
    }
}
