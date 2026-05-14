<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class InvoiceSeeder extends Seeder
{
    public function run(): void
    {
        $year = 2026;

        $invoices = [];

        for ($month = 1; $month <= 12; $month++) {

            // 3 plaćene fakture
            for ($i = 1; $i <= 3; $i++) {

                $price = rand(200, 5000);

                $date = Carbon::create($year, $month, rand(1, 25));

                $invoices[] = [
                    'id_invoice'   => rand(100, 999) . '/' . $year,
                    'text'         => 'Plaćena faktura za mjesec ' . $month,
                    'company'      => rand(1, 2),
                    'status'       => 1,
                    'price'        => $price,
                    'price_part'   => 0,
                    'debt'         => $price,
                    'currency'     => 'EUR',
                    'pdf'          => null,
                    'date_start'   => $date,
                    'date_end'     => $date->copy()->addDays(15),
                    'date_done'    => $date->copy()->addDays(rand(1, 10)),
                    'address'      => 'Adresa firme ' . rand(1, 20),
                    'created_at'   => now(),
                    'updated_at'   => now(),
                    'deleted_at'   => null,
                ];
            }

            // 3 neplaćene fakture
            for ($i = 1; $i <= 3; $i++) {

                $price = rand(200, 5000);

                $date = Carbon::create($year, $month, rand(1, 25));

                $invoices[] = [
                    'id_invoice'   => rand(1000, 9999) . '/' . $year,
                    'text'         => 'Neplaćena faktura za mjesec ' . $month,
                    'company'      => rand(1, 2),
                    'status'       => 0,
                    'price'        => $price,
                    'price_part'   => 0,
                    'debt'         => $price,
                    'currency'     => 'EUR',
                    'pdf'          => null,
                    'date_start'   => $date,
                    'date_end'     => $date->copy()->addDays(30),
                    'date_done'    => null,
                    'address'      => 'Adresa firme ' . rand(1, 20),
                    'created_at'   => now(),
                    'updated_at'   => now(),
                    'deleted_at'   => null,
                ];
            }
        }

        DB::table('supplier_invoices')->insert($invoices);
    }
}