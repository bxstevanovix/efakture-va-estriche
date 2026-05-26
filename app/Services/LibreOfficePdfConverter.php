<?php

namespace App\Services;

use RuntimeException;
use Symfony\Component\Process\ExecutableFinder;
use Symfony\Component\Process\Process;
use Throwable;

class LibreOfficePdfConverter
{
    private const DEFAULT_POOL_SIZE = 3;
    private const DEFAULT_LOCK_TIMEOUT = 120;
    private const DEFAULT_PROCESS_TIMEOUT = 60;

    public function convert(string $docxPath, string $outputDir, ?string $pdfPath = null): string
    {
        $soffice = $this->findSofficeBinary();

        if (! $soffice) {
            throw new RuntimeException('LibreOffice/soffice nije pronadjen.');
        }

        if (! is_file($docxPath)) {
            throw new RuntimeException('DOCX fajl nije pronadjen za PDF konverziju.');
        }

        $this->ensureDirectory($outputDir);

        $pdfPath ??= $outputDir . '/' . pathinfo($docxPath, PATHINFO_FILENAME) . '.pdf';

        return $this->withProfile(function (string $profileDir) use ($soffice, $outputDir, $docxPath, $pdfPath) {
            try {
                return $this->runConversion($soffice, $profileDir, $outputDir, $docxPath, $pdfPath);
            } catch (Throwable $firstException) {
                $this->resetProfile($profileDir);

                try {
                    return $this->runConversion($soffice, $profileDir, $outputDir, $docxPath, $pdfPath);
                } catch (Throwable $retryException) {
                    throw new RuntimeException(
                        trim(
                            'DOCX to PDF konverzija nije uspjela ni nakon resetovanja LibreOffice profila. '
                            . 'Prvi pokusaj: ' . $firstException->getMessage()
                            . ' Drugi pokusaj: ' . $retryException->getMessage()
                        ),
                        0,
                        $retryException
                    );
                }
            }
        });
    }

    private function runConversion(
        string $soffice,
        string $profileDir,
        string $outputDir,
        string $docxPath,
        string $pdfPath
    ): string {
        $this->ensureDirectory($profileDir);

        if (file_exists($pdfPath)) {
            @unlink($pdfPath);
        }

        if (file_exists($pdfPath)) {
            throw new RuntimeException('Postojeci PDF fajl nije moguce ukloniti prije konverzije.');
        }

        $process = new Process([
            $soffice,
            '-env:UserInstallation=' . $this->profileUri($profileDir),
            '--headless',
            '--convert-to',
            'pdf',
            '--outdir',
            $outputDir,
            $docxPath,
        ]);
        $process->setTimeout($this->processTimeout());
        $process->run();

        if (! $process->isSuccessful() || ! is_file($pdfPath)) {
            $details = trim($process->getErrorOutput() ?: $process->getOutput());
            $message = 'DOCX to PDF konverzija nije uspjela preko LibreOffice-a'
                . ' (' . basename($soffice) . ', exit code ' . ($process->getExitCode() ?? 'n/a') . ').';

            throw new RuntimeException(trim($message . ' ' . $details));
        }

        return $pdfPath;
    }

    private function withProfile(callable $callback): string
    {
        $baseDir = storage_path('app/libreoffice-profiles');
        $this->ensureDirectory($baseDir);

        $poolSize = $this->poolSize();
        $deadline = microtime(true) + $this->lockTimeout();

        do {
            for ($index = 1; $index <= $poolSize; $index++) {
                $lockPath = $baseDir . '/profile-' . $index . '.lock';
                $lockHandle = fopen($lockPath, 'c');

                if (! $lockHandle) {
                    throw new RuntimeException('LibreOffice profile lock nije moguce otvoriti.');
                }

                if (! flock($lockHandle, LOCK_EX | LOCK_NB)) {
                    fclose($lockHandle);
                    continue;
                }

                $profileDir = $baseDir . '/profile-' . $index;

                try {
                    $this->ensureDirectory($profileDir);

                    return $callback($profileDir);
                } finally {
                    flock($lockHandle, LOCK_UN);
                    fclose($lockHandle);
                }
            }

            usleep(100000);
        } while (microtime(true) < $deadline);

        throw new RuntimeException('LibreOffice profile pool je zauzet. Pokusajte ponovo za nekoliko sekundi.');
    }

    private function findSofficeBinary(): ?string
    {
        $candidates = array_filter([
            env('SOFFICE_PATH'),
            (new ExecutableFinder())->find('soffice'),
            (new ExecutableFinder())->find('libreoffice'),
            '/Applications/LibreOffice.app/Contents/MacOS/soffice',
        ]);

        foreach ($candidates as $candidate) {
            if (is_executable($candidate)) {
                return $candidate;
            }
        }

        return null;
    }

    private function profileUri(string $profileDir): string
    {
        $path = str_replace('\\', '/', $profileDir);
        $segments = array_map('rawurlencode', explode('/', ltrim($path, '/')));

        return 'file:///' . implode('/', $segments);
    }

    private function poolSize(): int
    {
        return max(1, min(10, (int) env('LIBREOFFICE_PROFILE_POOL_SIZE', self::DEFAULT_POOL_SIZE)));
    }

    private function lockTimeout(): int
    {
        return max(10, (int) env('LIBREOFFICE_PROFILE_LOCK_TIMEOUT', self::DEFAULT_LOCK_TIMEOUT));
    }

    private function processTimeout(): int
    {
        return max(10, (int) env('LIBREOFFICE_PROCESS_TIMEOUT', self::DEFAULT_PROCESS_TIMEOUT));
    }

    private function ensureDirectory(string $directory): void
    {
        if (is_dir($directory)) {
            return;
        }

        if (! mkdir($directory, 0775, true) && ! is_dir($directory)) {
            throw new RuntimeException('Direktorij nije moguce kreirati: ' . $directory);
        }
    }

    private function resetProfile(string $profileDir): void
    {
        $this->deleteDirectory($profileDir);
        $this->ensureDirectory($profileDir);
    }

    private function deleteDirectory(string $directory): void
    {
        if (! is_dir($directory)) {
            return;
        }

        foreach (scandir($directory) ?: [] as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }

            $path = $directory . '/' . $item;

            if (is_link($path) || is_file($path)) {
                @unlink($path);
                continue;
            }

            if (is_dir($path)) {
                $this->deleteDirectory($path);
            }
        }

        @rmdir($directory);
    }
}
