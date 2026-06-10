<?php

namespace App\Services;

use DOMDocument;
use DOMElement;
use DOMNode;
use RuntimeException;
use ZipArchive;

class DocxAngebotService
{
    private const DEFAULT_BANK_DETAILS = 'Bankverbindung: UniCredit Bank Austria AG, BIC: BKAUATWW, IBAN: AT22 1200 0006 2226 3507';
    private const TWIPS_PER_MM = 56.6929133858;
    private const EMU_PER_PIXEL = 9525;
    private const TABLE_WIDTH = 10092;
    private const TABLE_COLUMNS = [5592, 1500, 1500, 1500];
    private const PAGE_HEIGHT = 16838;
    private const PAGE_TOP_MARGIN = 3628;
    private const PAGE_BOTTOM_MARGIN = 1587;
    private const PAGE_BODY_HEIGHT = self::PAGE_HEIGHT - self::PAGE_TOP_MARGIN - self::PAGE_BOTTOM_MARGIN;
    private const TABLE_ROW_HEIGHT = 360;
    private const TABLE_HEADER_BODY_GAP = 40;
    private const SUMMARY_PAGE_RESERVE = 760;
    private const NOTE_RIGHT_TAB_POSITION = 5775;

    public function create(string $path): string
    {
        return $this->createFromData($path, $this->demoData());
    }

    public function createFromData(string $path, array $data): string
    {
        $directory = dirname($path);

        if (! is_dir($directory)) {
            mkdir($directory, 0775, true);
        }

        $zip = new ZipArchive();

        if ($zip->open($path, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            throw new RuntimeException('DOCX fajl nije moguće kreirati.');
        }

        $logoPath = public_path('img/full-logo.png');
        $hasLogo = is_file($logoPath);
        $showPageNumbers = ! empty($data['show_page_numbers']);
        $bankDetails = trim((string) ($data['bank_details'] ?? '')) ?: self::DEFAULT_BANK_DETAILS;

        $zip->addFromString('[Content_Types].xml', $this->contentTypes($hasLogo));
        $zip->addFromString('_rels/.rels', $this->rootRels());
        $zip->addFromString('word/_rels/document.xml.rels', $this->documentRels($hasLogo));
        $zip->addFromString('word/styles.xml', $this->styles());
        $zip->addFromString('word/header1.xml', $this->header($hasLogo));
        $zip->addFromString('word/footer1.xml', $this->footer($showPageNumbers, $bankDetails));
        $zip->addFromString('word/document.xml', $this->document($data, $hasLogo));

        if ($hasLogo) {
            $zip->addFromString('word/_rels/header1.xml.rels', $this->headerRels());
            $zip->addFromString('word/media/logo.png', file_get_contents($logoPath));
        }

        $zip->close();

        return $path;
    }

    private function document(array $data, bool $hasLogo): string
    {
        $tableRows = '';

        foreach (($data['items'] ?? []) as $row) {
            $tableRows .= $this->tableRow($row);
        }

        if (($data['items'] ?? []) === []) {
            $tableRows .= $this->tableRow(['', '', '', '']);
        }

        $summaryBreaksToNewPage = $this->shouldBreakBeforeSummary($data);
        $summaryPageLead = $summaryBreaksToNewPage
            ? $this->pageBreak() . $this->repeatedCustomerBlock($data)
            : $this->spacer(180);
        $spacingTop = (int) ($data['spacing_top'] ?? 20);
        $bvh = trim((string) ($data['bvh'] ?? ''));
        $auftragsnr = trim((string) ($data['auftragsnr'] ?? ''));
        $ausfuehrungszeit = trim((string) ($data['ausfuehrungszeit'] ?? ''));
        $documentLabel = trim((string) ($data['document_label'] ?? 'Angebot'));
        $title = $documentLabel . ' ' . trim((string) ($data['number'] ?? ''));
        $titleRuns = [$this->run($title, ['bold' => true])];

        if ($ausfuehrungszeit !== '') {
            $titleRuns[] = $this->run(', Ausführungszeit: ' . $ausfuehrungszeit);
        }

        $itemsTable = $this->table($this->tableHeader())
            . $this->spacer(self::TABLE_HEADER_BODY_GAP)
            . $this->table($tableRows);
        $note = $this->noteParagraphs((string) ($data['note_html'] ?? ''));
        $reverseVat = empty($data['use_tax'])
            ? $this->paragraph('Bauleistung ohne USt. (MwSt. zahlt Empfänger gemäß §19 Abs. 1a UStG 1994)', [
                'align' => 'center',
                'size' => 18,
                'before' => 500,
            ])
            : '';

        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<w:document xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships" xmlns:wp="http://schemas.openxmlformats.org/drawingml/2006/wordprocessingDrawing" xmlns:a="http://schemas.openxmlformats.org/drawingml/2006/main" xmlns:pic="http://schemas.openxmlformats.org/drawingml/2006/picture">
  <w:body>
    ' . $this->spacer($this->pxToTwips($spacingTop)) . '
    ' . $this->paragraph((string) ($data['customer_name'] ?? ''), ['bold' => true, 'after' => 70]) . '
    ' . $this->paragraph((string) ($data['address'] ?? ''), ['after' => 10]) . '
    ' . $this->paragraph((string) ($data['ort'] ?? ''), ['after' => 0]) . '
    ' . $this->horizontalLine(3500) . '
    ' . $this->metaTable((string) ($data['uid'] ?? ''), (string) ($data['date'] ?? '')) . '
    ' . ($bvh !== '' ? $this->paragraph('BVH. ' . $bvh, ['before' => 200, 'after' => 30]) : '') . '
    ' . ($auftragsnr !== '' ? $this->paragraph($auftragsnr, ['after' => 30]) : '') . '
    ' . $this->richParagraph($titleRuns, ['after' => 70]) . '
    ' . $itemsTable . '
    ' . $summaryPageLead . '
    ' . $this->summary($data['summary'] ?? []) . '
    ' . $note . '
    ' . $reverseVat . '
    <w:sectPr>
      <w:headerReference w:type="default" r:id="rIdHeader1"/>
      <w:footerReference w:type="default" r:id="rIdFooter1"/>
      <w:pgSz w:w="11906" w:h="16838"/>
      <w:pgMar w:top="' . $this->mmToTwips(64) . '" w:right="870" w:bottom="' . $this->mmToTwips(28) . '" w:left="' . $this->mmToTwips(16) . '" w:header="' . $this->mmToTwips(4) . '" w:footer="' . $this->mmToTwips(14) . '" w:gutter="0"/>
    </w:sectPr>
  </w:body>
</w:document>';
    }

    private function companyInfoTable(bool $hasLogo): string
    {
        $headerWidth = 5000;
        $dataColumnWidth = 3500;
        $logoColumnWidth = 1500;

        return '<w:tbl>
  <w:tblPr>
    <w:jc w:val="right"/>
    <w:tblW w:w="' . $headerWidth . '" w:type="dxa"/>
    <w:tblBorders>
      <w:top w:val="nil"/>
      <w:left w:val="nil"/>
      <w:bottom w:val="nil"/>
      <w:right w:val="nil"/>
      <w:insideH w:val="nil"/>
      <w:insideV w:val="nil"/>
    </w:tblBorders>
    <w:tblCellMar>
      <w:top w:w="0" w:type="dxa"/>
      <w:left w:w="0" w:type="dxa"/>
      <w:bottom w:w="0" w:type="dxa"/>
      <w:right w:w="0" w:type="dxa"/>
    </w:tblCellMar>
  </w:tblPr>
  <w:tblGrid>
    <w:gridCol w:w="' . $dataColumnWidth . '"/>
    <w:gridCol w:w="' . $logoColumnWidth . '"/>
  </w:tblGrid>
  <w:tr>
    <w:tc>
      <w:tcPr><w:tcW w:w="' . $headerWidth . '" w:type="dxa"/><w:gridSpan w:val="2"/></w:tcPr>
      ' . $this->paragraph('VULETIĆ & ANDRIĆ GmbH', ['color' => '666666', 'size' => 40, 'after' => 210, 'align' => 'right', 'before' => 400]) . '
    </w:tc>
  </w:tr>
  <w:tr>
    ' . $this->plainCell([
            '2230 Gänserndorf, Neusiedler Straße 20a',
            'Tel: 01/985 50 49 - Mob: 0676/480 46 49',
            'www.va-estriche.at / office@va-estriche.at',
            'Firmenbuchnummer: 53572 h',
            'Dienstgebernummer: 700673615',
            'UID-Nummer: ATU36793706',
        ], $dataColumnWidth, ['color' => '666666', 'size' => 16, 'align' => 'left', 'after' => 95]) . '
    <w:tc>
      <w:tcPr>
        <w:tcW w:w="' . $logoColumnWidth . '" w:type="dxa"/>
        <w:vAlign w:val="center"/>
        <w:tcMar>
          <w:top w:w="0" w:type="dxa"/>
          <w:left w:w="0" w:type="dxa"/>
          <w:bottom w:w="0" w:type="dxa"/>
          <w:right w:w="0" w:type="dxa"/>
        </w:tcMar>
      </w:tcPr>
      ' . ($hasLogo ? $this->logoParagraph() : '') . '
    </w:tc>
  </w:tr>
</w:tbl>';
    }

    private function metaTable(string $uid, string $date): string
    {
        $left = trim($uid) !== '' ? 'UID-Nummer: ' . $uid : '';

        return '<w:tbl>
  <w:tblPr><w:tblW w:w="5000" w:type="pct"/></w:tblPr>
  <w:tblGrid><w:gridCol w:w="5200"/><w:gridCol w:w="5200"/></w:tblGrid>
  <w:tr>
    ' . $this->plainCell([$left], 0) . '
    ' . $this->plainCell(['Datum: ' . $date], 5200, ['align' => 'right']) . '
  </w:tr>
</w:tbl>';
    }

    private function repeatedCustomerBlock(array $data): string
    {
        return $this->spacer(100)
            . $this->paragraph((string) ($data['customer_name'] ?? ''), ['bold' => true, 'after' => 70])
            . $this->paragraph((string) ($data['address'] ?? ''), ['after' => 10])
            . $this->paragraph((string) ($data['ort'] ?? ''), ['after' => 0])
            . $this->horizontalLine(3500);
    }

    private function tableHeader(): string
    {
        return $this->tableRow(['Beschreibung', 'Menge', 'Einzelpreis', 'Betrag'], true);
    }

    private function table(string $rows): string
    {
        return '<w:tbl>
  <w:tblPr>
    <w:tblW w:w="' . self::TABLE_WIDTH . '" w:type="dxa"/>
    <w:tblBorders>
      <w:top w:val="single" w:sz="4" w:space="0" w:color="333333"/>
      <w:left w:val="single" w:sz="4" w:space="0" w:color="333333"/>
      <w:bottom w:val="single" w:sz="4" w:space="0" w:color="333333"/>
      <w:right w:val="single" w:sz="4" w:space="0" w:color="333333"/>
      <w:insideH w:val="single" w:sz="4" w:space="0" w:color="333333"/>
      <w:insideV w:val="single" w:sz="4" w:space="0" w:color="333333"/>
    </w:tblBorders>
  </w:tblPr>
  <w:tblGrid>
    <w:gridCol w:w="' . self::TABLE_COLUMNS[0] . '"/>
    <w:gridCol w:w="' . self::TABLE_COLUMNS[1] . '"/>
    <w:gridCol w:w="' . self::TABLE_COLUMNS[2] . '"/>
    <w:gridCol w:w="' . self::TABLE_COLUMNS[3] . '"/>
  </w:tblGrid>
  ' . $rows . '
</w:tbl>';
    }

    private function tableRow(array $cells, bool $header = false): string
    {
        $widths = self::TABLE_COLUMNS;
        $rowProperties = $header
            ? '<w:trPr><w:tblHeader/><w:trHeight w:val="360" w:hRule="atLeast"/></w:trPr>'
            : '<w:trPr><w:cantSplit/><w:trHeight w:val="360" w:hRule="atLeast"/></w:trPr>';

        return '<w:tr>' . $rowProperties . collect($cells)->map(function ($cell, $index) use ($header, $widths) {
            $align = $index === 0 ? 'left' : 'center';
            $content = (! $header && $index === 3)
                ? $this->amountCellParagraph((string) $cell)
                : $this->paragraph((string) $cell, [
                    'bold' => $header,
                    'align' => $align,
                    'after' => 0,
                    'before' => 0,
                ]);

            return '<w:tc>
  <w:tcPr><w:tcW w:w="' . $widths[$index] . '" w:type="dxa"/><w:vAlign w:val="center"/></w:tcPr>
  ' . $content . '
</w:tc>';
        })->implode('') . '</w:tr>';
    }

    private function amountCellParagraph(string $value): string
    {
        return '<w:p>
  <w:pPr>
    <w:tabs><w:tab w:val="right" w:pos="1320"/></w:tabs>
    <w:spacing w:before="0" w:after="0"/>
  </w:pPr>
  <w:r><w:rPr>' . $this->runStyle() . '</w:rPr><w:t xml:space="preserve">€</w:t></w:r>
  <w:r><w:tab/></w:r>
  <w:r><w:rPr>' . $this->runStyle() . '</w:rPr><w:t xml:space="preserve">' . $this->e($value) . '</w:t></w:r>
</w:p>';
    }

    private function summary(array $summary): string
    {
        $rows = $this->summaryRow('Zwischensumme', $summary['subtotal'] ?? '0,00');

        foreach ($summary['adjustments'] ?? [] as $adjustment) {
            $rows .= $this->summaryRow($adjustment['label'], $adjustment['amount']);

            if ($this->shouldShowRunningTotal($adjustment)) {
                $rows .= $this->summaryRow('', $adjustment['running_total'], true);
            }
        }

        $rows .= $this->summaryRow('Gesamtbetrag', '€ ' . ($summary['total'] ?? '0,00'), false, true);

        return '<w:tbl>
  <w:tblPr>
    <w:jc w:val="right"/>
    <w:tblInd w:w="-500" w:type="dxa"/>
    <w:tblW w:w="4600" w:type="dxa"/>
    <w:tblBorders>
      <w:top w:val="nil"/>
      <w:left w:val="nil"/>
      <w:bottom w:val="nil"/>
      <w:right w:val="nil"/>
      <w:insideH w:val="nil"/>
      <w:insideV w:val="nil"/>
    </w:tblBorders>
  </w:tblPr>
  <w:tblGrid><w:gridCol w:w="3000"/><w:gridCol w:w="1600"/></w:tblGrid>
  ' . $rows . '
</w:tbl>';
    }

    private function summaryRow(string $label, string $value, bool $overline = false, bool $total = false): string
    {
        $size = 22;
        $keepNext = ! $total;
        $amountBorders = $overline
            ? '<w:tcBorders><w:top w:val="single" w:sz="4" w:space="0" w:color="777777"/></w:tcBorders>'
            : '';

        if ($total) {
            $amountBorders = '<w:tcBorders><w:bottom w:val="double" w:sz="6" w:space="1" w:color="000000"/></w:tcBorders>';
        }

        $topSpacing = $total ? 220 : 0;

        return '<w:tr>
  <w:trPr><w:cantSplit/></w:trPr>
  <w:tc>
    <w:tcPr><w:tcW w:w="2000" w:type="dxa"/></w:tcPr>
    ' . $this->paragraph($label, ['bold' => $total, 'size' => $size, 'before' => $topSpacing, 'after' => 20, 'keep_next' => $keepNext]) . '
  </w:tc>
  <w:tc>
    <w:tcPr><w:tcW w:w="3000" w:type="dxa"/>' . $amountBorders . '</w:tcPr>
    ' . $this->paragraph($value, ['bold' => $total, 'size' => $size, 'align' => 'right', 'before' => $topSpacing, 'after' => 20, 'keep_next' => $keepNext]) . '
  </w:tc>
</w:tr>';
    }

    private function noteParagraphs(string $html): string
    {
        if (trim($this->htmlToText($html)) === '') {
            return '';
        }

        $root = $this->parseHtmlFragment($html);
        $paragraphs = '';
        $first = true;

        foreach ($root->childNodes as $node) {
            $nodeParagraphs = $this->noteNodeParagraphs($node, $first);

            if ($nodeParagraphs !== '') {
                $paragraphs .= $nodeParagraphs;
                $first = false;
            }
        }

        return $paragraphs;
    }

    private function parseHtmlFragment(string $html): DOMElement
    {
        $document = new DOMDocument('1.0', 'UTF-8');
        $previous = libxml_use_internal_errors(true);

        $document->loadHTML(
            '<?xml encoding="UTF-8"><!doctype html><html><body><div id="note-root">' . $html . '</div></body></html>',
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
        );

        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        foreach ($document->getElementsByTagName('div') as $div) {
            if ($div->getAttribute('id') === 'note-root') {
                return $div;
            }
        }

        throw new RuntimeException('HTML note nije moguće obraditi.');
    }

    private function noteNodeParagraphs(DOMNode $node, bool $first): string
    {
        if ($node->nodeType === XML_TEXT_NODE) {
            $text = $this->normalizeTextNode($node->nodeValue ?? '');

            if (trim($text) === '') {
                return '';
            }

            return $this->richParagraph([$this->run($text, $this->defaultInlineStyle())], [
                'before' => $first ? 220 : 0,
                'after' => 35,
                'left' => 380,
                'editor_spacing_tabs' => true,
            ]);
        }

        if (! $node instanceof DOMElement) {
            return '';
        }

        $tag = strtolower($node->tagName);

        if ($tag === 'br') {
            return '';
        }

        if (in_array($tag, ['ul', 'ol'], true)) {
            return $this->listParagraphs($node, $tag === 'ol', $first);
        }

        if (in_array($tag, ['p', 'div'], true)) {
            $runs = $this->inlineRuns($node, $this->defaultInlineStyle());

            if (! $this->runsHaveContent($runs)) {
                return '';
            }

            return $this->richParagraph($runs, [
                'before' => $first ? 220 : 0,
                'after' => 35,
                'left' => 380,
                'editor_spacing_tabs' => true,
            ]);
        }

        $paragraphs = '';

        foreach ($node->childNodes as $child) {
            $childParagraphs = $this->noteNodeParagraphs($child, $first && $paragraphs === '');

            if ($childParagraphs !== '') {
                $paragraphs .= $childParagraphs;
            }
        }

        return $paragraphs;
    }

    private function listParagraphs(DOMElement $list, bool $ordered, bool $first): string
    {
        $paragraphs = '';
        $index = 1;

        foreach ($list->childNodes as $child) {
            if (! $child instanceof DOMElement || strtolower($child->tagName) !== 'li') {
                continue;
            }

            $marker = $ordered ? $index . '. ' : '• ';
            $runs = array_merge(
                [$this->run($marker)],
                $this->inlineRuns($child, $this->defaultInlineStyle())
            );

            if ($this->runsHaveContent($runs)) {
                $paragraphs .= $this->richParagraph($runs, [
                    'before' => ($first && $paragraphs === '') ? 220 : 0,
                    'after' => 35,
                    'left' => 520,
                    'hanging' => 180,
                    'editor_spacing_tabs' => true,
                ]);
            }

            $index++;
        }

        return $paragraphs;
    }

    private function inlineRuns(DOMNode $node, array $style): array
    {
        if ($node->nodeType === XML_TEXT_NODE) {
            $text = $this->normalizeTextNode($node->nodeValue ?? '');

            return $text === '' ? [] : [$this->run($text, $style)];
        }

        if (! $node instanceof DOMElement) {
            return [];
        }

        $tag = strtolower($node->tagName);

        if ($tag === 'br') {
            return [$this->lineBreakRun()];
        }

        if (in_array($tag, ['ul', 'ol'], true)) {
            return [];
        }

        $style = $this->mergeInlineStyle($node, $style);
        $runs = [];

        foreach ($node->childNodes as $child) {
            array_push($runs, ...$this->inlineRuns($child, $style));
        }

        return $runs;
    }

    private function mergeInlineStyle(DOMElement $element, array $style): array
    {
        $tag = strtolower($element->tagName);

        if (in_array($tag, ['strong', 'b'], true)) {
            $style['bold'] = true;
        }

        if (in_array($tag, ['em', 'i'], true)) {
            $style['italic'] = true;
        }

        if ($tag === 'u') {
            $style['underline'] = true;
        }

        $classes = preg_split('/\s+/', $element->getAttribute('class')) ?: [];

        if (in_array('ql-size-small', $classes, true)) {
            $style['size'] = 16;
        } elseif (in_array('ql-size-large', $classes, true)) {
            $style['size'] = 26;
        } elseif (in_array('ql-size-huge', $classes, true)) {
            $style['size'] = 36;
        }

        return $style;
    }

    private function defaultInlineStyle(): array
    {
        return ['size' => 22, 'editor_spacing_tabs' => true];
    }

    private function normalizeTextNode(string $text): string
    {
        return html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    private function runsHaveContent(array $runs): bool
    {
        foreach ($runs as $run) {
            if (str_contains($run, '<w:br/>') || trim(strip_tags($run)) !== '') {
                return true;
            }

            if (preg_match('/<w:t[^>]*>(.*?)<\/w:t>/s', $run, $matches) && trim($matches[1]) !== '') {
                return true;
            }
        }

        return false;
    }

    private function richParagraph(array $runs, array $options = []): string
    {
        $align = $options['align'] ?? 'left';
        $before = (int) ($options['before'] ?? 0);
        $after = (int) ($options['after'] ?? 40);
        $keepNext = ! empty($options['keep_next']) ? '<w:keepNext/>' : '';
        $tabs = ! empty($options['editor_spacing_tabs'])
            ? '<w:tabs><w:tab w:val="right" w:pos="' . self::NOTE_RIGHT_TAB_POSITION . '"/></w:tabs>'
            : '';
        $indent = '';

        if (! empty($options['left'])) {
            $indent = '<w:ind w:left="' . (int) $options['left'] . '"'
                . (! empty($options['hanging']) ? ' w:hanging="' . (int) $options['hanging'] . '"' : '')
                . '/>';
        }

        return '<w:p>
  <w:pPr>' . $keepNext . $tabs . '<w:jc w:val="' . $align . '"/><w:spacing w:before="' . $before . '" w:after="' . $after . '"/>' . $indent . '</w:pPr>
  ' . implode('', $runs) . '
</w:p>';
    }

    private function run(string $text, array $options = []): string
    {
        if ($text === '') {
            return '';
        }

        return '<w:r><w:rPr>' . $this->runStyle($options) . '</w:rPr>' . $this->textWithBreaks($text, $options) . '</w:r>';
    }

    private function lineBreakRun(): string
    {
        return '<w:r><w:br/></w:r>';
    }

    private function htmlToText(string $html): string
    {
        $html = preg_replace('/<br\s*\/?>/i', "\n", $html) ?? $html;
        $html = preg_replace('/<\/p>/i', "\n", $html) ?? $html;
        $html = preg_replace('/<\/div>/i', "\n", $html) ?? $html;
        $html = str_replace('&nbsp;', ' ', $html);

        return html_entity_decode(strip_tags($html), ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    private function paragraph(string $text, array $options = []): string
    {
        $align = $options['align'] ?? 'left';
        $before = (int) ($options['before'] ?? 0);
        $after = (int) ($options['after'] ?? 40);
        $keepNext = ! empty($options['keep_next']) ? '<w:keepNext/>' : '';
        $indent = ! empty($options['left']) ? '<w:ind w:left="' . (int) $options['left'] . '"/>' : '';

        return '<w:p>
  <w:pPr>' . $keepNext . '<w:jc w:val="' . $align . '"/><w:spacing w:before="' . $before . '" w:after="' . $after . '"/>' . $indent . '</w:pPr>
  <w:r><w:rPr>' . $this->runStyle($options) . '</w:rPr>' . $this->textWithBreaks($text) . '</w:r>
</w:p>';
    }

    private function textWithBreaks(string $text, array $options = []): string
    {
        $parts = preg_split('/\R/u', $text) ?: [''];
        $xml = '';

        foreach ($parts as $index => $part) {
            $xml .= ($index > 0 ? '<w:br/>' : '')
                . $this->textPartXml($part, $options);
        }

        return $xml !== '' ? $xml : '<w:t xml:space="preserve"></w:t>';
    }

    private function textPartXml(string $text, array $options = []): string
    {
        if (empty($options['editor_spacing_tabs'])) {
            return '<w:t xml:space="preserve">' . $this->e($text) . '</w:t>';
        }

        $nbsp = html_entity_decode('&nbsp;', ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $separatorPattern = '/(\t+|[ ' . preg_quote($nbsp, '/') . ']{2,})/u';
        $chunks = preg_split($separatorPattern, $text, -1, PREG_SPLIT_DELIM_CAPTURE);

        if ($chunks === false || count($chunks) === 1) {
            return '<w:t xml:space="preserve">' . $this->e($text) . '</w:t>';
        }

        $xml = '';
        $hasTextBefore = false;

        foreach ($chunks as $index => $chunk) {
            if ($chunk === '') {
                continue;
            }

            if (preg_match($separatorPattern, $chunk)) {
                if ($hasTextBefore && $this->hasTextAfter($chunks, $index)) {
                    $xml .= '<w:tab/>';
                } else {
                    $xml .= '<w:t xml:space="preserve">' . $this->e($chunk) . '</w:t>';
                }

                continue;
            }

            $xml .= '<w:t xml:space="preserve">' . $this->e($chunk) . '</w:t>';
            $hasTextBefore = true;
        }

        return $xml !== '' ? $xml : '<w:t xml:space="preserve"></w:t>';
    }

    private function hasTextAfter(array $chunks, int $index): bool
    {
        $nbsp = html_entity_decode('&nbsp;', ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $separatorPattern = '/^(\t+|[ ' . preg_quote($nbsp, '/') . ']{2,})$/u';

        for ($i = $index + 1; $i < count($chunks); $i++) {
            if ($chunks[$i] !== '' && ! preg_match($separatorPattern, $chunks[$i])) {
                return true;
            }
        }

        return false;
    }

    private function plainCell(array $lines, int $width, array $options = []): string
    {
        return '<w:tc>
  <w:tcPr>
    <w:tcW w:w="' . $width . '" w:type="dxa"/>
    <w:tcMar>
      <w:left w:w="100" w:type="dxa"/>
      <w:right w:w="0" w:type="dxa"/>
    </w:tcMar>
  </w:tcPr>
  ' . collect($lines)->map(fn ($line) => $this->paragraph((string) $line, [
            'align' => $options['align'] ?? 'left',
            'color' => $options['color'] ?? null,
            'size' => $options['size'] ?? 22,
            'after' => $options['after'] ?? 0,
        ]))->implode('') . '
</w:tc>';
    }

    private function horizontalLine(int $width): string
    {
        return '<w:p>
    <w:pPr>
        <w:pBdr><w:bottom w:val="single" w:sz="4" w:space="0" w:color="000000"/></w:pBdr>
        <w:spacing w:before="0" w:after="200" w:line="1" w:lineRule="exact"/>
        <w:ind w:right="' . (10590 - $width) . '"/>
    </w:pPr>
    <w:r><w:t></w:t></w:r>
    </w:p>';
    }

    private function spacer(int $height): string
    {
        return '<w:p>
    <w:pPr>
        <w:spacing w:before="0" w:after="0" w:line="' . $height . '" w:lineRule="exact"/>
    </w:pPr>
    <w:r><w:t></w:t></w:r>
    </w:p>';
    }

    private function pageBreak(): string
    {
        return '<w:p>
  <w:pPr><w:spacing w:before="0" w:after="0"/></w:pPr>
  <w:r><w:br w:type="page"/></w:r>
</w:p>';
    }

    private function shouldBreakBeforeSummary(array $data): bool
    {
        $items = $data['items'] ?? [];

        if ($items === []) {
            return false;
        }

        $tableHeight = self::TABLE_ROW_HEIGHT + self::TABLE_HEADER_BODY_GAP;

        foreach ($items as $item) {
            $tableHeight += $this->estimatedTableRowHeight($item);
        }

        $firstPageCapacity = max(0, self::PAGE_BODY_HEIGHT - $this->estimatedContentBeforeTableHeight($data));
        $subsequentPageCapacity = self::PAGE_BODY_HEIGHT;

        if ($tableHeight <= $firstPageCapacity) {
            $remaining = $firstPageCapacity - $tableHeight;
        } else {
            $overflow = $tableHeight - $firstPageCapacity;
            $usedOnLastPage = $overflow % $subsequentPageCapacity;
            $remaining = $usedOnLastPage === 0 ? 0 : $subsequentPageCapacity - $usedOnLastPage;
        }

        return $remaining < ($this->estimatedSummaryHeight($data['summary'] ?? []) + self::SUMMARY_PAGE_RESERVE);
    }

    private function estimatedContentBeforeTableHeight(array $data): int
    {
        $height = $this->pxToTwips((int) ($data['spacing_top'] ?? 20));
        $height += 335; // customer name
        $height += 275; // address
        $height += 265; // city
        $height += 210; // horizontal rule
        $height += 310; // UID/date row

        if (trim((string) ($data['bvh'] ?? '')) !== '') {
            $height += 495;
        }

        if (trim((string) ($data['auftragsnr'] ?? '')) !== '') {
            $height += 295;
        }

        return $height + 335; // Angebot title
    }

    private function estimatedTableRowHeight(array $row): int
    {
        $description = (string) ($row[0] ?? '');
        $lineCount = max(1, (int) ceil(mb_strlen($description) / 64));

        return max(self::TABLE_ROW_HEIGHT, 220 + ($lineCount * 245));
    }

    private function estimatedSummaryHeight(array $summary): int
    {
        $rows = 2;

        foreach ($summary['adjustments'] ?? [] as $adjustment) {
            $rows += $this->shouldShowRunningTotal($adjustment) ? 2 : 1;
        }

        return 180 + ($rows * 310) + 260;
    }

    private function shouldShowRunningTotal(array $adjustment): bool
    {
        if (empty($adjustment['running_total'])) {
            return false;
        }

        return ! str_contains(strtolower((string) ($adjustment['label'] ?? '')), 'mwst');
    }

    private function logoParagraph(): string
    {
        $cx = 96 * self::EMU_PER_PIXEL;
        $cy = 115 * self::EMU_PER_PIXEL;

        return '<w:p>
  <w:pPr><w:jc w:val="right"/><w:spacing w:after="0"/></w:pPr>
  <w:r>
    <w:drawing>
      <wp:inline distT="0" distB="0" distL="0" distR="0">
        <wp:extent cx="' . $cx . '" cy="' . $cy . '"/>
        <wp:docPr id="1" name="VA Estriche Logo"/>
        <a:graphic>
          <a:graphicData uri="http://schemas.openxmlformats.org/drawingml/2006/picture">
            <pic:pic>
              <pic:nvPicPr><pic:cNvPr id="0" name="logo.png"/><pic:cNvPicPr/></pic:nvPicPr>
              <pic:blipFill><a:blip r:embed="rIdLogo"/><a:stretch><a:fillRect/></a:stretch></pic:blipFill>
              <pic:spPr><a:xfrm><a:off x="0" y="0"/><a:ext cx="' . $cx . '" cy="' . $cy . '"/></a:xfrm><a:prstGeom prst="rect"><a:avLst/></a:prstGeom></pic:spPr>
            </pic:pic>
          </a:graphicData>
        </a:graphic>
      </wp:inline>
    </w:drawing>
  </w:r>
</w:p>';
    }

    private function headerHorizontalLine(): string
    {
        $headerWidth = 5600;

        return '<w:p>
  <w:pPr>
    <w:pBdr><w:bottom w:val="single" w:sz="4" w:space="0" w:color="C8C8C8"/></w:pBdr>
    <w:spacing w:before="180" w:after="0" w:line="1" w:lineRule="exact"/>
    <w:ind w:right="' . (10590 - $headerWidth) . '"/>
  </w:pPr>
  <w:r><w:t></w:t></w:r>
</w:p>';
    }

    private function runStyle(array $options = []): string
    {
        $size = (int) ($options['size'] ?? 22);
        $bold = ! empty($options['bold']) ? '<w:b/>' : '';
        $italic = ! empty($options['italic']) ? '<w:i/>' : '';
        $underline = ! empty($options['underline']) ? '<w:u w:val="single"/>' : '';
        $color = ! empty($options['color']) ? '<w:color w:val="' . $options['color'] . '"/>' : '';

        return '<w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/>'
            . $bold
            . $italic
            . $underline
            . $color
            . '<w:sz w:val="' . $size . '"/><w:szCs w:val="' . $size . '"/>';
    }

    private function header(bool $hasLogo): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<w:hdr xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships" xmlns:wp="http://schemas.openxmlformats.org/drawingml/2006/wordprocessingDrawing" xmlns:a="http://schemas.openxmlformats.org/drawingml/2006/main" xmlns:pic="http://schemas.openxmlformats.org/drawingml/2006/picture">
  ' . $this->companyInfoTable($hasLogo) . '
</w:hdr>';
    }

    private function footer(bool $showPageNumbers, string $bankDetails): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<w:ftr xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main">
  ' . ($showPageNumbers ? $this->pageNumberParagraph() : $this->paragraph('', ['after' => 0])) . '
  ' . $this->paragraph($bankDetails, [
            'align' => 'center',
            'size' => 16,
            'before' => 0,
            'after' => 0,
        ]) . '
</w:ftr>';
    }

    private function pageNumberParagraph(): string
    {
        return '<w:p>
  <w:pPr><w:jc w:val="right"/><w:spacing w:before="0" w:after="0"/></w:pPr>
  <w:r><w:rPr>' . $this->runStyle(['size' => 18]) . '</w:rPr><w:fldChar w:fldCharType="begin"/></w:r>
  <w:r><w:rPr>' . $this->runStyle(['size' => 18]) . '</w:rPr><w:instrText xml:space="preserve"> PAGE </w:instrText></w:r>
  <w:r><w:rPr>' . $this->runStyle(['size' => 18]) . '</w:rPr><w:fldChar w:fldCharType="separate"/></w:r>
  <w:r><w:rPr>' . $this->runStyle(['size' => 18]) . '</w:rPr><w:t>1</w:t></w:r>
  <w:r><w:rPr>' . $this->runStyle(['size' => 18]) . '</w:rPr><w:fldChar w:fldCharType="end"/></w:r>
  <w:r><w:rPr>' . $this->runStyle(['size' => 18]) . '</w:rPr><w:t>/</w:t></w:r>
  <w:r><w:rPr>' . $this->runStyle(['size' => 18]) . '</w:rPr><w:fldChar w:fldCharType="begin"/></w:r>
  <w:r><w:rPr>' . $this->runStyle(['size' => 18]) . '</w:rPr><w:instrText xml:space="preserve"> NUMPAGES </w:instrText></w:r>
  <w:r><w:rPr>' . $this->runStyle(['size' => 18]) . '</w:rPr><w:fldChar w:fldCharType="separate"/></w:r>
  <w:r><w:rPr>' . $this->runStyle(['size' => 18]) . '</w:rPr><w:t>1</w:t></w:r>
  <w:r><w:rPr>' . $this->runStyle(['size' => 18]) . '</w:rPr><w:fldChar w:fldCharType="end"/></w:r>
</w:p>';
    }

    private function contentTypes(bool $hasLogo): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">
  <Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>
  <Default Extension="xml" ContentType="application/xml"/>
  ' . ($hasLogo ? '<Default Extension="png" ContentType="image/png"/>' : '') . '
  <Override PartName="/word/document.xml" ContentType="application/vnd.openxmlformats-officedocument.wordprocessingml.document.main+xml"/>
  <Override PartName="/word/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.wordprocessingml.styles+xml"/>
  <Override PartName="/word/header1.xml" ContentType="application/vnd.openxmlformats-officedocument.wordprocessingml.header+xml"/>
  <Override PartName="/word/footer1.xml" ContentType="application/vnd.openxmlformats-officedocument.wordprocessingml.footer+xml"/>
</Types>';
    }

    private function rootRels(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
  <Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="word/document.xml"/>
</Relationships>';
    }

    private function documentRels(bool $hasLogo): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
  <Relationship Id="rIdStyles" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/>
  <Relationship Id="rIdHeader1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/header" Target="header1.xml"/>
  <Relationship Id="rIdFooter1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/footer" Target="footer1.xml"/>
</Relationships>';
    }

    private function headerRels(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
  <Relationship Id="rIdLogo" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/image" Target="media/logo.png"/>
</Relationships>';
    }

    private function styles(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<w:styles xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main">
  <w:docDefaults>
    <w:rPrDefault><w:rPr>' . $this->runStyle() . '</w:rPr></w:rPrDefault>
    <w:pPrDefault><w:pPr><w:spacing w:after="40"/></w:pPr></w:pPrDefault>
  </w:docDefaults>
</w:styles>';
    }

    private function e(string $value): string
    {
        return htmlspecialchars($value, ENT_XML1 | ENT_COMPAT, 'UTF-8');
    }

    private function mmToTwips(float $mm): int
    {
        return (int) round($mm * self::TWIPS_PER_MM);
    }

    private function pxToTwips(int $px): int
    {
        return (int) round($px * 15);
    }

    private function demoData(): array
    {
        return [
            'customer_name' => 'N.N.A Name der Firma GmbH',
            'address' => 'Benedik Schellinger Straße 57',
            'ort' => '1150 Wien',
            'uid' => '0123456789',
            'date' => '21.05.2025',
            'bvh' => '8888 Ort, Straße 11',
            'auftragsnr' => '',
            'number' => 'A-001',
            'ausfuehrungszeit' => 'KW: 32/25',
            'spacing_top' => 20,
            'use_tax' => false,
            'bank_details' => self::DEFAULT_BANK_DETAILS,
            'note_html' => '',
            'items' => [
                ['Zementestrich E 300, etc., etc. etc.', '2.000,00 m²', 'Pauschale', '100.000,00'],
                ['Zementestrich E 300, etc., etc. etc.', '2.000,00 m²', 'Pauschale', '100.000,00'],
                ['Zementestrich E 300, etc., etc. etc. Paushale, Deckung Srucklass Zumentresriche', '2.000,00 m²', 'Pauschale', '100.000,00'],
            ],
            'summary' => [
                'subtotal' => '100.000,00',
                'adjustments' => [
                    ['label' => '- 7% Nachlass', 'amount' => '7.000,00', 'running_total' => '93.000,00'],
                    ['label' => '- 10% Deckungsrücklass', 'amount' => '9.300,00', 'running_total' => '83.700,00'],
                ],
                'total' => '83.700,00',
            ],
        ];
    }
}
