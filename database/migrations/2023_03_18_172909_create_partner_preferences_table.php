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
        Schema::create('partner_preferences', function (Blueprint $table) {
            $table->id();
            $table->string('uid');
            $table->string('age')->nullable();
            $table->string('religion')->nullable();
            $table->string('caste')->nullable();
            $table->string('mother_tounge')->nullable();
            $table->string('country')->nullable();
            $table->string('state')->nullable();
            $table->string('city')->nullable();
            $table->string('marrital_status')->nullable();
            $table->string('height')->nullable();
            $table->string('physical_status')->nullable();
            $table->string('highest_education')->nullable();
            $table->string('occupation')->nullable();
            $table->string('annual_income')->nullable();
            $table->string('diet')->nullable();
            $table->string('smoking')->nullable();
            $table->string('drinking')->nullable();
            $table->string('have_dosh')->nullable();
            $table->string('dosh_type')->nullable();
            $table->string('star')->nullable();
            $table->string('expectation')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partner_preferences');
    }
};
