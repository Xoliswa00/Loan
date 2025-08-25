<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;
  
    protected $fillable = [
        'user_id',
        'loan_type',
        'loan_amount',
        'interest_rate',
        'loan_term',
        'collateral',
        'status',
        'approved_amount',
        'disbursed_date',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function loanApplication()
    {
        return $this->belongsTo(LoanApplication::class);
    }
    
  

    public function repayments()
    {
        return $this->hasMany(LoanRepayment::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function interest()
    {
        return $this->hasOne(LoanInterest::class);
    }

    public function repaymentSchedules()
    {
        return $this->hasMany(RepaymentSchedule::class);
    }

    public function fees() {
    return $this->hasMany(LoanFee::class);
}

}
