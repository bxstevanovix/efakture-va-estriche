<?php

namespace Database\Seeders;

use App\Models\ConstructionSite;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class EmployeeWorkLogSeeder extends Seeder
{
    public function run(): void
    {
        $employees = Employee::whereIn('employee_number', ['MA-001', 'MA-002', 'MA-003', 'MA-004', 'MA-005'])
            ->get()
            ->keyBy('employee_number');

        $sites = ConstructionSite::whereIn('name', [
            'Wien 22 - Wohnbau Donaustadt',
            'Wien 10 - Sanierung Favoriten',
            'Baden Zentrum - Neubau',
        ])->get()->keyBy('name');

        if ($employees->isEmpty() || $sites->isEmpty()) {
            return;
        }

        $monthStart = Carbon::now()->startOfMonth();

        $logs = [
            ['MA-001', 0, 'active', 'Wien 22 - Wohnbau Donaustadt', '07:00', '16:00', 60, null],
            ['MA-001', 1, 'active', 'Wien 22 - Wohnbau Donaustadt', '07:00', '17:00', 60, null],
            ['MA-001', 2, 'vacation', null, null, null, 0, 'Urlaubstag'],
            ['MA-001', 3, 'active', 'Wien 10 - Sanierung Favoriten', '07:30', '16:30', 45, null],
            ['MA-002', 0, 'active', 'Wien 22 - Wohnbau Donaustadt', '07:00', '15:30', 30, null],
            ['MA-002', 1, 'active', 'Wien 22 - Wohnbau Donaustadt', '07:00', '16:00', 60, null],
            ['MA-002', 2, 'sick', null, null, null, 0, 'Krankenstand'],
            ['MA-003', 0, 'active', 'Baden Zentrum - Neubau', '06:30', '16:30', 60, 'Teamleitung'],
            ['MA-003', 1, 'active', 'Baden Zentrum - Neubau', '06:30', '17:00', 60, 'Materialkoordination'],
            ['MA-004', 0, 'vacation', null, null, null, 0, null],
            ['MA-004', 1, 'vacation', null, null, null, 0, null],
            ['MA-005', 0, 'sick', null, null, null, 0, null],
            ['MA-005', 1, 'sick', null, null, null, 0, null],
        ];

        foreach ($logs as [$employeeNumber, $dayOffset, $status, $siteName, $start, $end, $breakMinutes, $notes]) {
            $employee = $employees[$employeeNumber] ?? null;

            if (! $employee) {
                continue;
            }

            $hours = $status === 'active'
                ? $this->hours($monthStart->copy()->addDays($dayOffset), $start, $end, $breakMinutes)
                : 0;

            $employee->workLogs()->updateOrCreate(
                ['work_date' => $monthStart->copy()->addDays($dayOffset)->toDateString()],
                [
                    'construction_site_id' => $siteName ? ($sites[$siteName]->id ?? null) : null,
                    'status' => $status,
                    'start_time' => $start,
                    'end_time' => $end,
                    'break_minutes' => $breakMinutes,
                    'hours' => $hours,
                    'overtime_hours' => max(0, $hours - 8),
                    'notes' => $notes,
                ]
            );
        }
    }

    private function hours(Carbon $date, string $startTime, string $endTime, int $breakMinutes): float
    {
        $start = Carbon::parse($date->toDateString() . ' ' . $startTime);
        $end = Carbon::parse($date->toDateString() . ' ' . $endTime);

        return round(max(0, $start->diffInMinutes($end) - $breakMinutes) / 60, 2);
    }
}
