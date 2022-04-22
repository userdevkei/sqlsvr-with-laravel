<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tempuser extends Model
{
    use HasFactory;

    protected $connection = 'mysql';



    protected $fillable = [
        'regStudentName', 'regStudentGender', 'regStudentNumber', 'regStudentEmail', 'regStudentPhone', 'regStudentIDNO', 'password', 'isVerified',
    ];
}
