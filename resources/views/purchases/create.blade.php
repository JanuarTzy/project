<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800">Form Pembelian Stok (Purchase Order)</h2>
            <a href="{{ route('purchases.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                &larr; Kembali ke Daftar PO
            </a>
        </div>
    </x-slot>

    <div class="py-12" x-data="purchaseForm({{ $products->toJson() }})">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <x-card>
                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-100 border border-red-200 text-red-700 rounded-md text-sm">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('purchases.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nomor PO <span class="text-red-500">*</span></label>
                            <input type="text" name="po_number" value="{{ old('po_number', $defaultPoNumber) }}" required
                                class="mt-1 block w-full rounded-md border-gray-300 font-mono shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Supplier <span class="text-red-500">*</span></label>
                            <select name="supplier_id" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="" disabled selected>Pilih Supplier</option>
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" @selected(old('supplier_id') == $supplier->id)>
                                        {{ $supplier->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tanggal Pembelian <span class="text-red-500">*</span></label>
                            <input type="date" name="purchase_date" value="{{ old('purchase_date', now()->toDateString()) }}" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                    </div>

                    {{-- Baris Item Produk --}}
                    <div class="border-t pt-4">
                        <div class="flex justify-between items-center mb-3">
                            <h3 class="font-semibold text-gray-800">Daftar Produk yang Dibeli</h3>
                            <button type="button" @click="addRow()"
                                class="bg-indigo-50 text-indigo-700 hover:bg-indigo-100 border border-indigo-200 px-3 py-1.5 rounded-md text-xs font-semibold">
                                + Tambah Baris Produk
                            </button>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 text-sm">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-3 py-2 text-left font-medium text-gray-500">Pilih Produk</th>
                                        <th class="px-3 py-2 text-right font-medium text-gray-500 w-32">Qty Beli</th>
                                        <th class="px-3 py-2 text-right font-medium text-gray-500 w-44">Harga Beli / Satuan (Rp)</th>
                                        <th class="px-3 py-2 text-right font-medium text-gray-500 w-44">Subtotal (Rp)</th>
                                        <th class="px-3 py-2 text-center w-12"></th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    <template x-for="(row, index) in rows" :key="index">
                                        <tr>
                                            <td class="px-3 py-2">
                                                <select :name="'items[' + index + '][product_id]'" x-model.number="row.product_id" required
                                                    class="w-full rounded-md border-gray-300 text-sm">
                                                    <option value="" disabled>-- Pilih Produk --</option>
                                                    @foreach ($products as $p)
                                                        <option value="{{ $p->id }}">{{ $p->code }} - {{ $p->name }} (Stok saat ini: {{ $p->stock }} {{ $p->unit }})</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td class="px-3 py-2">
                                                <input type="number" :name="'items[' + index + '][qty]'" x-model.number="row.qty" min="1" required
                                                    class="w-full rounded-md border-gray-300 text-right text-sm">
                                            </td>
                                            <td class="px-3 py-2">
                                                <input type="number" :name="'items[' + index + '][cost_price]'" x-model.number="row.cost_price" min="0" required
                                                    class="w-full rounded-md border-gray-300 text-right text-sm">
                                            </td>
                                            <td class="px-3 py-2 text-right font-semibold text-gray-900" x-text="formatRupiah(row.qty * row.cost_price)"></td>
                                            <td class="px-3 py-2 text-center">
                                                <button type="button" @click="removeRow(index)" class="text-red-500 hover:text-red-700 font-bold" x-show="rows.length > 1">&times;</button>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                                <tfoot class="border-t bg-gray-50">
                                    <tr>
                                        <td colspan="3" class="px-3 py-3 font-semibold text-right text-gray-700">Total Biaya Pembelian:</td>
                                        <td class="px-3 py-3 text-right font-bold text-lg text-indigo-700" x-text="formatRupiah(grandTotal())"></td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Catatan Tambahan (Opsional)</label>
                        <textarea name="notes" rows="2" placeholder="Catatan pengiriman, faktur supplier, nomor resi, dll..."
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('notes') }}</textarea>
                    </div>

                    <div class="flex items-center gap-3 pt-3">
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-md font-semibold text-sm">
                            Simpan Pembelian & Update Stok
                        </button>
                        <a href="{{ route('purchases.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                            Batal
                        </a>
                    </div>
                </form>
            </x-card>
        </div>
    </div>

    @push('scripts')
    <script>
        function purchaseForm(products) {
            return {
                products: products,
                rows: [
                    { product_id: products.length > 0 ? products[0].id : '', qty: 10, cost_price: 0 }
                ],
                addRow() {
                    this.rows.push({
                        product_id: this.products.length > 0 ? this.products[0].id : '',
                        qty: 1,
                        cost_price: 0
                    });
                },
                removeRow(index) {
                    if (this.rows.length > 1) {
                        this.rows.splice(index, 1);
                    }
                },
                grandTotal() {
                    return this.rows.reduce((sum, r) => sum + ((Number(r.qty) || 0) * (Number(r.cost_price) || 0)), 0);
                },
                formatRupiah(val) {
                    return 'Rp' + Number(val || 0).toLocaleString('id-ID');
                }
            }
        }
    </script>
    @endpush
</x-app-layout>

