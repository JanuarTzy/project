<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800">
                Data Kategori
            </h2>

            <a href="{{ route('categories.create') }}"
                class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm">
                + Tambah Kategori
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
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">
                                Nama Kategori
                            </th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">
                                Deskripsi
                            </th>
                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-500">
                                Jumlah Produk
                            </th>
                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-500">
                                Aksi
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">
                        @forelse ($categories as $category)
                            <tr>
                                <td class="px-4 py-2 text-sm">
                                    {{ $category->name }}
                                </td>

                                <td class="px-4 py-2 text-sm">
                                    {{ $category->description ?: '-' }}
                                </td>

                                <td class="px-4 py-2 text-sm text-center font-semibold">
                                    {{ $category->products_count }}
                                </td>

                                <td class="px-4 py-2 text-sm text-center">
                                    <a href="{{ route('categories.edit', $category) }}"
                                        class="text-indigo-600 hover:text-indigo-800">
                                        Ubah
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5"
                                    class="px-4 py-6 text-center text-gray-500">
                                    Belum ada data kategori.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $categories->links() }}
                </div>
            </x-card>
        </div>
    </div>
</x-app-layout>