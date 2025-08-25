<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanApplication extends Model
{
    use HasFactory;
    
    protected $guarded = [
    
    ];
    protected $table = 'loan_applications'; 
    public function loan()
{
    return $this->hasOne(Loan::class);
}

public function loanFee()
{
    return $this->hasOne(LoanFee::class);
}

public function repaymentSchedules()
{
    return $this->hasMany(RepaymentSchedule::class, 'loan_id', 'id');
}


    public function user()
    {
        return $this->belongsTo(User::class);
    }

        public function fees() {
    return $this->hasMany(LoanFee::class);
}
}
