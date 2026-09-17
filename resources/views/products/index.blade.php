<table class="w-full">
    <thead>
        <tr>
            <th>Nama Barang</th>
            <th>Stok</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($products as $product)
            <tr>
                <td>{{ $product->name }}</td>
                <td>{{ $product->stock }}</td>
                <td>
                    {{-- Menggunakan x-badge secara dinamis --}}
                    @if ($product->stock > 10)
                        <x-badge status="aman" />
                    @elseif ($product->stock > 0)
                        <x-badge status="menipis">Sisa {{ $product->stock }}</x-badge>
                    @else
                        <x-badge status="habis" />
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>