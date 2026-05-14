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
        Schema::create('invoice_pdfs', function (Blueprint $table) {
            $table->id(); // ID PDF-a
            $table->string('invoice_type'); // 'customer' ili 'supplier'
            $table->unsignedBigInteger('invoice_id');
            $table->string('file_name');
            $table->string('file_path');
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('deleted_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_pdfs');
    }
};
