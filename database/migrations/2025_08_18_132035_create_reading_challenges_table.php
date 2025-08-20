<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('reading_challenges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->integer('year');
            $table->integer('goal_count')->default(12);
            $table->integer('progress_count')->default(0);
            $table->timestamps();
            $table->unique(['user_id','year']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('reading_challenges');
    }
};
