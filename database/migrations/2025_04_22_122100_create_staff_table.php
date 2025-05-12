<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  
    public function up(): void
    {
        Schema::create('staff', function (Blueprint $table) {
            $table->id();
            $table->string('first_name', 50);
            $table->string('last_name', 50); 
            $table->string('position', 50); 
            $table->string('image', 255)->nullable(); 
            $table->string('login', 50)->unique();
            $table->string('password', 255); 
            $table->foreignId('branch_id')->nullable()->constrained()->onDelete('set null');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('staff');
    }
};
