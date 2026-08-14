<?php

namespace App\Console\Commands;

use App\Support\LegacyCompanyImporter;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CopyTruckCompaniesProcurementToFirme extends Command
{
    protected $signature = 'firme:copy-truck-companies-procurement
        {--source-database=va-estriche : Source database that contains truck_companies_procurement}
        {--target-database= : Target database; defaults to DB_DATABASE from .env}
        {--chunk=500 : Number of records copied per batch}';

    protected $description = 'Copy truck_companies_procurement from old VA Estriche database into the current firme table.';

    public function handle(): int
    {
        $sourceDatabase = trim((string) $this->option('source-database'));
        $targetDatabase = trim((string) ($this->option('target-database') ?: config('database.connections.'.config('database.default').'.database')));
        $chunkSize = max(1, (int) $this->option('chunk'));

        if ($sourceDatabase === '' || $targetDatabase === '') {
            $this->error('Source i target database moraju biti definisani.');

            return self::FAILURE;
        }

        $source = DB::table(DB::raw($this->qualifiedTable($sourceDatabase, 'truck_companies_procurement')));
        $target = DB::table(DB::raw($this->qualifiedTable($targetDatabase, 'firme')));
        $total = (clone $source)->count();

        if ($total === 0) {
            $this->warn("Nema redova za kopiranje iz {$sourceDatabase}.truck_companies_procurement.");

            return self::SUCCESS;
        }

        $copied = 0;
        $bar = $this->output->createProgressBar($total);
        $bar->start();

        (clone $source)
            ->select([
                'id',
                'name',
                'address',
                'jib',
                'phone',
                'email',
                'currency',
                'deleted_at',
                'created_at',
                'updated_at',
            ])
            ->orderBy('id')
            ->chunkById($chunkSize, function ($companies) use ($target, &$copied, $bar) {
                foreach ($companies as $company) {
                    LegacyCompanyImporter::import($company, 'supplier', $target->getConnection()->getDatabaseName());
                    $copied++;
                    $bar->advance();
                }
            }, 'id', 'id');

        $bar->finish();
        $this->newLine(2);
        $this->info("Obrađeno {$copied} firmi iz {$sourceDatabase}.truck_companies_procurement. Poklopljeni nazivi su spojeni, a stari ID-evi mapirani.");

        return self::SUCCESS;
    }

    private function qualifiedTable(string $database, string $table): string
    {
        return $this->quoteIdentifier($database).'.'.$this->quoteIdentifier($table);
    }

    private function quoteIdentifier(string $identifier): string
    {
        return '`'.str_replace('`', '``', $identifier).'`';
    }
}
