<?php

use App\Models\Invoice;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\InvoiceDetailController;
use App\Http\Controllers\InvoiceArchiveController;
use App\Http\Controllers\InvoicesReportController;
use App\Http\Controllers\InvoiceAttachmentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});


Route::resources([
    'invoices'             => InvoiceController::class ,
    'sections'             => SectionController::class ,
    'products'             => ProductController::class ,
    'Invoices_attachments' => InvoiceAttachmentController::class ,
    'archive'              => InvoiceArchiveController::class ,
]);
Route::middleware(['auth'])->group(function(){
    Route::resource('/users', UserController::class);
    Route::resource('roles', RoleController::class);
});

//=============================================================
Route::get('section/{id}' , [InvoiceController::class , 'get_products']);
Route::get('InvoicesDetails/{id}' , [InvoiceDetailController::class , 'edit']);
Route::get('download/{invoice_number}/{file_name}' , [InvoiceDetailController::class , 'download_image']);
Route::get('delete_file' , [InvoiceDetailController::class , 'destroy'])->name('delete_file');
Route::get('invoices/show/{id}' , [InvoiceController::class , 'show'])->name('invoices.show');
Route::get('invoice_paid', [InvoiceController::class , 'invoice_paid']);
Route::get('invoice_unpaid', [InvoiceController::class , 'invoice_unpaid']);
Route::get('invoice_partial', [InvoiceController::class , 'invoice_partial'])->name('invoice.partial');
Route::get('/print/{id}' , [ InvoiceController::class , 'print']);
Route::get('export_invoices/', [InvoiceController::class, 'export']);
Route::get('invoices_report' , [InvoicesReportController::class , 'index']);

//==============================================================================
Route::post('status_update/{id}' , [InvoiceController::class , 'status_update'])->name('status.update');
Route::post('Search_invoices' , [ InvoicesReportController::class , 'Search_invoices']);
Route::get('Search_customers_index' , [ InvoicesReportController::class , 'Search_customers_index'])->name('Search_customers_index');
Route::post('Search_customers' , [ InvoicesReportController::class , 'Search_customers'])->name('Search_customers');










Auth::routes();
// Auth::routes(['register'=>false]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/{page}', [AdminController::class , 'index']);

