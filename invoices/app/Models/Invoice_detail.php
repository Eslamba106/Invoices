<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Invoice_detail extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'invoice_details';
    protected $fillable = ['id', 'invoice_id', 'invoice_number', 'product', 'section', 'status', 'value_status', 'note', 'user'];
    
    public function section()
    {
    return $this->belongsTo(Invoice::class , 'invoice_id');
    }
}