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
        Schema::create('invitacions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('follow_id')->unique();
            $table->string('n_cotizacion');
            $table->string('link_clases')->nullable();
            $table->string('link_moodle')->nullable();
            $table->string('password')->nullable();
            $table->string('status')->nullable()->default('active');
            $table->string('author')->nullable();
            $table->datetime('date_execution')->nullable();
            $table->timestamps();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invitacions');
    }
};
