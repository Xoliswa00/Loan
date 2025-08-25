<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanInterest extends Model
{
    use HasFactory;

    protected $fillable = [
        'loan_id',
        'interest_type',
        'rate',
        'calculation_method',
    ];

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }
}
