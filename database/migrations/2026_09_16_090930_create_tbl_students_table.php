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
        Schema::create('tbl_students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('tbl_users')->nullOnDelete();
            $table->foreignId('class_id')->nullable()->constrained('tbl_classes')->nullOnDelete();
            $table->string('nis', 30)->unique();
            $table->string('name');
            $table->enum('gender', ['L', 'P']);
            $table->tinyInteger('archived')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_students');
    }
};
