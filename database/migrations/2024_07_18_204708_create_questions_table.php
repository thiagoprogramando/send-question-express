<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subject_id')->nullable()->constrained('subjects')->onDelete('cascade');
            $table->foreignId('topic_id')->nullable()->constrained('topics')->onDelete('cascade');
            $table->longText('question_text')->nullable();
            $table->longText('comment_text')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('questions');
    }
};
