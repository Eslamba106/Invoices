<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class invoice_attachment extends Model
{
    use HasFactory;
    protected $table = 'invoice_attachments';
    protected $fillable = ['id', 'file_name', 'invoice_number', 'invoice_id', 'Created_by'];
}
