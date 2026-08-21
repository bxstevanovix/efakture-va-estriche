<?php

namespace Tests\Feature;

use App\Models\CustomerInvoice;
use App\Models\SupplierInvoice;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardDatatableTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_datatable_handles_invoice_without_company(): void
    {
        CustomerInvoice::create([
            'id_invoice' => 'LEGACY-CUSTOMER',
            'company' => null,
            'date_start' => '2026-08-01',
            'date_end' => '2026-08-21',
            'price' => 100,
            'currency' => 'EUR',
        ]);

        $response = $this->postJson(route('datatable_customers'), [
            'date_start' => '2026-08-21',
            'year' => 2026,
        ]);

        $response->assertOk()->assertJsonPath('data.0.company', '---');
    }

    public function test_supplier_datatable_handles_invoice_without_company(): void
    {
        SupplierInvoice::create([
            'id_invoice' => 'LEGACY-SUPPLIER',
            'company' => null,
            'date_start' => '2026-08-01',
            'date_end' => '2026-08-21',
            'price' => 100,
            'currency' => 'EUR',
        ]);

        $response = $this->postJson(route('datatable_suppliers'), [
            'date_start1' => '2026-08-21',
            'year' => 2026,
        ]);

        $response->assertOk()->assertJsonPath('data.0.company', '---');
    }
}
