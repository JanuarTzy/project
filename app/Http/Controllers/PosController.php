<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Transaction;
use App\Http\Requests\StorePosRequest;
use Illuminate\Support\Facades\DB;

class PosController extends Controller
{
    
public function index()
{
    $products = Product::where('stock', '>', 0)
        ->orderBy('name')
        ->get(['id', 'code', 'name', 'price', 'stock', 'unit']);
 
    return view('pos.index', compact('products'));
}
    
    public function store(StorePosRequest $request)
    {
        try {
            $transaction = DB::transaction(function () use ($request) {
                $total = 0;
                $details = [];

                foreach ($request->items as $item) {
                    // lockForUpdate mengunci baris produk agar tidak dibaca kasir lain
                    // secara bersamaan selagi stok sedang diperbarui.
                    $product = Product::lockForUpdate()->findOrFail($item['product_id']);

                    if ($product->stock < $item['qty']) {
                        throw new \Exception("Stok {$product->name} tidak mencukupi (tersedia: {$product->stock}, diminta: {$item['qty']}).");
                    }

                    $subtotal = $product->price * $item['qty'];
                    $total += $subtotal;

                    $details[] = [
                        'product_id' => $product->id,
                        'qty' => $item['qty'],
                        'price' => $product->price,
                        'subtotal' => $subtotal,
                    ];

                    $product->decrement('stock', $item['qty']);
                }

                if ($request->pay < $total) {
                    throw new \Exception('Nominal bayar kurang dari total belanja.');
                }

                $trx = Transaction::create([
                    'invoice_number' => 'INV-' . now()->format('Ymd') . '-' . str_pad(Transaction::whereDate('created_at', now())->count() + 1, 4, '0', STR_PAD_LEFT),
                    'user_id' => auth()->id(),
                    'total' => $total,
                    'pay' => $request->pay,
                    'change' => $request->pay - $total,
                ]);

                $trx->details()->createMany($details);

                return $trx;
            });

            return redirect()->route('pos.struk', $transaction)->with('success', 'Transaksi berhasil disimpan.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function struk(Transaction $transaction)
    {
        $transaction->load('details.product', 'user');
        return view('pos.struk', compact('transaction'));
    }

    public function history()
    {
        $transactions = Transaction::with(['details.product', 'user'])
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('pos.history', compact('transactions'));
    }
}
