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
            $table->integer('target_cam_ipqc')->default(0);
            $table->integer('target_cnc_ipqc')->default(0);
            $table->integer('target_mfg_ipqc')->default(0);
            $table->integer('target_cam_oqc')->default(0);
            $table->integer('target_cnc_oqc')->default(0);
            $table->integer('target_mfg_oqc')->default(0);
            $table->integer('ncr_cam_ipqc')->default(0);
            $table->integer('lot_cam_ipqc')->default(0);
            $table->integer('ncr_cnc_ipqc')->default(0);
            $table->integer('lot_cnc_ipqc')->default(0);
            $table->integer('ncr_mfg_ipqc')->default(0);
            $table->integer('lot_mfg_ipqc')->default(0);
            $table->integer('ncr_cam_oqc')->default(0);
            $table->integer('lot_cam_oqc')->default(0);
            $table->integer('ncr_cnc_oqc')->default(0);
            $table->integer('lot_cnc_oqc')->default(0);
            $table->integer('ncr_mfg_oqc')->default(0);
            $table->integer('lot_mfg_oqc')->default(0);
            // $table->integer('aktual_cam_ipqc')->default(0);
            // $table->integer('aktual_cnc_ipqc')->default(0);
            // $table->integer('aktual_mfg_ipqc')->default(0);
            // $table->integer('aktual_cam_oqc')->default(0);
            // $table->integer('aktual_cnc_oqc')->default(0);
            // $table->integer('aktual_mfg_oqc')->default(0);
            $table->string('date');
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
