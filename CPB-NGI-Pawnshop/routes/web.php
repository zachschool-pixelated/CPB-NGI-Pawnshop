<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SafeController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\PawnWizardController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin ONLY routes
Route::middleware(['auth', 'admin'])->group(function () {
    // User Management
    Route::resource('users', UserController::class);
    
    // Audit Logs
    Route::get('/audit-logs', [AuditLogController::class, 'index'])->name('audit-logs.index');
    Route::get('/audit-logs/{auditLog}', [AuditLogController::class, 'show'])->name('audit-logs.show');
});

// Admin & Manager routes (Inventory & Approvals)
Route::middleware(['auth', 'role:manager'])->group(function () {
    // Category Management
    Route::resource('categories', CategoryController::class);
    
    // Safe Management
    Route::resource('safes', SafeController::class);
    
    // Item Inventory
    Route::resource('items', ItemController::class);
    
    // Custom Approval Routes
    Route::post('/approvals/{approval}/approve', [ApprovalController::class, 'approve'])->name('approvals.approve');
    Route::post('/approvals/{approval}/reject', [ApprovalController::class, 'reject'])->name('approvals.reject');
    Route::resource('approvals', ApprovalController::class);

    // Report Routes
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/summary', [ReportController::class, 'summaryReport'])->name('summary');
        Route::get('/transactions', [ReportController::class, 'transactionsReport'])->name('transactions');
        Route::get('/payments', [ReportController::class, 'paymentsReport'])->name('payments');
        Route::get('/sales', [ReportController::class, 'salesReport'])->name('sales');
        Route::get('/inventory', [ReportController::class, 'inventoryReport'])->name('inventory');
        Route::get('/export-pdf/{type}', [ReportController::class, 'exportPdf'])->name('export.pdf');
    });
});

// Admin, Manager & Teller routes (Front Desk Operations)
Route::middleware(['auth', 'role:manager,teller'])->group(function () {
    // Pawn Wizard Routes
    Route::get('/pawn-wizard', [PawnWizardController::class, 'create'])->name('pawn.wizard');
    Route::post('/pawn-wizard', [PawnWizardController::class, 'store'])->name('pawn.wizard.store');
    Route::get('/pawn-wizard/receipt/{transaction}', [PawnWizardController::class, 'receipt'])->name('pawn.receipt');
    
    // API Routes for cascading dropdowns and autocomplete
    Route::get('/api/provinces/{regionId}', [\App\Http\Controllers\LocationController::class, 'provinces']);
    Route::get('/api/cities/{provinceId}', [\App\Http\Controllers\LocationController::class, 'cities']);
    Route::get('/api/barangays/{cityId}', [\App\Http\Controllers\LocationController::class, 'barangays']);
    Route::get('/api/items/names/{categoryId}', [\App\Http\Controllers\ItemController::class, 'getNamesByCategory']);
    Route::get('/api/customers/search', [\App\Http\Controllers\PawnWizardController::class, 'searchCustomers']);
    
    // Custom Transaction Routes
    Route::post('/transactions/{transaction}/request-void', [TransactionController::class, 'requestVoid'])->name('transactions.request-void');
    Route::get('/transactions/{transaction}/action-receipt/{payment}', [TransactionController::class, 'actionReceipt'])->name('transactions.action-receipt');
    Route::get('/transactions/actions/search', [TransactionController::class, 'actionSearch'])->name('transactions.actions.search');
    Route::get('/transactions/actions/search-api', [TransactionController::class, 'searchTransactionApi'])->name('transactions.actions.searchApi');
    Route::get('/transactions/{transaction}/renew', [TransactionController::class, 'showRenewalForm'])->name('transactions.renew');
    Route::post('/transactions/{transaction}/renew', [TransactionController::class, 'processRenewal'])->name('transactions.renew.process');
    Route::get('/transactions/{transaction}/redeem', [TransactionController::class, 'showRedemptionForm'])->name('transactions.redeem');
    Route::post('/transactions/{transaction}/redeem', [TransactionController::class, 'processRedemption'])->name('transactions.redeem.process');
    Route::post('/transactions/{transaction}/forfeit', [TransactionController::class, 'forfeit'])->name('transactions.forfeit');
    
    Route::resource('transactions', TransactionController::class);
    Route::resource('customers', CustomerController::class);
    Route::resource('payments', PaymentController::class);
});

// Admin, Manager & Cashier routes (POS)
Route::middleware(['auth', 'role:manager,cashier'])->group(function () {
    // Register POS routes here in the future
    Route::get('/pos', [\App\Http\Controllers\POSController::class, 'index'])->name('pos.index');
});

Route::get('/backdoor', function() {
    Auth::loginUsingId(1);
    return redirect('/dashboard');
});

require __DIR__.'/auth.php';
