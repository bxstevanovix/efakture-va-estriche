<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('firme', function (Blueprint $table) {
            $table->string('currency', 11)->nullable()->after('email');
        });

        Schema::create('legacy_company_mappings', function (Blueprint $table) {
            $table->id();
            $table->string('source', 32);
            $table->unsignedBigInteger('legacy_id');
            $table->unsignedBigInteger('firma_id');
            $table->timestamps();

            $table->unique(['source', 'legacy_id']);
            $table->index('firma_id');
        });

        Schema::table('customer_invoices', function (Blueprint $table) {
            $table->unsignedBigInteger('legacy_id')->nullable()->unique()->after('id');
            $table->decimal('square_meters', 8, 2)->default(0)->after('debt');
        });

        Schema::table('supplier_invoices', function (Blueprint $table) {
            $table->unsignedBigInteger('legacy_id')->nullable()->unique()->after('id');
        });
    }

    public function down(): void
    {
        Schema::table('supplier_invoices', function (Blueprint $table) {
            $table->dropColumn('legacy_id');
        });

        Schema::table('customer_invoices', function (Blueprint $table) {
            $table->dropColumn(['legacy_id', 'square_meters']);
        });

        Schema::dropIfExists('legacy_company_mappings');

        Schema::table('firme', function (Blueprint $table) {
            $table->dropColumn('currency');
        });
    }
};
