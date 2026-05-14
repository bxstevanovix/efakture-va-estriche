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
        Schema::create('customer_invoices', function (Blueprint $table) {
            $table->id();

            $table->string('id_invoice', 20)->nullable();
            $table->text('text')->nullable();

            $table->integer('company')->nullable();
            $table->integer('status')->default(0);

            $table->decimal('price', 8, 2)->default(0.00);
            $table->decimal('price_part', 8, 2)->default(0.00);
            $table->decimal('debt', 8, 2)->default(0.00);

            $table->string('currency', 11)->nullable();
            $table->text('pdf')->nullable();

            $table->date('date_start');
            $table->date('date_end');
            $table->date('date_done')->nullable();

            $table->text('address')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_invoices');
    }
};
