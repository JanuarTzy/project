
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Tambah Kategori</h2>
    </x-slot>
 
    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <x-card>
                <form action="{{ route('categories.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama Kategori</label>
                        <input type="text" name="name" value="{{ old('name') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        @error('name')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
                        <textarea name="description" rows="3"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('description') }}</textarea>
                    </div>

                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md">
                        Simpan
                    </button>
                </form>
            </x-card>
        </div>
    </div>
</x-app-layout>
<form action="{{ route('products.store') }}" method="POST" class="space-y-4">
    @csrf
    <div>
        <label class="block text-sm font-medium text-gray-700">Kategori</label>
        <select name="category_id" class="mt-1 block w-full rounded-md border-gray-300">
            <option value="" disabled selected>Pilih kategori</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
        @error('category_id') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Kode Produk</label>
        <input type="text" name="code" value="{{ old('code') }}" class="mt-1 block w-full rounded-md border-gray-300">
        @error('code') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Nama Produk</label>
        <input type="text" name="name" value="{{ old('name') }}" class="mt-1 block w-full rounded-md border-gray-300">
    </div>
    <div class="grid grid-cols-3 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700">Satuan</label>
            <input type="text" name="unit" value="{{ old('unit', 'pcs') }}" class="mt-1 block w-full rounded-md border-gray-300">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Harga</label>
            <input type="number" name="price" value="{{ old('price') }}" class="mt-1 block w-full rounded-md border-gray-300">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Stok Awal</label>
            <input type="number" name="stock" value="{{ old('stock', 0) }}" class="mt-1 block w-full rounded-md border-gray-300">
        </div>
    </div>
    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md">Simpan Produk</button>
</form>




