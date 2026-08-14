<?php

namespace App\Support;

use Illuminate\Support\Facades\DB;

final class LegacyCompanyImporter
{
    public static function import(object $company, string $source, string $targetDatabase, ?string $uid = null): int
    {
        $mapping = DB::table("{$targetDatabase}.legacy_company_mappings")
            ->where('source', $source)
            ->where('legacy_id', $company->id)
            ->first();

        $firmaId = $mapping?->firma_id;

        if ($firmaId === null) {
            $firmaId = DB::table("{$targetDatabase}.firme")
                ->whereRaw('LOWER(TRIM(name)) = ?', [mb_strtolower(trim($company->name))])
                ->value('id');
        }

        $values = [
            'name' => trim($company->name),
            'address' => self::value($company->address),
            'jib' => self::value($company->jib),
            'uid' => self::value($uid),
            'ort' => null,
            'phone' => self::value($company->phone),
            'email' => self::value($company->email),
            'currency' => self::value($company->currency),
            'deleted_at' => $company->deleted_at,
            'created_at' => $company->created_at,
            'updated_at' => $company->updated_at,
        ];

        if ($firmaId === null) {
            $firmaId = DB::table("{$targetDatabase}.firme")->insertGetId($values);
        } else {
            $existing = DB::table("{$targetDatabase}.firme")->where('id', $firmaId)->first();
            $updates = [];

            foreach ($values as $column => $value) {
                if ($column === 'updated_at' || (self::value($existing->{$column} ?? null) === null && $value !== null)) {
                    $updates[$column] = $value;
                }
            }

            if ($updates !== []) {
                DB::table("{$targetDatabase}.firme")->where('id', $firmaId)->update($updates);
            }
        }

        DB::table("{$targetDatabase}.legacy_company_mappings")->updateOrInsert(
            ['source' => $source, 'legacy_id' => $company->id],
            ['firma_id' => $firmaId, 'updated_at' => now(), 'created_at' => now()]
        );

        return $firmaId;
    }

    private static function value(mixed $value): mixed
    {
        if (! is_string($value)) {
            return $value;
        }

        $value = trim($value);

        return $value === '' || $value === '-' ? null : $value;
    }
}
