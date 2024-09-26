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
        Schema::create('cotizacions', function (Blueprint $table) {
            $table->id();
            $table->string('cod_cotizacion')->nullable();
            $table->string('name')->nullable();
            $table->foreignId('customer_id')->nullable()->constrained('customers');
            $table->foreignId('course_id')->constrained('courses');
            $table->foreignId('add_course_id')->constrained('add_courses');
            $table->text('content')->nullable();
            $table->string('author');
            $table->json('grup')->nullable();
            $table->json('thour')->nullable();
            $table->json('tpart')->nullable();
            $table->json('vfranq')->nullable();
            $table->json('vunit')->nullable();
            $table->json('costs')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cotizacions');
    }
};
