<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table = 'products' ;
    protected $fillable = ['id', 'product_name', 'description', 'section_id'];

    public function parent(){
        return $this->belongsTo(Section::class , 'section_id');
    }
}
