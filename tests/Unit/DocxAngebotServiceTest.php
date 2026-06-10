<?php

namespace Tests\Unit;

use App\Services\DocxAngebotService;
use Tests\TestCase;
use ZipArchive;

class DocxAngebotServiceTest extends TestCase
{
    public function test_editor_spacing_is_preserved_in_generated_docx_note(): void
    {
        $nbsp = html_entity_decode('&nbsp;', ENT_QUOTES | ENT_HTML5, 'UTF-8');
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
            'note_html' => '<p><strong>Tekst mora biti ovdje&nbsp;&nbsp;&nbsp;&nbsp;300e</strong></p>'
                . "<p>ako nije ovdje onda je problem\t\t200e</p>",
            'items' => [],
            'summary' => [
                'subtotal' => '0,00',
                'adjustments' => [],
                'total' => '0,00',
            ],
        ]);

        $this->assertStringContainsString('Tekst mora biti ovdje' . str_repeat($nbsp, 4) . '300e', $xml);
        $this->assertStringContainsString('ako nije ovdje onda je problem' . str_repeat($nbsp, 16) . '200e', $xml);
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
