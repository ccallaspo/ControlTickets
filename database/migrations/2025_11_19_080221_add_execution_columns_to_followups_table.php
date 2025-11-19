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
        Schema::table('followups', function (Blueprint $table) {
            $table->boolean('has_execution_data')->default(false)->after('f_end');
            $table->string('exec_cod_sence_course')->nullable();
            $table->string('exec_name_course')->nullable();
            $table->string('exec_id_sence')->nullable();
            $table->string('exec_modalily')->nullable();
            $table->date('exec_f_star')->nullable();
            $table->date('exec_f_end')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('followups', function (Blueprint $table) {
            $table->dropColumn([
                'has_execution_data',
                'exec_cod_sence_course',
                'exec_name_course',
                'exec_id_sence',
                'exec_modalily',
                'exec_f_star',
                'exec_f_end',
            ]);
        });
    }
};
