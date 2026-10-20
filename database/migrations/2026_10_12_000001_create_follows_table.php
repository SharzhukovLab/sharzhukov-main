<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('follows', function (Blueprint $table) {
            $table->id();
            $table->foreignId('follower_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('followee_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['follower_id', 'followee_id'], 'follows_pair_unique');
            $table->index('followee_id', 'follows_followee_idx');
            $table->index('follower_id', 'follows_follower_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('follows');
    }
};
