<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    
public function index()
{
    $omzetHariIni = Transaction::whereDate('created_at', today())->sum('total');
    $omzetKemarin = Transaction::whereDate('created_at', today()->subDay())->sum('total');
    $transaksiHariIni = Transaction::whereDate('created_at', today())->count();
 
    $produkMenipis = Product::where('stock', '<', 10)->count();
 
    // Tren penjualan 7 hari terakhir, dikelompokkan per tanggal
    $tren = Transaction::selectRaw('DATE(created_at) as tanggal, SUM(total) as total')
        ->where('created_at', '>=', now()->subDays(6)->startOfDay())
        ->groupBy('tanggal')
        ->orderBy('tanggal')
        ->get();
 
    // Lengkapi tanggal yang tidak memiliki transaksi agar grafik tetap 7 titik
    $labelTren = collect(range(6, 0))->map(fn ($i) => now()->subDays($i)->format('Y-m-d'));
    $dataTren = $labelTren->map(function ($tanggal) use ($tren) {
        return optional($tren->firstWhere('tanggal', $tanggal))->total ?? 0;
    });
 
    // Lima produk terlaris berdasarkan akumulasi qty pada transaction_details
    $produkTerlaris = Product::select('products.name')
        ->selectRaw('SUM(transaction_details.qty) as total_qty')
        ->join('transaction_details', 'transaction_details.product_id', '=', 'products.id')
        ->groupBy('products.id', 'products.name')
        ->orderByDesc('total_qty')
        ->take(5)
        ->get();
 
    return view('dashboard', compact(
        'omzetHariIni', 'omzetKemarin', 'transaksiHariIni', 'produkMenipis',
        'labelTren', 'dataTren', 'produkTerlaris'
    ));
}
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
