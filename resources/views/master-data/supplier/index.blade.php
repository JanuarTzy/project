<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800">Data Supplier</h2>
            <a href="{{ route('suppliers.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                + Tambah Supplier
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

                {{-- Pencarian Supplier --}}
                <form method="GET" action="{{ route('suppliers.index') }}" class="flex gap-3 mb-4">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari nama, nomor telepon, atau email supplier..."
                        class="flex-1 rounded-md border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm">
                        Cari
                    </button>
                    @if (request('search'))
                        <a href="{{ route('suppliers.index') }}" class="px-4 py-2 text-sm text-gray-500 hover:text-gray-700">
                            Reset
                        </a>
                    @endif
                </form>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left font-medium text-gray-500">Nama Supplier</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-500">Kontak Telepon</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-500">Email</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-500">Alamat</th>
                                <th class="px-4 py-3 text-center font-medium text-gray-500">Total Transaksi PO</th>
                                <th class="px-4 py-3 text-center font-medium text-gray-500">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($suppliers as $supplier)
                                <tr>
                                    <td class="px-4 py-3 font-semibold text-gray-900">
                                        {{ $supplier->name }}
                                    </td>
                                    <td class="px-4 py-3 text-gray-600">
                                        {{ $supplier->phone ?: '-' }}
                                    </td>
                                    <td class="px-4 py-3 text-gray-600">
                                        {{ $supplier->email ?: '-' }}
                                    </td>
                                    <td class="px-4 py-3 text-gray-600 max-w-xs truncate">
                                        {{ $supplier->address ?: '-' }}
                                    </td>
                                    <td class="px-4 py-3 text-center font-medium text-indigo-600">
                                        {{ $supplier->purchases_count }} kali
                                    </td>
                                    <td class="px-4 py-3 text-center space-x-2">
                                        <a href="{{ route('suppliers.show', $supplier) }}" class="text-indigo-600 hover:text-indigo-800">
                                            Detail
                                        </a>
                                        <a href="{{ route('suppliers.edit', $supplier) }}" class="text-gray-600 hover:text-gray-800">
                                            Ubah
                                        </a>
                                        <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST"
                                              onsubmit="return confirm('Yakin ingin menghapus supplier {{ $supplier->name }}?')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800">
                                                Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                                        Belum ada data supplier yang tersimpan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $suppliers->links() }}
                </div>
            </x-card>
        </div>
    </div>
</x-app-layout>

