<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Services\DocxAngebotService;
use Symfony\Component\Process\ExecutableFinder;
use Symfony\Component\Process\Process;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('invoice:docx-demo {--pdf : Convert DOCX to PDF with LibreOffice/soffice if available}', function (DocxAngebotService $service) {
    $outputDirectory = storage_path('app/docx-demo');
    $docxPath = $outputDirectory . '/angebot-demo.docx';

    $service->create($docxPath);
    $this->info('DOCX created: ' . $docxPath);

    if (! $this->option('pdf')) {
        $this->line('Za PDF test pokreni: php artisan invoice:docx-demo --pdf');
        return self::SUCCESS;
    }

    $finder = new ExecutableFinder();
    $binary = $finder->find('soffice') ?: $finder->find('libreoffice');

    if (! $binary) {
        $this->warn('LibreOffice/soffice nije pronađen u PATH-u. DOCX je kreiran, ali PDF nije konvertovan.');
        return self::SUCCESS;
    }

    $profileDirectory = $outputDirectory . '/lo-profile';
    $process = new Process([
        $binary,
        '-env:UserInstallation=file://' . $profileDirectory,
        '--headless',
        '--convert-to',
        'pdf',
        '--outdir',
        $outputDirectory,
        $docxPath,
    ]);
    $process->setTimeout(60);
    $process->run();

    if (! $process->isSuccessful()) {
        $this->error($process->getErrorOutput() ?: $process->getOutput());
        return self::FAILURE;
    }

    $this->info('PDF created: ' . $outputDirectory . '/angebot-demo.pdf');
    return self::SUCCESS;
})->purpose('Create a sample DOCX invoice and optionally convert it to PDF with LibreOffice.');
