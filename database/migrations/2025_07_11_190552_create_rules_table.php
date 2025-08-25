<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


        use Illuminate\Support\Facades\DB;


        
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rules', function (Blueprint $table) {
            $table->id();
               $table->string('name')->unique(); // e.g. owner, admin, approver
            $table->string('description')->nullable();
            $table->timestamps();
        });



 DB::table('rules')->insert([
            ['name' => 'owner', 'description' => 'Full access to all modules'],
            ['name' => 'admin', 'description' => 'System administrator with high-level permissions'],
            ['name' => 'approver', 'description' => 'Can approve or reject loan applications'],
            ['name' => 'finance_clerk', 'description' => 'Handles recon, repayments, disbursements'],
            ['name' => 'staff', 'description' => 'General staff access with limited permissions'],
        ]);

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rules');
    }
};
