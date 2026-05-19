<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Item;
use App\Models\Transaction;
use App\Models\Payment;
use App\Models\Approval;
use App\Models\Safe;
use Illuminate\View\View;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     */
    public function index(): View
    {
        // 1. Basic Metrics
        $customerCount = Customer::count();
        $activeCount = Transaction::where('status', 'active')->count();
        $itemsCount = Item::count();
        $totalLoanAmount = Transaction::where('status', 'active')->sum('loan_amount');

        // 2. Financial Metrics
        $todaysCollections = Payment::whereDate('payment_date', today())->sum('amount_paid');
        $monthlyProfit = Payment::whereMonth('payment_date', now()->month)
                                ->whereYear('payment_date', now()->year)
                                ->sum(DB::raw('interest_paid + penalty_paid + service_charge'));

        // 3. Pending Approvals
        $pendingApprovals = Approval::with(['user', 'model'])
                                    ->where('status', 'pending')
                                    ->orderBy('created_at', 'desc')
                                    ->get();

        // 4. Overdue & Upcoming Maturities (Next 7 days or overdue)
        $maturities = Transaction::with('customer')
                                 ->where('status', 'active')
                                 ->where('maturity_date', '<=', now()->addDays(7))
                                 ->orderBy('maturity_date', 'asc')
                                 ->limit(5)
                                 ->get();

        // 5. Vault Capacity
        $safes = Safe::withCount(['items as current_items_count' => function ($query) {
            $query->whereIn('item_status', ['stored', 'pawned']);
        }])->get();

        // 6. Chart Data (Last 14 days)
        $dates = collect();
        $loanData = collect();
        $collectionData = collect();

        for ($i = 13; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $dates->push(now()->subDays($i)->format('M d'));

            $loans = Transaction::whereDate('transaction_date', $date)->sum('loan_amount');
            $collections = Payment::whereDate('payment_date', $date)->sum('amount_paid');

            $loanData->push($loans);
            $collectionData->push($collections);
        }

        // 7. Recent Transactions
        $recentTransactions = Transaction::with('customer')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard', compact(
            'customerCount',
            'activeCount',
            'itemsCount',
            'totalLoanAmount',
            'todaysCollections',
            'monthlyProfit',
            'pendingApprovals',
            'maturities',
            'safes',
            'dates',
            'loanData',
            'collectionData',
            'recentTransactions'
        ));
    }
}
