<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\invoice_attachment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;


class InvoiceAttachmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'file_name' => 'mimes:png,jpg,pdf,jpeg',
        ],[
            'file_name.mimes' => 'صيغة الملف يجب ان تكون pdf , jpg , jpeg , png',
        ]);

        $image = $request->file('file_name');
        $file_name = $image->getClientOriginalName();

        $attachment = new invoice_attachment();
        $attachment->file_name =  $file_name;
        $attachment->invoice_number = $request->invoice_number;
        $attachment->invoice_id = $request->invoice_id ;
        $attachment->Created_by = Auth::user()->name ; 
        $attachment->save();

        // move pic 
        $imageName = $request->file_name->getClientOriginalName();
        $request->file_name->move(public_path('Attachments/'.$request->invoice_number) , $imageName);
        Session::flash('Add' , 'تم الاضافة بنجاح');
        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(invoice_attachment $invoice_attachment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(invoice_attachment $invoice_attachment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, invoice_attachment $invoice_attachment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(invoice_attachment $invoice_attachment)
    {
        
    }
}
