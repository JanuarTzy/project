<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Struk Transaksi</h2>
    </x-slot>

    {{-- Gaya cetak khusus @media print (Mendukung Thermal Printer 58mm & 80mm) --}}
    <style>
        @media print {
            /* Sembunyikan seluruh komponen luar: navigasi, header halaman, footer, tombol */
            nav, header, footer, .no-print {
                display: none !important;
            }

            @page {
                size: 58mm auto; /* Format lebar kertas thermal printer 58mm */
                margin: 0mm;
            }

            html, body {
                background: #ffffff !important;
                color: #000000 !important;
                margin: 0 !important;
                padding: 0 !important;
                font-family: 'Courier New', Courier, monospace, -apple-system, sans-serif !important;
                font-size: 11px !important;
                line-height: 1.25 !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            main {
                padding: 0 !important;
                margin: 0 !important;
            }

            .struk-container {
                box-shadow: none !important;
                border: none !important;
                width: 58mm !important;
                max-width: 58mm !important;
                margin: 0 auto !important;
                padding: 3mm !important;
            }

            .thermal-divider {
                border-top: 1px dashed #000000 !important;
                margin: 4px 0 !important;
            }
        }
    </style>

    <div class="py-8">
        <div class="max-w-md mx-auto sm:px-6">
            <x-card class="struk-container bg-white">
                <div class="text-center">
                    <p class="font-bold text-base tracking-wide uppercase">POS Barokah Mart</p>
                    <p class="text-xs text-gray-500">Jl. Raya Barokah No. 123</p>
                    <p class="text-xs text-gray-500">Telp: 0812-3456-7890</p>
                    <p class="font-mono text-xs text-gray-600 mt-1 font-semibold">{{ $transaction->invoice_number }}</p>
                    <p class="text-[11px] text-gray-500 mt-0.5">
                        {{ $transaction->created_at->format('d/m/Y H:i') }} | Kasir: {{ $transaction->user->name }}
                    </p>
                </div>

                <hr class="my-2 border-dashed border-gray-400 thermal-divider">

                {{-- Daftar Item Belanja --}}
                <div class="space-y-1 text-xs font-mono">
                    @foreach ($transaction->details as $detail)
                        <div>
                            <div class="font-medium text-gray-900 truncate">
                                {{ $detail->product->name ?? 'Produk' }}
                            </div>
                            <div class="flex justify-between text-gray-600 text-[11px]">
                                <span>{{ $detail->qty }} x Rp{{ number_format($detail->price, 0, ',', '.') }}</span>
                                <span class="font-semibold text-gray-900">Rp{{ number_format($detail->subtotal, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>

                <hr class="my-2 border-dashed border-gray-400 thermal-divider">

                {{-- Total & Pembayaran --}}
                <div class="text-xs font-mono space-y-1">
                    <div class="flex justify-between font-bold text-sm text-gray-900">
                        <span>TOTAL</span>
                        <span>Rp{{ number_format($transaction->total, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-gray-700">
                        <span>TUNAI / BAYAR</span>
                        <span>Rp{{ number_format($transaction->pay, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-gray-700">
                        <span>KEMBALIAN</span>
                        <span>Rp{{ number_format($transaction->change, 0, ',', '.') }}</span>
                    </div>
                </div>

                <hr class="my-2 border-dashed border-gray-400 thermal-divider">

                <div class="text-center text-[10px] text-gray-500 font-mono mt-2">
                    <p>Terima Kasih Atas Kunjungan Anda</p>
                    <p>Barang yang sudah dibeli tidak dapat ditukar</p>
                </div>

                {{-- Area tombol aksi (disembunyikan saat dicetak) --}}
                <div class="no-print mt-6 space-y-2 border-t pt-4">
                    <button type="button" onclick="window.print()"
                        class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-medium py-2 rounded-md shadow flex items-center justify-center gap-2 text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4H7v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                        Cetak Struk (Thermal 58mm)
                    </button>

                    <a href="{{ route('pos.index') }}"
                        class="block text-center bg-indigo-600 hover:bg-indigo-700 text-white py-2 rounded-md text-sm font-medium">
                        + Transaksi Baru
                    </a>

                    <a href="{{ route('pos.history') }}"
                        class="block text-center text-xs text-gray-600 hover:text-gray-900 py-1">
                        &larr; Riwayat Transaksi Saya
                    </a>
                </div>
            </x-card>
        </div>
    </div>
</x-app-layout>
