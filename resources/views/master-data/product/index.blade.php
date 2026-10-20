<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800">Data Produk</h2>
            <a href="{{ route('products.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm">
                + Tambah Produk
            </a>
        </div>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-card>
                @if (session('success'))
                    <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-md text-sm">
                        {{ session('success') }}
                    </div>
                @endif

                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Kode</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Nama Produk</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Kategori</th>
                            <th class="px-4 py-2 text-right text-xs font-medium text-gray-500">Harga</th>
                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-500">Stok</th>
                            <th class="px-4 py-2 text-right text-xs font-medium text-gray-500">Total Nilai Stok</th>
                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-500">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($products as $product)
                            <tr
                                class="@if ($product->stock == 0) bg-red-50 @elseif ($product->stock < config('pos.low_stock_threshold')) bg-yellow-50 @endif">
                                <td class="px-4 py-2 text-sm">{{ $product->code }}</td>
                                <td class="px-4 py-2 text-sm">{{ $product->name }}</td>
                                <td class="px-4 py-2 text-sm">{{ $product->category->name }}</td>
                                <td class="px-4 py-2 text-sm text-right">
                                    Rp{{ number_format($product->price, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-2 text-sm text-center font-semibold">
                                    {{ $product->stock }}
                                    @if ($product->stock == 0)
                                        <span class="text-red-600 text-xs block">Habis</span>
                                    @elseif ($product->stock < config('pos.low_stock_threshold'))
                                        <span class="text-yellow-600 text-xs block">Menipis</span>
                                    @endif
                                </td>
                                <td class="px-4 py-2 text-sm text-right">
                                    Rp{{ number_format($product->stock * $product->price, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-2 text-sm text-center space-x-2">
                                    <a href="{{ route('products.edit', $product) }}" class="text-indigo-600">Ubah</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-6 text-center text-gray-500">Belum ada data produk.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-4">{{ $products->links() }}</div>
            </x-card>
        </div>
    </div>
</x-app-layout>