<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    public function index(Request $request)
    {
        $query = Purchase::with(['supplier', 'user', 'details.product']);

        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('po_number', 'like', "%{$search}%")
                  ->orWhereHas('supplier', fn($sq) => $sq->where('name', 'like', "%{$search}%"));
            });
        }

        $purchases = $query->latest('purchase_date')->latest('id')->paginate(10)->withQueryString();
        $suppliers = Supplier::orderBy('name')->get();

        return view('purchases.index', compact('purchases', 'suppliers'));
    }

    public function create()
    {
        $suppliers = Supplier::orderBy('name')->get();
        $products = Product::orderBy('name')->get();

        // Generate nomor PO otomatis
        $dateStr = now()->format('Ymd');
        $countToday = Purchase::whereDate('created_at', now())->count() + 1;
        $defaultPoNumber = 'PO-' . $dateStr . '-' . str_pad($countToday, 4, '0', STR_PAD_LEFT);

        return view('purchases.create', compact('suppliers', 'products', 'defaultPoNumber'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'po_number'     => 'required|string|max:50|unique:purchases,po_number',
            'supplier_id'   => 'required|exists:suppliers,id',
            'purchase_date' => 'required|date',
            'notes'         => 'nullable|string|max:500',
            'items'         => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.qty'        => 'required|integer|min:1',
            'items.*.cost_price' => 'required|numeric|min:0',
        ]);

        $purchase = DB::transaction(function () use ($request) {
            $totalAmount = 0;
            $details = [];

            foreach ($request->items as $item) {
                $subtotal = $item['qty'] * $item['cost_price'];
                $totalAmount += $subtotal;

                $details[] = [
                    'product_id' => $item['product_id'],
                    'qty'        => $item['qty'],
                    'cost_price' => $item['cost_price'],
                    'subtotal'   => $subtotal,
                ];

                // Tambahkan kuantitas ke stok fisik produk
                $product = Product::lockForUpdate()->findOrFail($item['product_id']);
                $product->increment('stock', $item['qty']);
            }

            $purchase = Purchase::create([
                'po_number'     => $request->po_number,
                'supplier_id'   => $request->supplier_id,
                'user_id'       => auth()->id(),
                'purchase_date' => $request->purchase_date,
                'total_amount'  => $totalAmount,
                'notes'         => $request->notes,
            ]);

            $purchase->details()->createMany($details);

            return $purchase;
        });

        return redirect()->route('purchases.show', $purchase)->with('success', 'Transaksi pembelian berhasil disimpan dan stok produk telah ditambahkan.');
    }

    public function show(Purchase $purchase)
    {
        $purchase->load(['supplier', 'user', 'details.product']);

        return view('purchases.show', compact('purchase'));
    }

    public function destroy(Purchase $purchase)
    {
        DB::transaction(function () use ($purchase) {
            // Kembalikan (kurangi) stok barang yang pernah dibeli
            foreach ($purchase->details as $detail) {
                $product = Product::lockForUpdate()->find($detail->product_id);
                if ($product) {
                    $product->decrement('stock', min($product->stock, $detail->qty));
                }
            }
            $purchase->delete();
        });

        return redirect()->route('purchases.index')->with('success', 'Riwayat pembelian berhasil dibatalkan dan penyesuaian stok telah dilakukan.');
    }
}
