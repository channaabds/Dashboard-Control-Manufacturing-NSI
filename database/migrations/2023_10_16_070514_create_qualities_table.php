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
        Schema::create('qualities', function (Blueprint $table) {
            $table->id();
            $table->enum('departement', ['IPQC', 'OQC']);
            $table->enum('keterangan', ['NCR', 'LOT TAG']);
            $table->string('no_ncr_lot')->nullable();
            $table->string('part_no')->nullable();
            $table->string('lot_no')->nullable();
            $table->string('mesin')->nullable();
            $table->string('defect')->nullable();
            $table->string('standard')->nullable();
            $table->string('actual')->nullable();
            $table->string('sampling')->nullable();
            $table->integer('qty_check')->nullable();
            $table->integer('ng')->nullable();
            $table->string('ng_pic')->nullable();
            $table->string('approve_pic')->nullable();
            $table->text('penyebab')->nullable();
            $table->text('action')->nullable();
            $table->date('date')->nullable();
            $table->date('deadline')->nullable();
            $table->enum('status', ['CLOSE', 'OPEN']);
            $table->string('pic_input')->nullable();
            $table->string('judgement')->nullable();
            $table->string('pembahasan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qualities');
    }
};
