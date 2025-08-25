<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanRepayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'loan_id',
        'payment_amount',
        'payment_date',
        'status',
        'payment_method',
    ];

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }
}
