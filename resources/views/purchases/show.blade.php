<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800">Detail Purchase Order</h2>
            <div class="flex gap-2 no-print">
                <button type="button" onclick="window.print()" class="bg-gray-800 hover:bg-gray-700 text-white px-3 py-1.5 rounded-md text-sm flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4H7v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Cetak Dokumen PO
                </button>
                <a href="{{ route('purchases.index') }}" class="text-sm text-gray-600 hover:text-gray-900 px-3 py-1.5">
                    &larr; Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <style>
        @media print {
            nav, header, .no-print {
                display: none !important;
            }
            body {
                background: white !important;
            }
            .po-card {
                box-shadow: none !important;
                border: none !important;
            }
        }
    </style>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <x-card class="po-card">
                @if (session('success'))
                    <div class="mb-6 p-4 bg-green-100 border border-green-200 text-green-700 rounded-md text-sm no-print">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="flex justify-between items-start border-b pb-6 mb-6">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">FAKTUR PURCHASE ORDER</h1>
                        <p class="text-sm font-mono text-indigo-600 font-bold mt-1">{{ $purchase->po_number }}</p>
                        <p class="text-xs text-gray-500 mt-2">Dibuat oleh: {{ $purchase->user->name ?? 'Admin' }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-semibold text-gray-800">POS Barokah Mart</p>
                        <p class="text-xs text-gray-500">Tanggal: {{ $purchase->purchase_date->format('d F Y') }}</p>
                    </div>
                </div>

                {{-- Informasi Supplier --}}
                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Informasi Supplier / Rekanan</p>
                    <p class="font-semibold text-gray-900 text-base">{{ $purchase->supplier->name }}</p>
                    <p class="text-sm text-gray-600">Telepon: {{ $purchase->supplier->phone ?: '-' }} &middot; Email: {{ $purchase->supplier->email ?: '-' }}</p>
                    <p class="text-sm text-gray-600 mt-1">Alamat: {{ $purchase->supplier->address ?: '-' }}</p>
                </div>

                {{-- Tabel Rincian Produk --}}
                <div class="overflow-x-auto mb-6">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-2.5 text-left font-medium text-gray-600">No</th>
                                <th class="px-4 py-2.5 text-left font-medium text-gray-600">Kode & Nama Produk</th>
                                <th class="px-4 py-2.5 text-right font-medium text-gray-600">Kuantitas</th>
                                <th class="px-4 py-2.5 text-right font-medium text-gray-600">Harga Beli / Satuan</th>
                                <th class="px-4 py-2.5 text-right font-medium text-gray-600">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($purchase->details as $index => $item)
                                <tr>
                                    <td class="px-4 py-3 text-gray-500">{{ $index + 1 }}</td>
                                    <td class="px-4 py-3">
                                        <span class="font-mono text-xs text-gray-500 block">{{ $item->product->code ?? '-' }}</span>
                                        <span class="font-semibold text-gray-900">{{ $item->product->name ?? 'Produk Dihapus' }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-right font-medium text-gray-800">
                                        {{ $item->qty }} {{ $item->product->unit ?? 'pcs' }}
                                    </td>
                                    <td class="px-4 py-3 text-right text-gray-700">
                                        Rp{{ number_format($item->cost_price, 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-3 text-right font-bold text-gray-900">
                                        Rp{{ number_format($item->subtotal, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="border-t bg-gray-50">
                            <tr>
                                <td colspan="4" class="px-4 py-3 font-semibold text-right text-gray-800 text-base">Total Nilai Pembelian:</td>
                                <td class="px-4 py-3 text-right font-bold text-lg text-indigo-700">Rp{{ number_format($purchase->total_amount, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                @if ($purchase->notes)
                    <div class="border-t pt-4 text-xs text-gray-600">
                        <span class="font-semibold text-gray-700">Catatan:</span> {{ $purchase->notes }}
                    </div>
                @endif
            </x-card>
        </div>
    </div>
</x-app-layout>

