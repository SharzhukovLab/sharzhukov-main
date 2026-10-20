<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('reactions', function (Blueprint $table) {
            $table->id();

            // Полиморф: News / UserProject / WikiPage
            $table->string('reactable_type', 100);
            $table->unsignedBigInteger('reactable_id');

            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();

            // like | love | fire | rocket
            $table->string('type', 16);

            $table->timestamps();

            // Один юзер → одна реакция на объект (смена типа = update)
            $table->unique(['user_id', 'reactable_type', 'reactable_id'], 'reactions_user_subject_unique');

            // Подсчёт по объекту: SELECT type, COUNT(*) GROUP BY type
            $table->index(['reactable_type', 'reactable_id', 'type'], 'reactions_subject_type_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reactions');
    }
};
