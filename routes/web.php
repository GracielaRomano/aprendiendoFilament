<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PdfController;
use Barryvdh\DomPDF\Facade\Pdf;


Route::get('/', function () {
    return redirect('/personal');
});

Route::get('/pdf/generate/timesheet/{user}', [PdfController::class, 'TimesheetRecords'])->name('pdf.example');