
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800">Edit Produk</h2>
        </div>
    </x-slot>
    <x-card>
<form action="{{ route('products.update', $product) }}" method="POST" class="space-y-4">
    @csrf
    @method('PUT')
    <div>
        <label class="block text-sm font-medium text-gray-700">Nama Produk</label>
        <input type="text" name="name" value="{{ old('name', $product->name) }}"
            class="mt-1 block w-full rounded-md border-gray-300">
    </div>
    <!-- field lain mengikuti pola yang sama -->
    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md">Simpan Perubahan</button>
</form>
</x-card>
</x-app-layout>
