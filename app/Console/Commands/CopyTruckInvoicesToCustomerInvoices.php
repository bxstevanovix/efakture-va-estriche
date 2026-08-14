<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CopyTruckInvoicesToCustomerInvoices extends Command
{
    protected $signature = 'customer-invoices:copy-truck-invoices
        {--source-database=va-estriche : Source database that contains truck_invoices}
        {--target-database= : Target database; defaults to DB_DATABASE from .env}
        {--chunk=500 : Number of records copied per batch}
        {--truncate : Truncate target customer_invoices table before copying}';

    protected $description = 'Copy truck_invoices from old VA Estriche database into customer_invoices.';

    public function handle(): int
    {
        $sourceDatabase = trim((string) $this->option('source-database'));
        $targetDatabase = trim((string) ($this->option('target-database') ?: config('database.connections.'.config('database.default').'.database')));
        $chunkSize = max(1, (int) $this->option('chunk'));

        if ($sourceDatabase === '' || $targetDatabase === '') {
            $this->error('Source i target database moraju biti definisani.');

            return self::FAILURE;
        }

        $source = DB::table(DB::raw($this->qualifiedTable($sourceDatabase, 'truck_invoices')));
        $target = DB::table(DB::raw($this->qualifiedTable($targetDatabase, 'customer_invoices')));
        $firmeTable = $this->qualifiedTable($targetDatabase, 'firme');
        $total = (clone $source)->count();

        if ($total === 0) {
            $this->warn("Nema redova za kopiranje iz {$sourceDatabase}.truck_invoices.");

            return self::SUCCESS;
        }

        if ($this->option('truncate')) {
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            DB::statement('TRUNCATE TABLE '.$this->qualifiedTable($targetDatabase, 'customer_invoices'));
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
            $this->warn("Target tabela {$targetDatabase}.customer_invoices je obrisana prije kopiranja.");
        }

        $copied = 0;
        $missingCompanies = 0;
        $bar = $this->output->createProgressBar($total);
        $bar->start();

        (clone $source)
            ->select([
                'id',
                'id_invoice',
                'text',
                'company',
                'status',
                'price',
                'price_part',
                'debt',
                'currency',
                'pdf',
                'date_start',
                'date_end',
                'date_done',
                'created_at',
                'updated_at',
                'deleted_at',
                'address',
                'square_meters',
            ])
            ->orderBy('id')
            ->chunkById($chunkSize, function ($invoices) use ($target, $firmeTable, &$copied, &$missingCompanies, $bar) {
                $oldCompanyIds = $invoices
                    ->pluck('company')
                    ->filter(fn ($id) => $id !== null && $id !== '')
                    ->unique()
                    ->values();

                $companyMap = $oldCompanyIds->isEmpty()
                    ? collect()
                    : DB::table(DB::raw($firmeTable))
                        ->join($target->getConnection()->getDatabaseName().'.legacy_company_mappings as mappings', 'mappings.firma_id', '=', 'firme.id')
                        ->where('mappings.source', 'customer')
                        ->whereIn('mappings.legacy_id', $oldCompanyIds)
                        ->pluck('firme.id', 'mappings.legacy_id');

                $rows = $invoices->map(function ($invoice) use ($companyMap, &$missingCompanies) {
                    $company = null;

                    if ($invoice->company !== null && $invoice->company !== '') {
                        $company = $companyMap->get($invoice->company);

                        if ($company === null) {
                            $missingCompanies++;
                        }
                    }

                    return [
                        'legacy_id' => $invoice->id,
                        'id_invoice' => $invoice->id_invoice,
                        'text' => $invoice->text,
                        'company' => $company,
                        'status' => $invoice->status,
                        'price' => $invoice->price,
                        'price_part' => $invoice->price_part,
                        'debt' => $invoice->debt,
                        'currency' => $invoice->currency,
                        'square_meters' => $invoice->square_meters,
                        'pdf' => $invoice->pdf,
                        'date_start' => $invoice->date_start,
                        'date_end' => $invoice->date_end,
                        'date_done' => $invoice->date_done,
                        'created_at' => $invoice->created_at,
                        'updated_at' => $invoice->updated_at,
                        'deleted_at' => $invoice->deleted_at,
                        'address' => $invoice->address,
                    ];
                })->all();

                $target->upsert($rows, ['legacy_id'], [
                    'id_invoice',
                    'text',
                    'company',
                    'status',
                    'price',
                    'price_part',
                    'debt',
                    'currency',
                    'square_meters',
                    'pdf',
                    'date_start',
                    'date_end',
                    'date_done',
                    'created_at',
                    'updated_at',
                    'deleted_at',
                    'address',
                ]);

                $copied += count($rows);
                $bar->advance(count($rows));
            }, 'id', 'id');

        $bar->finish();
        $this->newLine(2);
        $this->info("Kopirano/upsertovano {$copied} faktura iz {$sourceDatabase}.truck_invoices u {$targetDatabase}.customer_invoices.");

        if ($missingCompanies > 0) {
            $this->warn("Za {$missingCompanies} faktura nije pronađena legacy mapa firme, pa je company upisan kao null.");
        }

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
