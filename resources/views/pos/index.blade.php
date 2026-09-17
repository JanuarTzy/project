<div class="product-card border p-4 rounded-lg">
    <h3 class="font-bold">{{ $product->name }}</h3>
    <p>Rp {{ number_format($product->price) }}</p>

    {{-- Tampilkan badge di bawah harga --}}
    <div class="mt-2">
        <x-badge :status="$product->status_stok" />
    </div>
</div>