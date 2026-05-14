<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request as Request;
use App\Models\Firma;
use Illuminate\Support\Str;

class FileManagerController extends Controller
{

    public function fileList($companyId, Request $request)
    {
        $company = Firma::findOrFail($companyId);
        $folderName = $company->folder_name;

        $path = $request->get('path');

        // ROOT nivo
        if (!$path) {
            return response()->json([
                [
                    'name' => 'Ausgangsrechnungen',
                    'type' => 'Folder',
                    'size' => '-',
                    'date' => '-',
                    'path' => 'ausgangsrechnungen/' . $folderName,
                    'is_dir' => true
                ],
                [
                    'name' => 'Eingangsrechnungen',
                    'type' => 'Folder',
                    'size' => '-',
                    'date' => '-',
                    'path' => 'eingangsrechnungen/' . $folderName,
                    'is_dir' => true
                ]
            ]);
        }

        $fullPath = storage_path('app/public/' . $path);

        if (!is_dir($fullPath)) {
            mkdir($fullPath, 0777, true);
        }

        $items = [];

        foreach (scandir($fullPath) as $item) {
            if ($item == '.' || $item == '..') continue;

            $itemPath = $fullPath . '/' . $item;
            $isDir = is_dir($itemPath);

            $items[] = [
                'name' => $item,
                'type' => $isDir ? 'Folder' : 'Fajl',
                'size' => $isDir ? '-' : round(filesize($itemPath) / 1024, 2) . ' KB',
                'date' => date("d.m.Y H:i", filemtime($itemPath)),
                'path' => $path . '/' . $item,
                'is_dir' => $isDir,
            ];
        }

        return response()->json($items);
    }


    /**
     * ✅ PRIKAZ PDF FAJLA (za login korisnike)
     */
    public function viewFile(Request $request)
{
    $path = $request->get('path');

    if (!$path) {
        abort(404, 'Fajl nije pronađen');
    }

    $fullPath = storage_path('app/public/' . $path);

    if (!file_exists($fullPath)) {
        abort(404, 'Fajl ne postoji');
    }

    // uzmi company slug iz path-a (2-doo-stevanovic)
    $parts = explode('/', $path);
    $companySlug = $parts[1] ?? null;

    return response()->file($fullPath, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'inline; filename="' . $companySlug . '-' . basename($path) . '"',
    ]);
}

}
