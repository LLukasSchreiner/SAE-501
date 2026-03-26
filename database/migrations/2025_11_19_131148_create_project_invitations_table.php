<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project_invitations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->foreignId('inviter_id')->constrained('users')->onDelete('cascade'); // Qui invite
            $table->foreignId('invitee_id')->constrained('users')->onDelete('cascade'); // Qui est invité
            $table->string('role')->default('member'); // owner, member, viewer
            $table->enum('status', ['pending', 'accepted', 'declined'])->default('pending');
            $table->string('token')->unique(); // Token unique pour le lien d'invitation
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_invitations');
    }
};