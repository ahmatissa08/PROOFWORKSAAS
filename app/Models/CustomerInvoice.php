<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerInvoice extends Model
{
    protected $fillable = [
        'user_id',
        'stripe_invoice_id',
        'amount_paid',
        'currency',
        'status',
        'invoice_pdf',
        'hosted_invoice_url',
        'paid_at',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
