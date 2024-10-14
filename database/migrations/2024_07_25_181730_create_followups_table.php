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
        Schema::create('followups', function (Blueprint $table) {
            $table->id();
            $table->string('active');
            $table->string('name');
            $table->string('description')->nullable();
            $table->string('author');
            $table->string('referent')->nullable();
            $table->foreignId('event_id')->constrained('events');
            $table->foreignId('task_id')->constrained('tasks');
            $table->foreignId('customer_id')->nullable()->constrained('customers')->onDelete('set null');
            //////////Agenda
            $table->string('cod_sence_course')->nullable();
            $table->string('name_course')->nullable();
            $table->string('id_sence')->nullable();
            $table->string('modalily')->nullable();
            $table->json('week')->nullable();
            $table->string('doc_participant')->nullable();
            $table->string('h_star')->nullable();
            $table->string('h_end')->nullable();
            $table->string('f_star')->nullable();
            $table->string('f_end')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('followups');
    }
};
