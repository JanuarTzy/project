<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Transaksi Penjualan</h2>
    </x-slot>
 
    <div class="py-8"
         x-data="posCart({{ $products->toJson() }})">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('error'))
                <div class="mb-4 p-4 bg-red-100 border border-red-200 text-red-700 rounded-md text-sm">
                    {{ session('error') }}
                </div>
            @endif
            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-200 text-green-700 rounded-md text-sm">
                    {{ session('success') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-100 border border-red-200 text-red-700 rounded-md text-sm">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-3 gap-6">
 
            <!-- Kolom kiri: pencarian & daftar produk -->
            <div class="col-span-2">
                <x-card>
                    <input type="text" x-model="keyword" placeholder="Cari nama atau kode produk..."
                        class="w-full rounded-md border-gray-300 mb-4">
 
                    <div class="grid grid-cols-3 gap-3 max-h-[420px] overflow-y-auto">
                        <template x-for="product in filteredProducts()" :key="product.id">
                            <button type="button" @click="addItem(product)"
                                class="border rounded-md p-3 text-left hover:bg-indigo-50 transition">
                                <p class="text-sm font-medium text-gray-900" x-text="product.name"></p>
                                <p class="text-xs text-gray-500" x-text="'Stok: ' + product.stock + ' ' + (product.unit || 'pcs')"></p>
                                <p class="text-sm font-semibold text-indigo-600 mt-1" x-text="formatRupiah(product.price)"></p>
                            </button>
                        </template>

                        <div x-show="filteredProducts().length === 0" class="col-span-3 text-center py-8 text-gray-400">
                            Tidak ada produk yang tersedia.
                        </div>
                    </div>
                </x-card>
            </div>
 
            <!-- Kolom kanan: keranjang belanja -->
            <div>
                <x-card>
                    <h3 class="font-semibold mb-3">Keranjang</h3>
 
                    <form :action="'{{ route('pos.store') }}'" method="POST">
                        @csrf
                        <template x-for="(item, index) in cart" :key="item.id">
                            <div class="flex items-center justify-between mb-2 text-sm">
                                <div>
                                    <p x-text="item.name"></p>
                                    <p class="text-xs text-gray-500" x-text="formatRupiah(item.price) + ' x'"></p>
                                </div>
                                <input type="number" min="1" :max="item.stock" x-model.number="item.qty"
                                    @input="clampQty(item)" class="w-16 rounded-md border-gray-300 text-center">
                                <button type="button" @click="removeItem(index)" class="text-red-500 ml-2">&times;</button>
 
                                <input type="hidden" :name="'items[' + index + '][product_id]'" :value="item.id">
                                <input type="hidden" :name="'items[' + index + '][qty]'" :value="item.qty">
                            </div>
                        </template>
 
                        <div class="border-t pt-3 mt-3 space-y-1">
                            <div class="flex justify-between font-semibold">
                                <span>Total</span>
                                <span x-text="formatRupiah(total())"></span>
                            </div>
 
                            <label class="block text-sm mt-2">Bayar</label>
                            <input type="number" name="pay" x-model.number="pay" min="0"
                                class="w-full rounded-md border-gray-300">
 
                            <div class="flex justify-between text-sm mt-1">
                                <span>Kembalian</span>
                                <span x-text="formatRupiah(change())"></span>
                            </div>
                        </div>
 
                        <button type="submit" :disabled="cart.length === 0 || pay < total()"
                            class="w-full mt-4 bg-indigo-600 text-white py-2 rounded-md disabled:opacity-50">
                            Simpan Transaksi
                        </button>
                    </form>
                </x-card>
            </div>
        </div>
    </div>
 
    @push('scripts')
    <script>
        function posCart(products) {
            return {
                products: products,
                cart: [],
                keyword: '',
                pay: 0,
                filteredProducts() {
                    if (!this.keyword) return this.products;
                    const k = this.keyword.toLowerCase();
                    return this.products.filter(p =>
                        p.name.toLowerCase().includes(k) || p.code.toLowerCase().includes(k)
                    );
                },
                addItem(product) {
                    const existing = this.cart.find(i => i.id === product.id);
                    if (existing) {
                        existing.qty++;
                        return;
                    }
                    this.cart.push({ ...product, qty: 1 });
                },
                clampQty(item) {
                    if (item.qty < 1) item.qty = 1;
                },
                removeItem(index) {
                    this.cart.splice(index, 1);
                },
                total() {
                    return this.cart.reduce((sum, i) => sum + (i.price * i.qty), 0);
                },
                change() {
                    return Math.max(this.pay - this.total(), 0);
                },
                formatRupiah(value) {
                    return 'Rp' + Number(value || 0).toLocaleString('id-ID');
                },
            }
        }
    </script>
    @endpush
</x-app-layout>

