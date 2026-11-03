<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800">Laporan Penjualan</h2>
            <button type="button" onclick="window.print()" class="no-print bg-gray-800 text-white px-4 py-2 rounded-md text-sm hover:bg-gray-700 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4H7v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Cetak Laporan
            </button>
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
            .print-card {
                box-shadow: none !important;
                border: none !important;
                padding: 0 !important;
            }
        }
    </style>

    <div class="py-8 space-y-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            {{-- Form Filter Periode Tanggal --}}
            <div class="no-print">
                <x-card>
                    <form method="GET" action="{{ route('report.sales') }}" class="flex flex-wrap items-end gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Dari Tanggal</label>
                            <input type="date" name="start_date" value="{{ $startDate }}"
                                class="rounded-md border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Sampai Tanggal</label>
                            <input type="date" name="end_date" value="{{ $endDate }}"
                                class="rounded-md border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div class="flex gap-2">
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                                Filter
                            </button>
                            <a href="{{ route('report.sales') }}" class="px-4 py-2 text-sm text-gray-500 hover:text-gray-700">
                                Reset
                            </a>
                        </div>
                    </form>
                </x-card>
            </div>

            {{-- Kartu Ringkasan Statistik --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <x-card class="print-card border-l-4 border-indigo-600">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Total Omset Penjualan</p>
                    <p class="text-2xl font-bold text-gray-900 mt-2">Rp{{ number_format($totalOmset, 0, ',', '.') }}</p>
                    <p class="text-xs text-gray-400 mt-1">Periode terpilih</p>
                </x-card>

                <x-card class="print-card border-l-4 border-emerald-600">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Total Transaksi Selesai</p>
                    <p class="text-2xl font-bold text-gray-900 mt-2">{{ number_format($totalTransaksi, 0, ',', '.') }}</p>
                    <p class="text-xs text-gray-400 mt-1">Struk tercetak</p>
                </x-card>

                <x-card class="print-card border-l-4 border-amber-600">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Total Item Produk Terjual</p>
                    <p class="text-2xl font-bold text-gray-900 mt-2">{{ number_format($totalItemTerjual, 0, ',', '.') }}</p>
                    <p class="text-xs text-gray-400 mt-1">Unit barang keluar</p>
                </x-card>
            </div>

            {{-- 5 Produk Terlaris --}}
            @if ($topProducts->isNotEmpty())
                <x-card class="print-card">
                    <h3 class="font-semibold text-lg text-gray-800 mb-4">5 Produk Terlaris (Periode Ini)</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left font-medium text-gray-500">Peringkat</th>
                                    <th class="px-4 py-2 text-left font-medium text-gray-500">Kode & Nama Produk</th>
                                    <th class="px-4 py-2 text-right font-medium text-gray-500">Jumlah Terjual</th>
                                    <th class="px-4 py-2 text-right font-medium text-gray-500">Kontribusi Penjualan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach ($topProducts as $idx => $tp)
                                    <tr>
                                        <td class="px-4 py-2 font-bold text-gray-700">#{{ $idx + 1 }}</td>
                                        <td class="px-4 py-2">
                                            <span class="font-mono text-xs text-gray-500">{{ $tp->product->code ?? '-' }}</span>
                                            <span class="font-medium text-gray-900 ml-1">{{ $tp->product->name ?? 'Produk Dihapus' }}</span>
                                        </td>
                                        <td class="px-4 py-2 text-right font-semibold">{{ $tp->total_qty }} {{ $tp->product->unit ?? 'pcs' }}</td>
                                        <td class="px-4 py-2 text-right">Rp{{ number_format($tp->total_revenue, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </x-card>
            @endif

            {{-- Tabel Riwayat Transaksi Penjualan --}}
            <x-card class="print-card">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-semibold text-lg text-gray-800">Daftar Transaksi Penjualan</h3>
                    <span class="text-xs text-gray-500">
                        {{ \Carbon\Carbon::parse($startDate)->translatedFormat('d M Y') }} - {{ \Carbon\Carbon::parse($endDate)->translatedFormat('d M Y') }}
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left font-medium text-gray-500">No. Invoice</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-500">Waktu</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-500">Kasir</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-500">Rincian Item</th>
                                <th class="px-4 py-3 text-right font-medium text-gray-500">Total Transaksi</th>
                                <th class="px-4 py-3 text-center font-medium text-gray-500 no-print">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($transactions as $t)
                                <tr>
                                    <td class="px-4 py-3 font-mono font-semibold text-indigo-600">
                                        {{ $t->invoice_number }}
                                    </td>
                                    <td class="px-4 py-3 text-gray-600">
                                        {{ $t->created_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="px-4 py-3 text-gray-700">
                                        {{ $t->user->name ?? 'Kasir' }}
                                    </td>
                                    <td class="px-4 py-3 text-gray-600">
                                        <ul class="text-xs list-disc list-inside">
                                            @foreach ($t->details as $d)
                                                <li>{{ $d->product->name ?? 'Produk' }} (x{{ $d->qty }})</li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td class="px-4 py-3 text-right font-bold text-gray-900">
                                        Rp{{ number_format($t->total, 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-3 text-center no-print">
                                        <a href="{{ route('pos.struk', $t) }}" class="text-xs bg-gray-100 hover:bg-gray-200 text-gray-700 px-2 py-1 rounded">
                                            Struk
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                                        Tidak ada data penjualan pada rentang tanggal yang dipilih.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 no-print">
                    {{ $transactions->links() }}
                </div>
            </x-card>

        </div>
    </div>
</x-app-layout>

