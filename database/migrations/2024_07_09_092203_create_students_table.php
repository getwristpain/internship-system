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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('id_number')->nullable();
            $table->string('class')->nullable();
            $table->string('school_year')->nullable();
            $table->string('avatar')->nullable();
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('birth_place')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('gender')->nullable();
            $table->string('blood_type')->nullable();
            $table->string('parent_name')->nullable();
            $table->string('parent_address')->nullable();
            $table->string('parent_phone')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
