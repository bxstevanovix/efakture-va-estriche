<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AngeboteController;
use App\Http\Controllers\RechnungController;
use App\Http\Controllers\FirmaController;
use App\Http\Controllers\CustomerInvoicesController;
use App\Http\Controllers\DashController;
use App\Http\Controllers\SupplierInvoicesController;
use App\Http\Controllers\FileManagerController;
use App\Http\Controllers\PDFController;

Route::get('/', function () {
    return auth()->check()
        ? redirect('/dashboard')
        : redirect('/login');
});

Route::get('/invoice/download', [AngeboteController::class, 'downloadPdf'])->name('angebote.download');

Route::get('/dashboard', [DashController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');
Route::post('/dashboard/datatable-customers', [DashController::class, 'datatableCustomers'])->name('datatable_customers');
Route::post('/dashboard/datatable-suppliers', [DashController::class, 'datatableSuppliers'])->name('datatable_suppliers');

Route::middleware('auth')->group(function () {
    
    //IZLAZNE FAKTURE
    Route::name('customer-invoices.')->prefix('customer-invoices')->group(function () {
        Route::get('/', [CustomerInvoicesController::class, 'index'])->name('index');
        Route::post('/datatable', [CustomerInvoicesController::class, 'datatable'])->name('datatable');
        Route::get('/create', [CustomerInvoicesController::class, 'create'])->name('create');
        Route::post('/create', [CustomerInvoicesController::class, 'store']);
        Route::get('/{entity}/edit', [CustomerInvoicesController::class, 'edit'])->name('edit');
        Route::post('/{entity}/edit', [CustomerInvoicesController::class, 'update']);
        Route::post('/{entity}/delete', [CustomerInvoicesController::class, 'delete'])->name('delete');
        Route::post('/paid', [CustomerInvoicesController::class, 'paid'])->name('paid');
        Route::get('/summary', [CustomerInvoicesController::class, 'getSummary'])->name('summary');
        Route::get('/pdf/{id}', [CustomerInvoicesController::class, 'pdfFile'])->name('pdf');
        Route::get('/view/{id}', [CustomerInvoicesController::class, 'viewPdf'])->name('view');
        Route::post('/upload-pdf', [CustomerInvoicesController::class, 'uploadPdf'])->name('upload_pdf');
        Route::delete('/{entity}/delete-pdf', [CustomerInvoicesController::class, 'deletePdf'])->name('delete_pdf');

        Route::post('/invoice-pdfs/store', [CustomerInvoicesController::class, 'uploadMorePdf'])->name('upload_more_pdfs');

        Route::get('/autocomplete/address', [CustomerInvoicesController::class, 'autocompleteAddress']);

        // IZVESTAJI
        Route::get('/reports', [CustomerInvoicesController::class, 'reports'])->name('reports');
        Route::post('/reports/datatable', [CustomerInvoicesController::class, 'reportsDatatable'])->name('reports_datatable');
        Route::post('/reports/generate-pdf', [CustomerInvoicesController::class, 'generatePDF'])->name('generate_pdf');
    });

    //ULAZNE FAKTURE
    Route::name('supplier-invoices.')->prefix('supplier-invoices')->group(function () {
        Route::get('/', [SupplierInvoicesController::class, 'index'])->name('index');
        Route::post('/datatable', [SupplierInvoicesController::class, 'datatable'])->name('datatable');
        Route::get('/create', [SupplierInvoicesController::class, 'create'])->name('create');
        Route::post('/create', [SupplierInvoicesController::class, 'store']);
        Route::get('/{entity}/edit', [SupplierInvoicesController::class, 'edit'])->name('edit');
        Route::post('/{entity}/edit', [SupplierInvoicesController::class, 'update']);
        Route::post('/{entity}/delete', [SupplierInvoicesController::class, 'delete'])->name('delete');
        Route::post('/paid', [SupplierInvoicesController::class, 'paid'])->name('paid');
        Route::get('/summary', [SupplierInvoicesController::class, 'getSummary'])->name('summary');
        Route::get('/pdf/{id}', [SupplierInvoicesController::class, 'pdfFile'])->name('pdf');
        Route::get('/view/{id}', [SupplierInvoicesController::class, 'viewPdf'])->name('view');
        Route::post('/upload-pdf', [SupplierInvoicesController::class, 'uploadPdf'])->name('upload_pdf');
        Route::delete('/{entity}/delete-pdf', [SupplierInvoicesController::class, 'deletePdf'])->name('delete_pdf');

        Route::get('/autocomplete/address', [SupplierInvoicesController::class, 'autocompleteAddress']);

        // IZVESTAJI
        Route::get('/reports', [SupplierInvoicesController::class, 'reports'])->name('reports');
        Route::post('/reports/datatable', [SupplierInvoicesController::class, 'reportsDatatable'])->name('reports_datatable');
        Route::post('/reports/generate-pdf', [SupplierInvoicesController::class, 'generatePDF'])->name('generate_pdf');
    });

    //FIRME
    Route::name('firme.')->prefix('/firme')->group(function() {
        Route::get('/', [FirmaController::class, 'index'])->name('index');
        Route::post('datatable', [FirmaController::class, 'datatable'])->name('datatable');
        Route::get('create', [FirmaController::class, 'create'])->name('create');
        Route::post('create', [FirmaController::class, 'store']);
        Route::get('{entity}', [FirmaController::class, 'show'])->name('show');
        Route::get('{entity}/edit', [FirmaController::class, 'edit'])->name('edit');
        Route::post('{entity}/edit', [FirmaController::class, 'update']);
        Route::post('{entity}/delete', [FirmaController::class, 'delete'])->name('delete');
    });

    // ANGEBOTE
    Route::name('angebote.')->prefix('/angebote')->group(function() {
        Route::get('/', [AngeboteController::class, 'index'])->name('index');
        Route::post('/datatable', [AngeboteController::class, 'datatable'])->name('datatable');
        Route::post('/create', [AngeboteController::class, 'save'])->name('store');
        Route::post('/{entity}/delete', [AngeboteController::class, 'delete'])->name('delete');
        Route::get('/pdf/{id}', [AngeboteController::class, 'pdfFile'])->name('pdf');
        Route::get('/view/{id}', [AngeboteController::class, 'viewPdf'])->name('view');
        // Autocomplete za firma i adress
        Route::get('/autocomplete/firma', [AngeboteController::class, 'autocompleteFirma']);
        Route::get('/autocomplete/adresa', [AngeboteController::class, 'autocompleteAdress']);
        Route::get('/autocomplete/beschreibung', [AngeboteController::class, 'autocompleteBeschreibung']);
    });

    // RECHNUNG
    Route::name('rechnung.')->prefix('/rechnung')->group(function() {
        Route::get('/', [RechnungController::class, 'index'])->name('index');
        Route::post('/datatable', [RechnungController::class, 'datatable'])->name('datatable');
        Route::post('/create', [RechnungController::class, 'save'])->name('store');
        Route::post('/{entity}/delete', [RechnungController::class, 'delete'])->name('delete');
        Route::get('/pdf/{id}', [RechnungController::class, 'pdfFile'])->name('pdf');
        Route::get('/view/{id}', [RechnungController::class, 'viewPdf'])->name('view');
        // Autocomplete za firma i adress
        Route::get('/autocomplete/firma', [RechnungController::class, 'autocompleteFirma']);
        Route::get('/autocomplete/adress', [RechnungController::class, 'autocompleteAdress']);
        Route::get('/autocomplete/beschreibung', [RechnungController::class, 'autocompleteBeschreibung']);
    });

    // PROFILE
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // FILE MANAGER
    Route::get('/file-list/{company}', [FileManagerController::class, 'fileList'])->name('file-list');
    Route::get('/file-manager/view', [FileManagerController::class, 'viewFile'])->name('filemanager_view');

    // PDF EDITOR
    Route::delete('/invoice-pdfs/{id}', [PDFController::class, 'destroy'])->name('invoice-pdfs.delete');
    // Lista PDF-ova za datu fakturu
    Route::get('/list-invoice-pdfs/{type}/{id}', [PDFController::class, 'list']);
});



require __DIR__.'/auth.php';
