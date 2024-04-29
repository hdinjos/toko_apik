<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductInvoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'total_price',
        'total_qty',
        'invoice_id',
        'unit_price'
    ];
}
