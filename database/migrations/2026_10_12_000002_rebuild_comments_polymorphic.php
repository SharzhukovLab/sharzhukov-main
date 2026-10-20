<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();

        // Legacy-таблица реакций на комментарии (заменяется полиморфной reactions позже)
        Schema::dropIfExists('comment_reactions');
        Schema::dropIfExists('comments');

        Schema::enableForeignKeyConstraints();

        Schema::create('comments', function (Blueprint $table) {
            $table->id();

            // Полиморф: News / UserProject / WikiPage
            $table->string('commentable_type', 100);
            $table->unsignedBigInteger('commentable_id');

            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('comments')->cascadeOnDelete();

            $table->text('body');
            $table->enum('status', ['pending', 'approved', 'rejected', 'spam'])->default('pending');

            $table->text('moderation_note')->nullable();
            $table->foreignId('moderated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('moderated_at')->nullable();

            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent', 255)->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Основной list-query
            $table->index(['commentable_type', 'commentable_id', 'status', 'created_at'], 'comments_subject_list_idx');
            $table->index(['user_id', 'created_at'], 'comments_user_idx');
            $table->index('parent_id', 'comments_parent_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
