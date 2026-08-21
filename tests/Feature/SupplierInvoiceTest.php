<?php

namespace Tests\Feature;

use App\Models\Firma;
use App\Models\SupplierInvoice;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
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

    public function test_reports_datatable_handles_legacy_invoice_without_company(): void
    {
        $user = User::factory()->create();
        SupplierInvoice::create([
            'id_invoice' => 'LEGACY-ER-001',
            'company' => null,
            'date_start' => '2026-01-01',
            'date_end' => '2026-01-31',
            'price' => 100,
            'debt' => 100,
            'currency' => 'EUR',
        ]);

        $response = $this->actingAs($user)->postJson('/supplier-invoices/reports/datatable', [
            'draw' => 1,
            'start' => 0,
            'length' => 10,
        ]);

        $response->assertOk()->assertJsonPath('data.0.company', '---');
    }

    public function test_legacy_procurement_pdf_is_opened_directly(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('procurement/2026/07/legacy.pdf', '%PDF-1.4 legacy');

        $invoice = SupplierInvoice::create([
            'id_invoice' => 'LEGACY-PDF',
            'date_start' => '2026-07-01',
            'date_end' => '2026-07-31',
            'price' => 100,
            'pdf' => 'procurement/2026/07/legacy.pdf',
        ]);

        $response = $this->actingAs(User::factory()->create())
            ->get(route('supplier-invoices.view', $invoice));

        $response->assertOk()
            ->assertHeader('content-type', 'application/pdf')
            ->assertHeader('content-disposition', 'inline; filename="Rechnung_legacy-pdf.pdf"');
    }

    public function test_new_supplier_pdf_still_uses_pdf_viewer(): void
    {
        Storage::fake('public');
        $path = 'eingangsrechnungen/7-strabag-ag/099-99/polizze-9.pdf';
        Storage::disk('public')->put($path, '%PDF-1.4 new');

        $invoice = SupplierInvoice::create([
            'id_invoice' => '099/99',
            'date_start' => '2026-07-01',
            'date_end' => '2026-07-31',
            'price' => 100,
            'pdf' => $path,
        ]);

        $response = $this->actingAs(User::factory()->create())
            ->get(route('supplier-invoices.view', $invoice));

        $response->assertOk()
            ->assertViewIs('pdf.viewer')
            ->assertViewHas('pdfUrl', route('supplier-invoices.pdf', $invoice));
    }
}
