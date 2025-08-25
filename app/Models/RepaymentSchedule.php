<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RepaymentSchedule extends Model
{
    use HasFactory;

    protected $table = 'repayment_schedules'; 

    
    protected $fillable = [
        'loan_id',
        'emi_amount',
        'due_date',
        'status',
    ];

    public function loan()
    {
        return $this->belongsTo(LoanApplication::class);
    }
}
