<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request as Request;
use App\Models\Firma;
use Illuminate\Support\Str;
use App\Models\InvoicePdf;

class PDFController extends Controller
{

    public function destroy($id)
    {
        $pdf = InvoicePdf::findOrFail($id);

        $pdf->deleted_by = auth()->id();
        $pdf->save();

        $pdf->delete();

        return back()->with('success', 'PDF obrisan');
    }

    public function list($type, $id)
    {
        $pdfs = InvoicePdf::where('invoice_type', $type)->where('invoice_id', $id)->get();

        return response()->json($pdfs);
    }

}
