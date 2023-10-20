<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sections = Section::all();
        $products = Product::all();
        return view('products.product' , compact('sections' , 'products'));
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
            'product_name' => 'required|max:255|unique:products,product_name',
            'section_id'   => 'required',
        ],[
            'product_name.required' => 'اسم المنتج مطلوب' ,
            'product_name.max' => 'الاسم كبير للغاية' ,
            'product_name.unique' => 'الاسم موجود بالفعل',
            'section_id.required' => 'القسم مطلوب'  
        ]);
        Product::create([
            'product_name' => $request->product_name ,
            'section_id'   => $request->section_id,
            'description'  => $request->description
        ]);
        Session::flash('Add' , 'تم اضافة القسم بنجاح');
           return back() ;
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $product = Product::where('id',$request->pro_id)->first();
        $section = Section::where('section_name' , $request->section_name)->first();
        $id = $section->id;
        // $this->validate($request, [

        //     'product_name' => 'required|max:255|unique:products,product_name',
        //     'section_id' => 'required|max:255|unique:products,section_id,'.$id,
        //     'description' => 'required',
        // ],[

        //     'product_name.required' =>'يرجي ادخال اسم المنتج',
        //     'product_name.unique' =>'اسم المنتج مسجل مسبقا',
        //     'description.required' =>'يرجي ادخال البيان',

        // ]);
        $product->update([
            'product_name' => $request->Product_name ,
            'section_id'   => $id,
            'description'  => $request->description 
        ]);
        Session::flash('Edit' , 'تم التعديل بنجاح');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $product = Product::where('id' , $request->pro_id)->first() ;
        $product->delete();
        return back();
    }
}
