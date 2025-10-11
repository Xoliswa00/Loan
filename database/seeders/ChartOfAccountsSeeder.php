<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\chart_of_accounts;

class ChartOfAccountsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
  $accounts = [
        ['account_code' => '1000', 'account_type' => 'Cash on Hand', 'account_group' => 'Current Asset', 'account_category' => 'Asset', 'statement_section' => 'Balance Sheet'],
        ['account_code' => '1100', 'account_type' => 'Bank', 'account_group' => 'Current Asset', 'account_category' => 'Asset', 'statement_section' => 'Balance Sheet'],
        ['account_code' => '1200', 'account_type' => 'Loan Receivable - Principal', 'account_group' => 'Loan Asset', 'account_category' => 'Asset', 'statement_section' => 'Balance Sheet'],
        ['account_code' => '1210', 'account_type' => 'Loan Receivable - Interest', 'account_group' => 'Loan Asset', 'account_category' => 'Asset', 'statement_section' => 'Balance Sheet'],
        ['account_code' => '1220', 'account_type' => 'Interest Receivable (Accrued)', 'account_group' => 'Loan Asset', 'account_category' => 'Asset', 'statement_section' => 'Balance Sheet'],
        ['account_code' => '2000', 'account_type' => 'Accounts Payable', 'account_group' => 'Current Liability', 'account_category' => 'Liability', 'statement_section' => 'Balance Sheet'],
        ['account_code' => '2100', 'account_type' => 'Customer Deposits', 'account_group' => 'Current Liability', 'account_category' => 'Liability', 'statement_section' => 'Balance Sheet'],
        ['account_code' => '2200', 'account_type' => 'Loan Payable (Funding Source)', 'account_group' => 'Non-Current Liability', 'account_category' => 'Liability', 'statement_section' => 'Balance Sheet'],
        ['account_code' => '4000', 'account_type' => 'Interest Income', 'account_group' => 'Loan Income', 'account_category' => 'Income', 'statement_section' => 'P&L'],
        ['account_code' => '4010', 'account_type' => 'Processing Fee Income', 'account_group' => 'Loan Income', 'account_category' => 'Income', 'statement_section' => 'P&L'],
        ['account_code' => '4020', 'account_type' => 'Penalty Income', 'account_group' => 'Loan Income', 'account_category' => 'Income', 'statement_section' => 'P&L'],
        ['account_code' => '5000', 'account_type' => 'Operating Expense', 'account_group' => 'Operating Expense', 'account_category' => 'Expense', 'statement_section' => 'P&L'],
        ['account_code' => '5100', 'account_type' => 'Bad Debt Expense', 'account_group' => 'Loan Expense', 'account_category' => 'Expense', 'statement_section' => 'P&L'],
        ['account_code' => '5200', 'account_type' => 'Bank Charges', 'account_group' => 'Operating Expense', 'account_category' => 'Expense', 'statement_section' => 'P&L'],
        ['account_code' => '6000', 'account_type' => 'Loan Loss Provision', 'account_group' => 'Provisions', 'account_category' => 'Contra Asset', 'statement_section' => 'Balance Sheet'],
    ];

    foreach ($accounts as $account) {
        chart_of_accounts::create($account);
    }




    }
}
