<?php

namespace Tests\Feature;

use App\Models\Firma;
use App\Models\SupplierInvoice;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SupplierInvoiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_supplier_invoice_amount_accepts_decimal_comma(): void
    {
        $company = Firma::create(['name' => 'Lieferant GmbH']);
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/supplier-invoices/create', [
            'id_invoice' => 'ER-001/2026',
            'company' => $company->id,
            'date_start' => '15-05-2026',
            'date_end' => '25-05-2026',
            'price' => '123,45',
            'text' => null,
            'address' => 'Teststrasse 1',
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('supplier-invoices.index', absolute: false));

        $invoice = SupplierInvoice::firstOrFail();

        $this->assertSame(123.45, (float) $invoice->price);
        $this->assertSame(123.45, (float) $invoice->debt);
    }

    public function test_supplier_invoice_amount_accepts_cents_below_one_euro(): void
    {
        $company = Firma::create(['name' => 'Lieferant GmbH']);
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/supplier-invoices/create', [
            'id_invoice' => 'ER-002/2026',
            'company' => $company->id,
            'date_start' => '15-05-2026',
            'date_end' => '25-05-2026',
            'price' => '0,50',
            'text' => null,
            'address' => 'Teststrasse 1',
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('supplier-invoices.index', absolute: false));

        $invoice = SupplierInvoice::firstOrFail();

        $this->assertSame(0.5, (float) $invoice->price);
        $this->assertSame(0.5, (float) $invoice->debt);
    }
}
