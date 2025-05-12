<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('service_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('question');
            $table->boolean('is_closed')->default(false);
            $table->timestamps();
        });
        
        Schema::create('service_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')->constrained('service_questions')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('answer');
            $table->timestamps();
        });
        
        Schema::create('question_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('question_id')->constrained('service_questions')->onDelete('cascade');
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('question_notifications');
        Schema::dropIfExists('service_answers');
        Schema::dropIfExists('service_questions');
    }
};