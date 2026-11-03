<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exports\SalesExport;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function sales(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', now()->toDateString());
        $cashierId = $request->input('user_id');

        // Query transaksi sesuai filter rentang tanggal dan kasir
        $transactionsQuery = Transaction::with(['user', 'details.product'])
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate);

        if (!empty($cashierId)) {
            $transactionsQuery->where('user_id', $cashierId);
        }

        $totalOmset = (clone $transactionsQuery)->sum('total');
        $totalTransaksi = (clone $transactionsQuery)->count();

        // Total item produk yang terjual pada rentang tanggal tersebut
        $totalItemTerjual = TransactionDetail::whereHas('transaction', function ($q) use ($startDate, $endDate, $cashierId) {
            $q->whereDate('created_at', '>=', $startDate)
              ->whereDate('created_at', '<=', $endDate);

            if (!empty($cashierId)) {
                $q->where('user_id', $cashierId);
            }
        })->sum('qty');

        // Daftar transaksi terbaru
        $transactions = $transactionsQuery->latest()->paginate(10)->withQueryString();

        // 5 Produk Terlaris dalam periode ini
        $topProducts = TransactionDetail::whereHas('transaction', function ($q) use ($startDate, $endDate, $cashierId) {
            $q->whereDate('created_at', '>=', $startDate)
              ->whereDate('created_at', '<=', $endDate);

            if (!empty($cashierId)) {
                $q->where('user_id', $cashierId);
            }
        })
        ->select('product_id', DB::raw('SUM(qty) as total_qty'), DB::raw('SUM(subtotal) as total_revenue'))
        ->with('product')
        ->groupBy('product_id')
        ->orderByDesc('total_qty')
        ->take(5)
        ->get();

        // Ambil daftar kasir/user untuk opsi dropdown filter
        $cashiers = User::orderBy('name')->get();

        return view('reports.sales', compact(
            'transactions',
            'totalOmset',
            'totalTransaksi',
            'totalItemTerjual',
            'startDate',
            'endDate',
            'cashierId',
            'cashiers',
            'topProducts'
        ));
    }
    
    public function export(Request $request)
    {
        $start = $request->filled('start') ? Carbon::parse($request->start)->toDateString() : now()->subDays(6)->toDateString();
        $end = $request->filled('end') ? Carbon::parse($request->end)->toDateString() : now()->toDateString();
        $userId = $request->input('user_id');

        $filename = 'laporan-penjualan-' . Carbon::parse($start)->format('Ymd') . '-' . Carbon::parse($end)->format('Ymd') . '.xlsx';

        return Excel::download(new SalesExport($start, $end, $userId), $filename);
    }
}


