<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800">Riwayat Pembelian Stok (Purchase Order)</h2>
            <a href="{{ route('purchases.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                + Tambah Pembelian (PO)
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-card>
                @if (session('success'))
                    <div class="mb-4 p-3 bg-green-100 border border-green-200 text-green-700 rounded-md text-sm">
                        {{ session('success') }}
                    </div>
                @endif

                {{-- Filter & Pencarian PO --}}
                <form method="GET" action="{{ route('purchases.index') }}" class="flex flex-wrap items-end gap-3 mb-4">
                    <div class="flex-1 min-w-[200px]">
                        <label class="block text-xs font-medium text-gray-600 mb-1">Cari PO / Supplier</label>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari nomor PO atau nama supplier..."
                            class="w-full rounded-md border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Filter Supplier</label>
                        <select name="supplier_id" class="rounded-md border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Semua Supplier</option>
                            @foreach ($suppliers as $s)
                                <option value="{{ $s->id }}" @selected(request('supplier_id') == $s->id)>
                                    {{ $s->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                            Cari
                        </button>
                        @if (request()->hasAny(['search', 'supplier_id']))
                            <a href="{{ route('purchases.index') }}" class="px-3 py-2 text-sm text-gray-500 hover:text-gray-700">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left font-medium text-gray-500">No. PO</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-500">Tanggal Beli</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-500">Supplier</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-500">Petugas</th>
                                <th class="px-4 py-3 text-right font-medium text-gray-500">Total Biaya</th>
                                <th class="px-4 py-3 text-center font-medium text-gray-500">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($purchases as $po)
                                <tr>
                                    <td class="px-4 py-3 font-semibold font-mono text-indigo-600">
                                        {{ $po->po_number }}
                                    </td>
                                    <td class="px-4 py-3 text-gray-600">
                                        {{ $po->purchase_date->format('d/m/Y') }}
                                    </td>
                                    <td class="px-4 py-3 text-gray-900 font-medium">
                                        <a href="{{ route('suppliers.show', $po->supplier) }}" class="hover:underline text-indigo-700">
                                            {{ $po->supplier->name }}
                                        </a>
                                    </td>
                                    <td class="px-4 py-3 text-gray-600">
                                        {{ $po->user->name ?? 'Admin' }}
                                    </td>
                                    <td class="px-4 py-3 text-right font-bold text-gray-900">
                                        Rp{{ number_format($po->total_amount, 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-3 text-center space-x-2">
                                        <a href="{{ route('purchases.show', $po) }}" class="text-indigo-600 hover:text-indigo-800 font-medium">
                                            Detail
                                        </a>
                                        <form action="{{ route('purchases.destroy', $po) }}" method="POST"
                                              onsubmit="return confirm('Membatalkan PO {{ $po->po_number }} akan otomatis mengurangi kembali stok yang telah ditambahkan. Lanjutkan?')"
                                              class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 text-xs">
                                                Batalkan
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                                        Belum ada data transaksi pembelian stok.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $purchases->links() }}
                </div>
            </x-card>
        </div>
    </div>
</x-app-layout>

