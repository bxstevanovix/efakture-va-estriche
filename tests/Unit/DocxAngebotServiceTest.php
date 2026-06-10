<?php

namespace Tests\Unit;

use App\Services\DocxAngebotService;
use Tests\TestCase;
use ZipArchive;

class DocxAngebotServiceTest extends TestCase
{
    public function test_editor_spacing_is_preserved_in_generated_docx_note(): void
    {
        $xml = $this->documentXml([
            'document_label' => 'Rechnung',
            'customer_name' => 'Test GmbH',
            'address' => 'Teststrasse 1',
            'ort' => '1010 Wien',
            'uid' => '',
            'date' => '10.06.2026',
            'bvh' => '',
            'auftragsnr' => '',
            'number' => '1-2',
            'ausfuehrungszeit' => '',
            'spacing_top' => 20,
            'use_tax' => true,
            'bank_details' => 'Bankverbindung: UniCredit Bank Austria AG, BIC: BKAUATWW, IBAN: AT22 1200 0006 2226 3507',
            'note_html' => '<p><strong class="ql-size-huge">Gutschrift Teilrechnung 1-2</strong></p>'
                . "<p><strong class=\"ql-size-huge\">Netto\t300 EVRA</strong></p>"
                . '<p><strong class="ql-size-huge">Brutto&nbsp;&nbsp;&nbsp;&nbsp;450 E</strong></p>'
                . '<p><strong class="ql-size-huge">Ukupono.&nbsp;&nbsp;&nbsp;&nbsp;769 e</strong></p>',
            'items' => [],
            'summary' => [
                'subtotal' => '0,00',
                'adjustments' => [],
                'total' => '0,00',
            ],
        ]);

        $this->assertStringContainsString('Gutschrift Teilrechnung 1-2', $xml);
        $this->assertMatchesRegularExpression('/<w:t xml:space="preserve">Netto<\/w:t><w:tab\/><w:t xml:space="preserve">300 EVRA<\/w:t>/', $xml);
        $this->assertMatchesRegularExpression('/<w:t xml:space="preserve">Brutto<\/w:t><w:tab\/><w:t xml:space="preserve">450 E<\/w:t>/', $xml);
        $this->assertMatchesRegularExpression('/<w:t xml:space="preserve">Ukupono\.<\/w:t><w:tab\/><w:t xml:space="preserve">769 e<\/w:t>/', $xml);
    }

    private function documentXml(array $data): string
    {
        $directory = storage_path('framework/testing/docx');

        if (! is_dir($directory)) {
            mkdir($directory, 0775, true);
        }

        $path = $directory . '/docx-service-' . uniqid('', true) . '.docx';

        try {
            app(DocxAngebotService::class)->createFromData($path, $data);

            $zip = new ZipArchive();
            $this->assertTrue($zip->open($path));

            $xml = $zip->getFromName('word/document.xml');
            $zip->close();

            $this->assertIsString($xml);

            return $xml;
        } finally {
            if (is_file($path)) {
                unlink($path);
            }
        }
    }
}
