<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Invoice extends Model
{
    use HasFactory , SoftDeletes ;
    protected $fillable = [
        'id', 
        'invoice_number', 
        'invoice_data', 
        'due_data', 
        'product', 
        'section_id', 
        'rate_vat', 
        'discount', 
        'value_vat', 
        'Amount_collection', 
        'Amount_Commission', 
        'total', 
        'status', 
        'value_status', 
        'note', 
        'user', 
        'Payment_Date'
    ];
    protected $table = 'invoices';
    protected $dates = ['deleted_at'];

 public function section()
   {
   return $this->belongsTo(Section::class , 'section_id');
   }
 public function child()
   {
   return $this->hasOne(Invoice_detail::class , 'invoice_id');
   }
}
