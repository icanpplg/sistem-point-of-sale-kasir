<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    // Field yang bisa diisi secara massal
    protected $fillable = [
        'transaction_date',
        'cashier',
        'total',
        'payment',
        'change',
    ];

    // Casting field transaction_date sebagai instance Carbon
    protected $dates = ['transaction_date'];

    /**
     * Relasi ke TransactionItem (satu transaksi memiliki banyak item)
     */
    public function items()
    {
        return $this->hasMany(TransactionItem::class);
    }
}
