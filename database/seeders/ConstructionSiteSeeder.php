<?php

namespace Database\Seeders;

use App\Models\ConstructionSite;
use Illuminate\Database\Seeder;

class ConstructionSiteSeeder extends Seeder
{
    public function run(): void
    {
        $sites = [
            [
                'name' => 'Wien 22 - Wohnbau Donaustadt',
                'address' => 'Wagramer Straße 120, 1220 Wien',
                'customer_name' => 'Donaustadt Projekt GmbH',
                'start_date' => '2026-05-01',
                'status' => 'active',
            ],
            [
                'name' => 'Wien 10 - Sanierung Favoriten',
                'address' => 'Favoritenstraße 180, 1100 Wien',
                'customer_name' => 'Altbau Service GmbH',
                'start_date' => '2026-04-10',
                'status' => 'active',
            ],
            [
                'name' => 'Baden Zentrum - Neubau',
                'address' => 'Erzherzog-Rainer-Ring 8, 2500 Baden',
                'customer_name' => 'Baden Immobilien GmbH',
                'start_date' => '2026-05-15',
                'status' => 'active',
            ],
            [
                'name' => 'Mödling - Reihenhäuser',
                'address' => 'Hauptstraße 44, 2340 Mödling',
                'customer_name' => 'Mödling Bauprojekt GmbH',
                'start_date' => '2026-06-01',
                'status' => 'planned',
            ],
        ];

        foreach ($sites as $site) {
            ConstructionSite::updateOrCreate(
                ['name' => $site['name']],
                $site
            );
        }
    }
}
