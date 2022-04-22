<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentUpdate extends Model
{
    use HasFactory;

    protected $connections = 'sqlsrv';

    protected $table = 'tblSTUDENTS';

//    protected $table = 'users';

    protected $fillable = [
        'regStudentName', 'regStudentEmail', 'regStudentIDNO', 'regStudentPhone', 'regStudentNumber', 'regStudentGender'
    ];


}
