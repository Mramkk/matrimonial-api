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
            $table->string('uid')->unique();
            $table->string('ref_id')->nullable();
            $table->string('profile_for');
            $table->string('first_name');
            $table->string('last_name');
            $table->date('dob');
            $table->integer('age');
            $table->enum('gender', ['Male', 'Female']);
            $table->string('phone')->nullable();
            $table->string('email')->unique();
            $table->string('password')->nullable();
            $table->string('religion')->nullable();
            $table->string('community')->nullable();
            $table->string('sub_community')->nullable();
            $table->string('mother_tounge')->nullable();
            $table->string('country')->nullable();
            $table->string('state')->nullable();
            $table->string('city')->nullable();
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
            $table->string('email_verification_code')->nullable();
            $table->enum('email_verified', [0, 1])->default(0);
            $table->enum('status', [0, 1])->default(0);
            $table->enum('membership', [0, 1])->default(0);
            $table->enum('photo', [0, 1])->default(0);
            $table->enum('document', [0, 1])->default(0);
            $table->enum('completed', [0, 1])->default(0);
            $table->enum('online', [0, 1])->default(0);
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
