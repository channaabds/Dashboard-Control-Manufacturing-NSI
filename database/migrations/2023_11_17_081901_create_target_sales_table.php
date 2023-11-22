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
        Schema::create('target_sales', function (Blueprint $table) {
            $table->id();
            $table->integer('januari')->default(1500);
            $table->integer('februari')->default(1500);
            $table->integer('maret')->default(1500);
            $table->integer('april')->default(1500);
            $table->integer('mei')->default(1500);
            $table->integer('juni')->default(1500);
            $table->integer('juli')->default(1500);
            $table->integer('agustus')->default(1500);
            $table->integer('september')->default(1500);
            $table->integer('oktober')->default(1500);
            $table->integer('november')->default(1500);
            $table->integer('desember')->default(1500);
            $table->date('tahun');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('target_sales');
    }
};
