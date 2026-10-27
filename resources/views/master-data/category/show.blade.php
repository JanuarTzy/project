<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800">Detail Kategori</h2>
            <a href="{{ route('categories.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800">
                &larr; Kembali ke Daftar Kategori
            </a>
        </div>
    </x-slot>

    <div class="py-12 space-y-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-card>
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-800">{{ $category->name }}</h3>
                        <p class="text-sm text-gray-500 mt-1">{{ $category->description ?: 'Tidak ada deskripsi' }}</p>
                    </div>
                    <div class="flex space-x-2">
                        <a href="{{ route('categories.edit', $category) }}" class="bg-indigo-600 text-white px-3 py-1.5 rounded-md text-sm">
                            Ubah Kategori
                        </a>
                    </div>
                </div>

                {{-- Statistik Ringkasan --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                    <div class="bg-indigo-50 border border-indigo-100 rounded-lg p-4 text-center">
                        <p class="text-xs text-indigo-600 uppercase font-medium">Total Jenis Produk</p>
                        <p class="text-2xl font-bold text-indigo-900 mt-1">{{ $category->products->count() }}</p>
                    </div>
                    <div class="bg-green-50 border border-green-100 rounded-lg p-4 text-center">
                        <p class="text-xs text-green-600 uppercase font-medium">Total Stok Keseluruhan</p>
                        <p class="text-2xl font-bold text-green-900 mt-1">{{ number_format($totalStock, 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-amber-50 border border-amber-100 rounded-lg p-4 text-center">
                        <p class="text-xs text-amber-600 uppercase font-medium">Estimasi Nilai Total Stok</p>
                        <p class="text-2xl font-bold text-amber-900 mt-1">Rp{{ number_format($totalNilaiStock, 0, ',', '.') }}</p>
                    </div>
                </div>

                {{-- Tabel Daftar Produk dalam Kategori --}}
                <div class="flex justify-between items-center mb-4">
                    <h4 class="text-lg font-semibold text-gray-800">Daftar Produk</h4>
                    <a href="{{ route('products.create') }}" class="text-sm text-indigo-600 hover:text-indigo-800">
                        + Tambah Produk Baru
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left font-medium text-gray-500">Kode</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-500">Nama Produk</th>
                                <th class="px-4 py-3 text-right font-medium text-gray-500">Harga Satuan</th>
                                <th class="px-4 py-3 text-center font-medium text-gray-500">Stok</th>
                                <th class="px-4 py-3 text-right font-medium text-gray-500">Total Nilai Stok</th>
                                <th class="px-4 py-3 text-center font-medium text-gray-500">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($category->products as $product)
                                <tr class="@if ($product->stock == 0) bg-red-50 @elseif ($product->stock < config('pos.low_stock_threshold', 5)) bg-yellow-50 @endif">
                                    <td class="px-4 py-3 font-mono text-xs">{{ $product->code }}</td>
                                    <td class="px-4 py-3 font-medium text-gray-900">{{ $product->name }}</td>
                                    <td class="px-4 py-3 text-right">Rp{{ number_format($product->price, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3 text-center font-semibold">
                                        {{ $product->stock }} {{ $product->unit }}
                                        @if ($product->stock == 0)
                                            <span class="text-red-600 text-xs block font-normal">Habis</span>
                                        @elseif ($product->stock < config('pos.low_stock_threshold', 5))
                                            <span class="text-yellow-600 text-xs block font-normal">Menipis</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-right font-medium">
                                        Rp{{ number_format($product->stock * $product->price, 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-3 text-center space-x-2">
                                        <a href="{{ route('products.show', $product) }}" class="text-indigo-600 hover:text-indigo-800">
                                            Detail
                                        </a>
                                        <a href="{{ route('products.edit', $product) }}" class="text-gray-600 hover:text-gray-800">
                                            Ubah
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                                        Belum ada produk dalam kategori ini.
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

