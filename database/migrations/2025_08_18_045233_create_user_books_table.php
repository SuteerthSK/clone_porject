<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('user_books', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('book_id')->constrained()->onDelete('cascade');
            $table->string('shelf')->default('to-read'); // e.g. "to-read", "reading", "read"
            $table->timestamps();

            $table->primary(['user_id', 'book_id']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('user_books');
    }
};
