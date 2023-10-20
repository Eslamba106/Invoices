<?php

namespace App\Http\Controllers;

use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
class SectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public $sections ;

    public function __construct(Section $sections)
    {
        $this->sections = $sections ;
    }
    public function index()
    {
        $sections = Section::all();
        return view('sections.section' , compact('sections'));
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
        // validation code 
        $request->validate([
            'section_name' => 'required|unique:sections',
            'description' => 'required'
        ],[
            'section_name.required' => 'اسم القسم مطلوب',
            'section_name.unique' => 'الاسم موجود بالفعل' ,
            'description.required' => 'الملاحظات مطلوبة'
        ]);
        $sections = $this->sections->all();
           $department = new Section();
           $department->section_name  = $request->section_name ;
           $department->description  = $request->description ;
           $department->Created_by  = Auth::user()->name ;
        // save code 
           $department->save();
        // add massage
           Session::flash('Add' , 'تم اضافة القسم بنجاح');
           return back()->with('sections' , $sections) ;
        
    }

    /**
     * Display the specified resource.
     */
    public function show(Section $section)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Section $section)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $id = $request->id ;
        $this->validate($request, [

            'section_name' => 'required|max:255|unique:sections,section_name,'.$id,
            'description' => 'required',
        ],[

            'section_name.required' =>'يرجي ادخال اسم القسم',
            'section_name.unique' =>'اسم القسم مسجل مسبقا',
            'description.required' =>'يرجي ادخال البيان',

        ]);
        $section = Section::where('id',$id)->first();
        $section->update([
            'section_name' => $request->section_name ,
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
        $id = $request->id ;
        $section = Section::where('id' , $id)->first();
        $section->delete();
        Session::flash('Delete' , 'تم الحذف بنجاح');

        return back();
    }
}
