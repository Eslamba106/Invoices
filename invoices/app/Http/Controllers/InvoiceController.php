<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Invoice;
use App\Models\Section;
use Illuminate\Http\Request;
use App\Models\Invoice_detail;
use App\Exports\InvoicesExport;
use App\Models\invoice_attachment;
use App\Notifications\InvoicePaid;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Notification;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $invoices = Invoice::all();
        return view('invoices.invoices', compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $sections = Section::all();
        return view('invoices.add_invoice' , compact('sections'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        Invoice::create([
            'invoice_number' => $request->invoice_number,
            'invoice_data' => $request->invoice_Date,
            'due_data' => $request->Due_date,
            'product' => $request->product,
            'section_id' => $request->Section,
            'Amount_collection' => $request->Amount_collection,
            'Amount_Commission' => $request->Amount_Commission,
            'discount' => $request->Discount,
            'value_vat' => $request->Value_VAT,
            'rate_vat' => $request->Rate_VAT,
            'total' => $request->Total,
            'status' => 'غير مدفوعة',
            'value_status' => 2,
            'note' => $request->note,
            'user' => Auth::user()->name
        ]);

        $invoice_id = Invoice::latest()->first()->id;
        Invoice_detail::create([
            'invoice_id' => $invoice_id,
            'invoice_number' => $request->invoice_number,
            'product' => $request->product,
            'section' => $request->Section,
            'status' => 'غير مدفوعة',
            'value_status' => 2,
            'note' => $request->note,
            'user' => (Auth::user()->name),
        ]);

        if ($request->hasFile('pic')) {

            $invoice_id = Invoice::latest()->first()->id;
            $image = $request->file('pic');
            $file_name = $image->getClientOriginalName();
            $invoice_number = $request->invoice_number;

            $attachments = new invoice_attachment();
            $attachments->file_name = $file_name;
            $attachments->invoice_number = $invoice_number;
            $attachments->Created_by = Auth::user()->name;
            $attachments->invoice_id = $invoice_id;
            $attachments->save();

            // move pic
            $imageName = $request->pic->getClientOriginalName();
            $request->pic->move(public_path('Attachments/' . $invoice_number), $imageName);
        }

        // notifications code
        $user = auth()->user();
        Notification::send($user, new InvoicePaid($invoice_id));
        
        // event(new MyEventClass('hello world'));


        // sesstion and redirect code
        Session::flash('Add', 'تم اضافة الفاتورة بنجاح');
        return redirect()->route('invoices.index');
    }

    public function show($id)
    {
        $invoices = Invoice::where('id' , $id)->first();
        return view('invoices.payment_status' , compact('invoices'));
    }

    public function edit($id)
    {
        $invoice = Invoice::where('id' , $id)->first();
        $sections= Section::all();
        return view('invoices.edit' , compact(['invoice' , 'sections']));
    }

    public function update(Request $request)
    {
        $invoice = Invoice::where('id' , $request->invoice_id);
        $invoice->update([
            'invoice_number' => $request->invoice_number ,
            'invoice_data'   => $request->invoice_Date,
            'due_data'       => $request->Due_date,
            'product'        => $request->product,
            'section_id'     => $request->Section,
            'Amount_collection' => $request->Amount_collection,
            'Amount_Commission' => $request->Amount_Commission,
            'discount'          => $request->Discount ,
            'value_vat'         => $request->Value_VAT,
            'rate_vat'         => $request->Rate_VAT,
            'total'         => $request->Total,
            'note'         => $request->note,
        ]);
        Session::flash('edit' , 'تم تعديل الفاتورة بنجاح');
        return redirect()->route('invoices.index');
    }

    public function destroy(Request $request)
    {
       $id = $request->invoice_id ;
       $invoice = Invoice::where('id' , $id)->first();
       $details = invoice_attachment::where('invoice_id' , $id)->first();
       $id_page = $request->id_page ;
       if($id_page != 2){
            if(!empty($details->invoice_number)){
            Storage::disk('public_upload')->deleteDirectory($details->invoice_number);
            }
            $invoice->forceDelete();
            Session::flash('delete_invoice');
            return redirect()->route('invoices.index');
       }
       else{
        $invoice->delete();
        Session::flash('archive_invoice');
        return redirect()->route('invoices.index');
       }

    }
    public function get_products($id){
        $products = DB::table('products')->where('section_id' , $id)->pluck('product_name' , 'id');
        return json_encode($products);
    }

    public function status_update($id , Request $request){
        
        $invoices = Invoice::findOrFail($id);

        if ($request->Status === 'مدفوعة') {

            $invoices->update([
                'value_status' => 1,
                'status' => $request->Status,
                'Payment_Date' => $request->Payment_Date,
            ]);

            Invoice_detail::create([
                'invoice_id' => $request->invoice_id,
                'invoice_number' => $request->invoice_number,
                'product' => $request->product,
                'section' => $request->Section,
                'status' => $request->Status,
                'value_status' => 1,
                'note' => $request->note,
                'Payment_Date' => $request->Payment_Date,
                'user' => (Auth::user()->name),
            ]);
        }

        else {
            $invoices->update([
                'value_status' => 3,
                'status' => $request->Status,
                'Payment_Date' => $request->Payment_Date,
            ]);
            Invoice_detail::create([
                'invoice_id' => $request->invoice_id,
                'invoice_number' => $request->invoice_number,
                'product' => $request->product,
                'section' => $request->Section,
                'status' => $request->Status,
                'value_status' => 3,
                'note' => $request->note,
                'Payment_Date' => $request->Payment_Date,
                'user' => (Auth::user()->name),
            ]);
        }
        Session::flash('Status_Update');
        return redirect('/invoices');


    }

    public function invoice_paid(){
        $invoices = Invoice::where('value_status' , 1)->get();
        return view('invoices.invoice_paid' , compact('invoices'));
    }
    public function invoice_unpaid(){
        $invoices = Invoice::where('value_status' , 2)->get();
        return view('invoices.invoice_unpaid' , compact('invoices'));
    }
    public function invoice_partial(){
        $invoices = Invoice::where('value_status' , 3)->get();
        return view('invoices.partialpaid' , compact('invoices'));
    }

    public function print($id){
        $invoices = Invoice::where('id' , $id)->first();
        return view('invoices.print' , compact('invoices'));
    }
    public function export() {
        return Excel::download(new InvoicesExport, 'invoices.xlsx');
    }
}
// pulck fetch columns from table