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
        Schema::create('rechnungen', function (Blueprint $table) {
            $table->id();
            $table->string('id_invoice', 20)->nullable()->index();
            $table->text('type')->nullable();
            $table->text('firma')->nullable();
            $table->text('price')->default('0.00');
            $table->text('adress')->nullable();
            $table->text('ort')->nullable();
            $table->text('uid')->nullable();
            $table->text('bvh')->nullable();
            $table->text('auftragsnr')->nullable();
            $table->text('ausfuhrungszeit')->nullable();
            $table->text('invoice_url')->nullable();
            $table->date('date_start');
            $table->text('note')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rechnungen');
    }
};
