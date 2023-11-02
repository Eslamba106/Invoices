<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Invoice_detail;
use App\Models\invoice_attachment;
use Illuminate\Support\Facades\Storage;

class InvoiceDetailController extends Controller
{

    public function index()
    {
        //
    }


    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }


    public function show(Invoice_detail $invoice_detail)
    {
        //
    }

    public function edit($id)
    {
        $invoices = Invoice::where('id',$id)->first();
        $details  = invoice_detail::where('invoice_id' , $id)->get();
        $attachments  = invoice_attachment::where('invoice_id' , $id)->get();
        return view('invoices.invoices_detalis',compact('invoices','details','attachments'));
    }


    public function update(Request $request, Invoice_detail $invoice_detail)
    {
        //
    }

    public function destroy(Request $request)
    {
        $invoices = invoice_attachment::findOrFail($request->id_file);
        $invoices->delete();
        Storage::disk('public_upload')->delete($request->invoice_number.'/'.$request->file_name);
        session()->flash('delete', 'تم حذف المرفق بنجاح');
        return back();
    }

    public function download_image($invoice_number , $file_name){
        $path = public_path('Attachments/'.$invoice_number.'/'.$file_name) ;
        return response()->download($path);
    }
    // public function open_file($invoice_number , $file_name){
    //     // $path = Storage::disk('public_upload')->getDriver()->getAdapter()->applyPathPrefix($invoice_number.'/'.$file_name);
    //     $path = Storage::disk('public_upload');
    //     return response()->download($path);
    // }
}
