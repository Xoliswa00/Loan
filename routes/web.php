<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\LoanApplicationController;
use App\Http\Controllers\LoanRepaymentController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\AccountDetailController;
use App\Http\Controllers\LoanInterestController;
use App\Http\Controllers\RepaymentScheduleController;
use App\Http\Controllers\AdminController;

use Illuminate\Support\Facades\Gate;

use function PHPUnit\Framework\isNull;

/*
|---------------------------------------------------------------------------
| Web Routes
|---------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Public route
Route::get('/', function () {
    return view('welcome');
});

// Dashboard route (requires authentication and email verification)

Route::get('/dashboard', function () {
    $user = auth()->user();

    if (is_null($user->rule_id)) {
        return view('dashboard'); // Regular user view
    } else {
        return redirect()->route('Admin.index'); // Admin dashboard route
    }
})->middleware(['auth'])->name('dashboard');




// Profile routes (requires authentication)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});



Route::view('/about', 'pages.about')->name('about');
Route::view('/contact', 'pages.contact')->name('contact');



    


Route::middleware('auth')->group(function () {


    Route::resource('loan', LoanController::class); // This automatically defines routes like loan.index, loan.create, loan.show, etc.
    Route::resource('loanapplications', LoanApplicationController::class);
    Route::resource('repaymentSchedules', LoanRepaymentController::class);
    Route::resource('transactions', TransactionController::class);
    Route::resource('loaninterests', LoanInterestController::class);
    Route::resource('accountdetails', AccountDetailController::class);
    Route::resource('applications', LoanApplicationController::class);
    Route::resource('loans', LoanController::class);
        Route::resource('loan_repayments', LoanRepaymentController::class);
                Route::resource('Admin', AdminController::class);


Route::post('loansp/{id}', [LoanController::class, 'approve'])->name('loans.approve');
Route::post('loansr/{id}', [LoanController::class, 'reject'])->name('loans.reject');
Route::post('loans/{loan}/disburse', [LoanController::class, 'disburse'])->name('loans.disburse');
Route::post('loans/{loan}/update-payment', [LoanController::class, 'updatePayment'])->name('loans.updatePayment');
// Route definitions
Route::get('/loans', [LoanController::class, 'index'])->name('loans');
Route::get('/loanapplications', [LoanApplicationController::class, 'index'])->name('loanapplications');
Route::get('/loanrepayments', [LoanRepaymentController::class, 'index'])->name('loanrepayments');

 Route::get('/Admin', [AdminController::class, 'Loans'])->name('Admin.Loans');
  Route::get('/Admins', [AdminController::class, 'index'])->name('Admin.index');




});







// Authentication routes
require __DIR__.'/auth.php';
