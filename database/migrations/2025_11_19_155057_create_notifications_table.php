<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Destinataire
            $table->string('type'); // 'task_assigned', 'task_updated', 'deadline_soon', 'invitation', etc.
            $table->string('title'); // Titre de la notification
            $table->text('message'); // Message
            $table->string('action_url')->nullable(); // URL vers la ressource
            $table->foreignId('related_id')->nullable(); // ID de la tâche/projet concerné
            $table->string('related_type')->nullable(); // Type (Task, Project, etc.)
            $table->boolean('read')->default(false); // Lu ou non
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};