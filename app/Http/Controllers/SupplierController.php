<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $query = Supplier::query()->withCount('purchases');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $suppliers = $query->latest()->paginate(10)->withQueryString();

        return view('master-data.supplier.index', compact('suppliers'));
    }

    public function create()
    {
        return view('master-data.supplier.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:150',
            'phone'   => 'nullable|string|max:30',
            'email'   => 'nullable|email|max:100',
            'address' => 'nullable|string|max:500',
        ]);

        Supplier::create($validated);

        return redirect()->route('suppliers.index')->with('success', 'Data Supplier berhasil ditambahkan.');
    }

    public function show(Supplier $supplier)
    {
        $supplier->load(['purchases.details.product', 'purchases.user']);
        $totalPembelian = $supplier->purchases->sum('total_amount');

        return view('master-data.supplier.show', compact('supplier', 'totalPembelian'));
    }

    public function edit(Supplier $supplier)
    {
        return view('master-data.supplier.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:150',
            'phone'   => 'nullable|string|max:30',
            'email'   => 'nullable|email|max:100',
            'address' => 'nullable|string|max:500',
        ]);

        $supplier->update($validated);

        return redirect()->route('suppliers.index')->with('success', 'Data Supplier berhasil diperbarui.');
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->delete();

        return redirect()->route('suppliers.index')->with('success', 'Data Supplier berhasil dihapus.');
    }
}
