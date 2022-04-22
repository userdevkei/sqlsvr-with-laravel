<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTempusersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql')->create('tempusers', function (Blueprint $table) {
            $table->id();
            $table->string('regStudentNumber');
            $table->string('regStudentName');
            $table->string('regStudentEmail');
            $table->string('regStudentPhone');
            $table->string('regStudentIDNO');
            $table->boolean('isVerified')->default(false);
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tempusers');
    }
}
