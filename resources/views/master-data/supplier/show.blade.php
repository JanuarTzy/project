<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800">Detail Supplier</h2>
            <div class="flex gap-2">
                <a href="{{ route('purchases.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1.5 rounded-md text-sm">
                    + Buat PO Baru
                </a>
                <a href="{{ route('suppliers.index') }}" class="text-sm text-gray-600 hover:text-gray-900 px-3 py-1.5">
                    &larr; Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12 space-y-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            {{-- Profil Supplier --}}
            <x-card>
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900">{{ $supplier->name }}</h3>
                        <p class="text-sm text-gray-500 mt-1">
                            Telepon: {{ $supplier->phone ?: '-' }} &middot; Email: {{ $supplier->email ?: '-' }}
                        </p>
                        <p class="text-sm text-gray-600 mt-2">
                            Alamat: {{ $supplier->address ?: 'Belum ada catatan alamat.' }}
                        </p>
                    </div>
                    <a href="{{ route('suppliers.edit', $supplier) }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-1.5 rounded-md text-sm font-medium">
                        Ubah Profil
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-indigo-50 border border-indigo-100 rounded-lg p-4 text-center">
                        <p class="text-xs text-indigo-600 uppercase font-medium">Total Pembelian (PO)</p>
                        <p class="text-2xl font-bold text-indigo-900 mt-1">{{ $supplier->purchases->count() }} Transaksi</p>
                    </div>
                    <div class="bg-emerald-50 border border-emerald-100 rounded-lg p-4 text-center">
                        <p class="text-xs text-emerald-600 uppercase font-medium">Akumulasi Belanja Stok</p>
                        <p class="text-2xl font-bold text-emerald-900 mt-1">Rp{{ number_format($totalPembelian, 0, ',', '.') }}</p>
                    </div>
                </div>
            </x-card>

            {{-- Riwayat PO dari Supplier Ini --}}
            <x-card>
                <h4 class="font-semibold text-lg text-gray-800 mb-4">Riwayat Pembelian ke Supplier Ini</h4>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left font-medium text-gray-500">No. PO</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-500">Tanggal</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-500">Item Produk</th>
                                <th class="px-4 py-3 text-right font-medium text-gray-500">Total Nominal</th>
                                <th class="px-4 py-3 text-center font-medium text-gray-500">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($supplier->purchases as $po)
                                <tr>
                                    <td class="px-4 py-3 font-semibold text-indigo-600 font-mono">
                                        {{ $po->po_number }}
                                    </td>
                                    <td class="px-4 py-3 text-gray-600">
                                        {{ $po->purchase_date->format('d M Y') }}
                                    </td>
                                    <td class="px-4 py-3 text-gray-700">
                                        <ul class="list-disc list-inside text-xs">
                                            @foreach ($po->details as $d)
                                                <li>{{ $d->product->name ?? 'Produk' }} ({{ $d->qty }} {{ $d->product->unit ?? 'pcs' }})</li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td class="px-4 py-3 text-right font-bold text-gray-900">
                                        Rp{{ number_format($po->total_amount, 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <a href="{{ route('purchases.show', $po) }}" class="text-indigo-600 hover:text-indigo-800 text-xs font-medium">
                                            Lihat PO
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-6 text-center text-gray-500">
                                        Belum ada riwayat pembelian ke supplier ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-card>
        </div>
    </div>
</x-app-layout>

