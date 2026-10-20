<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->id();

            // Кто выполнил действие (actor)
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();

            // Тип события: comment_created, reaction_added, user_followed,
            // project_approved, wiki_published, news_published
            $table->string('type', 40);

            // Полиморфный subject (News / UserProject / WikiPage / Comment / User)
            $table->string('subject_type', 100)->nullable();
            $table->unsignedBigInteger('subject_id')->nullable();

            // Доп. данные (excerpt, reaction type, счётчики и т.п.)
            $table->json('data')->nullable();

            $table->timestamp('created_at')->useCurrent();

            // Главный feed-query: активность по списку user_id DESC
            $table->index(['user_id', 'created_at'], 'activities_user_created_idx');
            // Публичная лента: все события DESC
            $table->index('created_at', 'activities_created_idx');
            // Проверка «есть ли активность по subject»
            $table->index(['subject_type', 'subject_id'], 'activities_subject_idx');
            // Не показывать дубли одного типа события по subject
            $table->index(['user_id', 'type', 'subject_type', 'subject_id'], 'activities_dedup_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
