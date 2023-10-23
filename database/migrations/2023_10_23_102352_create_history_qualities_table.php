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
        Schema::create('history_qualities', function (Blueprint $table) {
            $table->id();
            $table->integer('targetCamIpqc')->default(0);
            $table->integer('targetCncIpqc')->default(0);
            $table->integer('targetMfgIpqc')->default(0);
            $table->integer('targetCamOqc')->default(0);
            $table->integer('targetCncOqc')->default(0);
            $table->integer('targetMfgOqc')->default(0);
            $table->integer('aktualCamIpqc')->default(0);
            $table->integer('aktualCncIpqc')->default(0);
            $table->integer('aktualMfgIpqc')->default(0);
            $table->integer('aktualCamOqc')->default(0);
            $table->integer('aktualCncOqc')->default(0);
            $table->integer('aktualMfgOqc')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('history_qualities');
    }
};
