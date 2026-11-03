<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Struk Transaksi</h2>
    </x-slot>

    {{-- Gaya cetak khusus @media print --}}
    <style>
        @media print {
            /* Sembunyikan navigasi, header halaman, tombol aksi, dan elemen non-struk */
            nav, header, .no-print {
                display: none !important;
            }

            body {
                background: white !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            .struk-container {
                box-shadow: none !important;
                border: none !important;
                max-width: 100% !important;
                width: 80mm !important; /* Standar lebar kertas thermal POS */
                margin: 0 auto !important;
                padding: 10px !important;
            }

            main {
                padding: 0 !important;
                margin: 0 !important;
            }
        }
    </style>

    <div class="py-8">
        <div class="max-w-md mx-auto sm:px-6">
            <x-card class="struk-container">
                <p class="text-center font-bold text-lg">POS Barokah Mart</p>
                <p class="text-center text-xs text-gray-500 mb-2">{{ $transaction->invoice_number }}</p>
                <p class="text-xs text-gray-500 mb-3 text-center">
                    {{ $transaction->created_at->format('d M Y H:i') }} &middot; Kasir: {{ $transaction->user->name }}
                </p>
                <hr class="mb-3 border-dashed">

                @foreach ($transaction->details as $detail)
                    <div class="flex justify-between text-sm mb-1">
                        <span>{{ $detail->product->name ?? 'Produk' }} x{{ $detail->qty }}</span>
                        <span>Rp{{ number_format($detail->subtotal, 0, ',', '.') }}</span>
                    </div>
                @endforeach

                <hr class="my-3 border-dashed">
                <div class="flex justify-between font-semibold">
                    <span>Total</span>
                    <span>Rp{{ number_format($transaction->total, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span>Bayar</span>
                    <span>Rp{{ number_format($transaction->pay, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span>Kembali</span>
                    <span>Rp{{ number_format($transaction->change, 0, ',', '.') }}</span>
                </div>

                {{-- Area tombol yang disembunyikan saat dicetak --}}
                <div class="no-print mt-6 space-y-2">
                    <button type="button" onclick="window.print()"
                        class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-medium py-2 rounded-md shadow flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4H7v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                        Cetak Struk
                    </button>

                    <a href="{{ route('pos.index') }}"
                        class="block text-center bg-indigo-600 hover:bg-indigo-700 text-white py-2 rounded-md">
                        Transaksi Baru
                    </a>

                    <a href="{{ route('pos.history') }}"
                        class="block text-center text-sm text-gray-600 hover:text-gray-900 py-1">
                        &larr; Riwayat Transaksi Saya
                    </a>
                </div>
            </x-card>
        </div>
    </div>
</x-app-layout>
