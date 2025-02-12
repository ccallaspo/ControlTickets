<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('log_invitacions', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('count')->nullable();
            $table->json('emails')->nullable();
            $table->string('status')->nullable();
            $table->foreignId('invitacion_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_invitacions');
    }
};
