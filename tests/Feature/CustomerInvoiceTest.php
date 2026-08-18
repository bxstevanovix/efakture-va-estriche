<?php

namespace Tests\Feature;

use App\Models\CustomerInvoice;
use App\Models\Firma;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerInvoiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_invoice_amount_accepts_decimal_comma(): void
    {
        $company = Firma::create(['name' => 'Test GmbH']);
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/customer-invoices/create', [
            'id_invoice' => '001/2026',
            'company' => $company->id,
            'date_start' => '15-05-2026',
            'date_end' => '25-05-2026',
            'price' => '123,45',
            'text' => null,
            'address' => 'Teststrasse 1',
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('customer-invoices.index', absolute: false));

        $invoice = CustomerInvoice::firstOrFail();

        $this->assertSame(123.45, (float) $invoice->price);
        $this->assertSame(123.45, (float) $invoice->debt);
    }

    public function test_customer_invoice_amount_accepts_cents_below_one_euro(): void
    {
        $company = Firma::create(['name' => 'Test GmbH']);
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/customer-invoices/create', [
            'id_invoice' => '002/2026',
            'company' => $company->id,
            'date_start' => '15-05-2026',
            'date_end' => '25-05-2026',
            'price' => '0,50',
            'text' => null,
            'address' => 'Teststrasse 1',
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('customer-invoices.index', absolute: false));

        $invoice = CustomerInvoice::firstOrFail();

        $this->assertSame(0.5, (float) $invoice->price);
        $this->assertSame(0.5, (float) $invoice->debt);
    }

    public function test_reports_datatable_handles_legacy_invoice_without_company(): void
    {
        $user = User::factory()->create();
        CustomerInvoice::create([
            'id_invoice' => 'LEGACY-001',
            'company' => null,
            'date_start' => '2026-01-01',
            'date_end' => '2026-01-31',
            'price' => 100,
            'debt' => 100,
            'currency' => 'EUR',
        ]);

        $response = $this->actingAs($user)->postJson('/customer-invoices/reports/datatable', [
            'draw' => 1,
            'start' => 0,
            'length' => 10,
        ]);

        $response->assertOk()->assertJsonPath('data.0.company', '---');
    }
}
