<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('caste_id');
            $table->foreign('caste_id')->references('id')->on('castes');

            $table->unsignedBigInteger('school_id');
            $table->foreign('school_id')->references('id')->on('schools');

            $table->bigInteger('code')->unique();
            $table->string('board_code')->unique();
            $table->string('name');
            $table->string('profile')->nullable();
            $table->string('aadhaar')->nullable();
            $table->date('dob');
            $table->enum('gender', ['male', 'female', 'transgender']);
            $table->string('address_line_1');
            $table->string('address_line_2');
            $table->string('address_line_3');
            $table->string('pin_code');
            $table->enum('status', ['studying', 'tc', 'absconded', 'restigated']);
            $table->string('status_reason')->nullable();
            $table->boolean('isactive')->default(1);
            

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('students');
    }
}