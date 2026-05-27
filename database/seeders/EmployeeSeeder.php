<?php

namespace Database\Seeders;

use App\Models\Employee;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        $employees = [
            [
                'employee_number' => 'MA-001',
                'first_name' => 'Marko',
                'last_name' => 'Petrović',
                'phone' => '+43 660 100 200',
                'email' => 'marko.petrovic@example.com',
                'address' => 'Favoritenstraße 120, 1100 Wien',
                'birth_date' => '1988-04-14',
                'nationality' => 'Serbien',
                'position' => 'Estrichleger',
                'entry_date' => '2026-05-01',
                'contract_type' => 'full_time',
                'hourly_wage' => 18.50,
                'status' => 'active',
                'notes' => 'Erfahrung mit Baustellenkoordination und Maschinen.',
            ],
            [
                'employee_number' => 'MA-002',
                'first_name' => 'Nikola',
                'last_name' => 'Jovanović',
                'phone' => '+43 676 220 310',
                'email' => 'nikola.jovanovic@example.com',
                'address' => 'Simmeringer Hauptstraße 88, 1110 Wien',
                'birth_date' => '1992-09-03',
                'nationality' => 'Bosnien und Herzegowina',
                'position' => 'Bauhelfer',
                'entry_date' => '2026-04-15',
                'contract_type' => 'full_time',
                'hourly_wage' => 16.80,
                'status' => 'active',
                'notes' => null,
            ],
            [
                'employee_number' => 'MA-003',
                'first_name' => 'Aleksandar',
                'last_name' => 'Ilić',
                'phone' => '+43 699 330 440',
                'email' => 'aleksandar.ilic@example.com',
                'address' => 'Brünner Straße 45, 1210 Wien',
                'birth_date' => '1985-12-21',
                'nationality' => 'Kroatien',
                'position' => 'Vorarbeiter',
                'entry_date' => '2026-03-01',
                'contract_type' => 'full_time',
                'hourly_wage' => 22.00,
                'status' => 'active',
                'notes' => 'Kann kleine Teams selbstständig führen.',
            ],
            [
                'employee_number' => 'MA-004',
                'first_name' => 'Milan',
                'last_name' => 'Stanković',
                'phone' => '+43 664 550 660',
                'email' => 'milan.stankovic@example.com',
                'address' => 'Linzer Straße 210, 1140 Wien',
                'birth_date' => '1996-02-08',
                'nationality' => 'Serbien',
                'position' => 'Bauhelfer',
                'entry_date' => '2026-05-10',
                'contract_type' => 'part_time',
                'hourly_wage' => 15.90,
                'status' => 'vacation',
                'notes' => null,
            ],
            [
                'employee_number' => 'MA-005',
                'first_name' => 'Dejan',
                'last_name' => 'Kovač',
                'phone' => '+43 681 770 880',
                'email' => 'dejan.kovac@example.com',
                'address' => 'Hauptplatz 12, Wiener Neustadt',
                'birth_date' => '1990-06-17',
                'nationality' => 'Slowakei',
                'position' => 'Fahrer',
                'entry_date' => '2026-02-20',
                'contract_type' => 'full_time',
                'hourly_wage' => 17.40,
                'status' => 'sick',
                'notes' => 'Führerschein B und C vorhanden.',
            ],
        ];

        foreach ($employees as $employee) {
            Employee::updateOrCreate(
                ['employee_number' => $employee['employee_number']],
                $employee
            );
        }
    }
}
