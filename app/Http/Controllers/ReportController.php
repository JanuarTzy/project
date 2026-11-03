<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function sales(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', now()->toDateString());

        // Query transaksi sesuai filter rentang tanggal
        $transactionsQuery = Transaction::with(['user', 'details.product'])
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate);

        $totalOmset = (clone $transactionsQuery)->sum('total');
        $totalTransaksi = (clone $transactionsQuery)->count();

        // Total item produk yang terjual pada rentang tanggal tersebut
        $totalItemTerjual = TransactionDetail::whereHas('transaction', function ($q) use ($startDate, $endDate) {
            $q->whereDate('created_at', '>=', $startDate)
              ->whereDate('created_at', '<=', $endDate);
        })->sum('qty');

        // Daftar transaksi terbaru
        $transactions = $transactionsQuery->latest()->paginate(10)->withQueryString();

        // 5 Produk Terlaris dalam periode ini
        $topProducts = TransactionDetail::whereHas('transaction', function ($q) use ($startDate, $endDate) {
            $q->whereDate('created_at', '>=', $startDate)
              ->whereDate('created_at', '<=', $endDate);
        })
        ->select('product_id', DB::raw('SUM(qty) as total_qty'), DB::raw('SUM(subtotal) as total_revenue'))
        ->with('product')
        ->groupBy('product_id')
        ->orderByDesc('total_qty')
        ->take(5)
        ->get();

        return view('reports.sales', compact(
            'transactions',
            'totalOmset',
            'totalTransaksi',
            'totalItemTerjual',
            'startDate',
            'endDate',
            'topProducts'
        ));
    }
}

