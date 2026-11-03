<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800">Riwayat Transaksi Saya</h2>
            <a href="{{ route('pos.index') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm">
                + Transaksi Baru
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-card>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left font-medium text-gray-500">No. Invoice</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-500">Waktu Transaksi</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-500">Item Produk</th>
                                <th class="px-4 py-3 text-right font-medium text-gray-500">Total Belanja</th>
                                <th class="px-4 py-3 text-right font-medium text-gray-500">Bayar</th>
                                <th class="px-4 py-3 text-right font-medium text-gray-500">Kembalian</th>
                                <th class="px-4 py-3 text-center font-medium text-gray-500">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($transactions as $trx)
                                <tr>
                                    <td class="px-4 py-3 font-semibold text-indigo-600 font-mono">
                                        {{ $trx->invoice_number }}
                                    </td>
                                    <td class="px-4 py-3 text-gray-600">
                                        {{ $trx->created_at->format('d M Y H:i:s') }}
                                    </td>
                                    <td class="px-4 py-3 text-gray-700">
                                        <ul class="list-disc list-inside space-y-0.5 text-xs">
                                            @foreach ($trx->details as $detail)
                                                <li>{{ $detail->product->name ?? 'Produk Dihapus' }} (x{{ $detail->qty }})</li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td class="px-4 py-3 text-right font-semibold text-gray-900">
                                        Rp{{ number_format($trx->total, 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-3 text-right text-gray-600">
                                        Rp{{ number_format($trx->pay, 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-3 text-right text-gray-600">
                                        Rp{{ number_format($trx->change, 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <a href="{{ route('pos.struk', $trx) }}"
                                            class="inline-block px-3 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded text-xs font-medium">
                                            Lihat Struk
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                                        Belum ada riwayat transaksi yang Anda buat.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $transactions->links() }}
                </div>
            </x-card>
        </div>
    </div>
</x-app-layout>

