<?php

use App\Http\Controllers\InvitacionesController;
use App\Http\Controllers\PdfController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', function () {
    return redirect('/admin');
});


Route::get('/download-pdf/{id}', [PdfController::class, 'download'])->name('pdf.download');
Route::get('/send-pdf/{record}', [PdfController::class, 'sendPdf'])->name('send.pdf');

Route::get('/pdf', function () {
    return redirect('/pdf/my_pdf_view');
});

Route::get('/import-emails/{id}', [InvitacionesController::class, 'readEmails'])->name('enviar.invitaciones');
