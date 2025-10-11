<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\chart_of_accounts;
use App\Models\gl_accounts;

class branches extends Model
{
    use HasFactory;
    protected $fillable = ['branch_code', 'branch_name', 'location', 'branch_type', 'company_id','is_active'];

}
