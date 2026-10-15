<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupermarketSale extends Model
{
    use HasFactory;

    // Menghubungkan ke tabel supermarket_sales di database
    protected $table = 'supermarket_sales';

    // Kolom yang diizinkan untuk diisi data secara massal (mass assignment)
    protected $fillable = [
        'invoice',
        'stock_code',
        'description',
        'quantity',
        'invoice_date',
        'price',
        'customer_id',
        'country',
    ];
}
