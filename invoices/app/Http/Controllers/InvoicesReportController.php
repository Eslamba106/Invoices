<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Section;
use Illuminate\Http\Request;

class InvoicesReportController extends Controller
{
    public function index(){
        return view('reports.invoices_report');
    }
    public function Search_invoices(Request $request){
        $rdio = $request->rdio ;
        // in case search by invoice type
        if($rdio == 1){
            if($request->type && $request->start_at == '' && $request->end_at == ''){
                if($request->type == 'all'){
                    $invoices = Invoice::all();
                    $type = 'الكل';
                    return view('reports.invoices_report' , compact('type'))->with('invoices' , $invoices);
                }
                else{
                    $invoices = Invoice::select('*')->where('status' , $request->type)->get();
                    $type = $request->type ;
                    return view('reports.invoices_report' , compact('type'))->with('invoices' , $invoices);
                }

            }
            else
            {
                if($request->type == 'all'){
                    $start_at = date($request->start_at);
                    $end_at = date($request->end_at);
                    $type = 'الكل في هذة الفترة' ;
                    $invoices = Invoice::whereBetween('invoice_data' , [$start_at , $end_at])->get();
                    return view('reports.invoices_report' , compact(['type'  , 'start_at' , 'end_at']))->with('invoices' , $invoices);
                }
                else{
                    $start_at = date($request->start_at);
                    $end_at = date($request->end_at);
                    $type = $request->type ;
                    $invoices = Invoice::whereBetween('invoice_data' , [$start_at , $end_at])->where('status' , $request->type)->get();
                    return view('reports.invoices_report' , compact(['type'  , 'start_at' , 'end_at']))->with('invoices' , $invoices);
                }

            }
        }
        
        // in case search by invoice number
        else{
            $invoices = Invoice::where('invoice_number' , $request->invoice_number)->get();
            return view('reports.invoices_report' , compact('invoices'));
        }
    }
    public function Search_customers_index(){
        $sections = Section::all();
        return view('reports.customers_report' , compact('sections'));
    }
    public function Search_customers(Request $request){
        // in case not selected date
        if($request->Section && $request->product && $request->start_at =='' && $request->end_at == ''){
            $invoices = Invoice::select('*')->where('section_id' , $request->Section)->where('product' , $request->product)->get();
            $sections = Section::all();
            return view('reports.customers_report' , compact(['invoices' , 'sections']));
        }
        // in case selected date
        else{
            $start_at = date($request->start_at);
            $end_at = date($request->end_at);
            $invoices = Invoice::whereBetween('invoice_data' , [$start_at , $end_at])->where('section_id' , $request->Secion)->get();
            $sections = Section::all();
            return view('reports.customers_report' , compact(['invoices' , 'sections']));
        }

    }
}
