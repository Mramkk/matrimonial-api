<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('uid');
            $table->string('bhid')->unique();
            $table->string('created_by');
            $table->string('first_name');
            $table->string('last_name');
            $table->date('dob');
            $table->date('age');
            $table->enum('gender', ['Male', 'Female'])->nullable();
            $table->string('phone');
            $table->string('email')->unique();
            $table->string('religion');
            $table->string('caste');
            $table->string('mother_tounge');
            $table->string('country');
            $table->string('state');
            $table->string('city');
            $table->string('marrital_status')->nullable();
            $table->string('subcaste')->nullable();
            $table->string('marry_other_caste')->nullable();
            $table->string('height')->nullable();
            $table->string('weight')->nullable();
            $table->string('body_type')->nullable();
            $table->string('complexion')->nullable();
            $table->string('physical_status')->nullable();
            $table->string('highest_education')->nullable();
            $table->string('additional_degree')->nullable();
            $table->string('occupation')->nullable();
            $table->string('employed_in')->nullable();
            $table->string('annual_income')->nullable();
            $table->string('diet')->nullable();
            $table->string('smoking')->nullable();
            $table->string('drinking')->nullable();
            $table->string('have_dosh')->nullable();
            $table->string('star')->nullable();
            $table->string('rasi')->nullable();
            $table->string('birth_time')->nullable();
            $table->string('birth_place')->nullable();
            $table->string('family_status')->nullable();
            $table->string('family_type')->nullable();
            $table->string('family_values')->nullable();
            $table->string('father_occupation')->nullable();
            $table->string('mother_occupation')->nullable();
            $table->string('no_brothers')->nullable();
            $table->string('no_married_brothers')->nullable();
            $table->string('no_sisters')->nullable();
            $table->string('no_married_sisters')->nullable();
            $table->string('about_me')->nullable();
            $table->string('id_proof')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->enum('status', [0, 1])->default(1);
            $table->enum('complete', [0, 1])->default(1);
            $table->string('otp')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
