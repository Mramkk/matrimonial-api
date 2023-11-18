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
        Schema::create('imgs', function (Blueprint $table) {
            $table->id();
            $table->string('uid');
            $table->integer('img_id')->uniqid();
            $table->string('type');
            $table->string('image');
            $table->enum('active', [0, 1])->default(0);
            $table->enum('hidden', [0, 1])->default(0);
            $table->enum('visible', [0, 1])->default(1);
            $table->enum('visible_to_paid', [0, 1])->default(0);
            $table->enum('visible_to_accept', [0, 1])->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('imgs');
    }
};
