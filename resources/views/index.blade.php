    <style>
            body{
                margin: 0 auto;
                padding: 7px;
            }
            .form-container{
                margin: 2% auto;
                width: 30vw;
                height: 38vh;
                box-shadow: green;
            }
            .table-section{
                margin: auto;
                width: 94%;
            }
            th{
                text-align:  !important;
            }
        </style>
    @extends('layouts.app')
    @section('content')
        <div class="form-container">
            {!! Form::open(['action' => 'App\Http\Controllers\StudentController@storeDetails', 'method' => 'POST']) !!}

            <div class="form-group row">
                    {!! Form::label('REG_NO', 'REG. NUMBER', ['class' => 'col-sm-4']) !!}
                <div class="col-sm-8">
{{--                    <select name="reg_number" id="reg_number" class="form-control">--}}
{{--                        @foreach($students as $student)--}}
{{--                            <option>-- Select Registration -- </option>--}}
{{--                            <option value="{{ $student->RegStud_No_PK }}">{{ $student->RegStud_No_PK }}</option>--}}
{{--                        @endforeach--}}
{{--                    </select>--}}
                    {!! Form::text('reg_number', '', ['class' => 'form-control','placeholder' => 'Select Registration', 'id' => '']) !!}
                </div>
            </div>

            <div class="form-group row">
                {!! Form::label('Student email', 'STUDENT EMAIL', ['class' => 'col-sm-4']) !!}
                <div class="col-sm-8">
                    {!! Form::email('student_email', '', ['class' => 'form-control','placeholder' => 'Student Email']) !!}
                </div>
            </div>

{{--            <div class="form-group row">--}}
{{--                {!! Form::label('Student name', 'STUDENT NAME', ['class' => 'col-sm-4']) !!}--}}
{{--                <div class="col-sm-8">--}}
{{--                    {!! Form::text('student_name', '', ['class' => 'form-control','placeholder' => 'Student Name']) !!}--}}
{{--                </div>--}}
{{--            </div>--}}

            <div class="form-group row">
                {!! Form::label('Student ID', 'STUDENT IDNO', ['class' => 'col-sm-4']) !!}
                <div class="col-sm-8">
                    {!! Form::text('id_number', '', ['class' => 'form-control','placeholder' => 'Student National ID']
                        ) !!}
                </div>
            </div>

            <div class="form-group row">
                {!! Form::label('Student phone', 'STUDENT PHONE', ['class' => 'col-sm-4']) !!}
                <div class="col-sm-8">
                    {!! Form::text('student_phone', '', ['class' => 'form-control','placeholder' => 'Student Phone Number']) !!}
                </div>
            </div>

            <div class="form-group d-flex justify-content-sm-center">
                {!! Form::submit('Save Details', ['class' => 'btn btn-success']) !!}
            </div>

            {!! Form::close() !!}
        </div>
    <div class="">

    </div>
    <div class="table-section">

    <div class="table table-responsive">
        <table class="table table-responsive-sm table-borderless table-striped">
            <thead>
            <tr>
                <th colspan="1">Registration number</th>
                <th colspan="1">Student Name</th>
                <th colspan="1">Gender</th>
                <th colspan="1">Student Email</th>
                <th colspan="1">Phone Number</th>
                <th colspan="1">National ID</th>
                <th colspan="1">Status</th>
                <th colspan="2">Action</th>
            </tr>
            </thead>
            <tbody>
                @foreach($regstudents as $regstudent)
                    <tr>
                        <td colspan="">{{ $regstudent->regStudentNumber }}</td>
                        <td colspan="">{{ $regstudent->regStudentName }}</td>
                        <td colspan="">{{ $regstudent->regStudentGender }}</td>
                        <td colspan="">{{ $regstudent->regStudentEmail }}</td>
                        <td colspan="">{{ $regstudent->regStudentPhone }}</td>
                        <td colspan="">{{ $regstudent->regStudentIDNO }}</td>
                        <td colspan="">
                            @if($regstudent->isVerified === 1)
                                <span class="badge badge-success">verified</span>
                            @else
                                <a class="btn btn-sm btn-info" href="{{ route('requestOTP', $regstudent->id) }}">verify</a>
                            @endif
                        </td>
                        <td><a class="btn btn-sm btn-info" href="#"> edit </a> </td>
                        <td><a class="btn btn-sm btn-danger" href="#"> delete </a> </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
        <div class="d-flex justify-content-sm-center">
            {{ $regstudents->links() }}
        </div>
    </div>
    @endsection
