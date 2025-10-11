<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\glmapping;

class GlmappingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
       $mappings = [
            // Loan disbursement
            ['key' => 'loan_disbursement_dr', 'account_code' => '1200', 'entry_type' => 'debit', 'description' => 'Loan Receivable'],
            ['key' => 'loan_disbursement_cr', 'account_code' => '1100', 'entry_type' => 'credit', 'description' => 'Bank'],

            // Loan repayment
            ['key' => 'loan_repayment_dr', 'account_code' => '1100', 'entry_type' => 'debit', 'description' => 'Bank'],
            ['key' => 'loan_repayment_cr', 'account_code' => '1200', 'entry_type' => 'credit', 'description' => 'Loan Receivable'],

            // Income accounts
            ['key' => 'interest_income', 'account_code' => '4000', 'entry_type' => 'credit', 'description' => 'Interest Income'],
            ['key' => 'processing_fee_income', 'account_code' => '4010', 'entry_type' => 'credit', 'description' => 'Processing Fee Income'],
            ['key' => 'penalty_income', 'account_code' => '4020', 'entry_type' => 'credit', 'description' => 'Penalty Income'],

            // Expenses
            ['key' => 'bad_debt_expense', 'account_code' => '5100', 'entry_type' => 'debit', 'description' => 'Bad Debt Expense'],
            ['key' => 'bank_charges', 'account_code' => '5200', 'entry_type' => 'debit', 'description' => 'Bank Charges'],

            // Provisions
            ['key' => 'loan_loss_provision', 'account_code' => '6000', 'entry_type' => 'debit', 'description' => 'Loan Loss Provision'],
        ];

        foreach ($mappings as $map) {
            glmapping::updateOrCreate(
                ['key' => $map['key']],
                $map
            );
        }
    }

}
