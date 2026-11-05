
<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">Dashboard</h2></x-slot>
 
    <div class="py-8 space-y-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-4 gap-4">
            <x-card>
                <p class="text-xs text-gray-500">Omzet Hari Ini</p>
                <p class="text-xl font-semibold">Rp{{ number_format($omzetHariIni, 0, ',', '.') }}</p>
            </x-card>
            <x-card>
                <p class="text-xs text-gray-500">Omzet Kemarin</p>
                <p class="text-xl font-semibold">Rp{{ number_format($omzetKemarin, 0, ',', '.') }}</p>
            </x-card>
            <x-card>
                <p class="text-xs text-gray-500">Transaksi Hari Ini</p>
                <p class="text-xl font-semibold">{{ $transaksiHariIni }}</p>
            </x-card>
            <x-card>
                <p class="text-xs text-gray-500">Produk Stok Menipis</p>
                <p class="text-xl font-semibold text-yellow-600">{{ $produkMenipis }}</p>
            </x-card>
        </div>
 
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-3 gap-6">
            <x-card class="col-span-2">
                <h3 class="font-semibold mb-3">Tren Penjualan 7 Hari Terakhir</h3>
                <canvas id="chartTren" height="120"></canvas>
            </x-card>
 
            <x-card>
                <h3 class="font-semibold mb-3">Produk Terlaris</h3>
                <ol class="list-decimal list-inside space-y-1 text-sm">
                    @foreach ($produkTerlaris as $produk)
                        <li>{{ $produk->name }} <span class="text-gray-500">({{ $produk->total_qty }} terjual)</span></li>
                    @endforeach
                </ol>
            </x-card>
        </div>
    </div>
 
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (window.Chart && document.getElementById('chartTren')) {
                new window.Chart(document.getElementById('chartTren'), {
                    type: 'line',
                    data: {
                        labels: @json($labelTren),
                        datasets: [{
                            label: 'Penjualan (Rp)',
                            data: @json($dataTren),
                            borderColor: '#4f46e5',
                            backgroundColor: 'rgba(79, 70, 229, 0.1)',
                            fill: true,
                            tension: 0.3,
                        }],
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return 'Rp' + Number(value).toLocaleString('id-ID');
                                    }
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>
    @endpush
</x-app-layout>