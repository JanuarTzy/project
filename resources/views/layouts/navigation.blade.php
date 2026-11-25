<nav x-data="{ open: false }" class="bg-white border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            <div class="flex items-center space-x-6">
                <a href="{{ route('dashboard') }}"
                    class="font-semibold {{ request()->routeIs('dashboard') ? 'text-indigo-600' : 'text-gray-700' }}">
                    Dashboard
                </a>

                <div class="hidden md:flex items-center space-x-6">
                    {{-- Menu Khusus Admin --}}
                    @if (auth()->user()->role === 'admin')
                        <a href="{{ route('categories.index') }}"
                            class="{{ request()->routeIs('categories.*') ? 'text-indigo-600' : 'text-gray-500 hover:text-gray-700' }}">
                            Kategori
                        </a>
                        <a href="{{ route('products.index') }}"
                            class="{{ request()->routeIs('products.*') ? 'text-indigo-600' : 'text-gray-500 hover:text-gray-700' }}">
                            Produk
                        </a>
                        <a href="{{ route('suppliers.index') }}"
                            class="{{ request()->routeIs('suppliers.*') ? 'text-indigo-600' : 'text-gray-500 hover:text-gray-700' }}">
                            Supplier
                        </a>
                        <a href="{{ route('purchases.index') }}"
                            class="{{ request()->routeIs('purchases.*') ? 'text-indigo-600' : 'text-gray-500 hover:text-gray-700' }}">
                            Pembelian Stok (PO)
                        </a>
                        <a href="{{ route('report.sales') }}"
                            class="{{ request()->routeIs('report.sales') ? 'text-indigo-600' : 'text-gray-500 hover:text-gray-700' }}">
                            Laporan
                        </a>
                    @endif

                    {{-- Menu Khusus Kasir --}}
                    @if (auth()->user()->role === 'kasir')
                        <a href="{{ route('pos.history') }}"
                            class="{{ request()->routeIs('pos.history') ? 'text-indigo-600' : 'text-gray-500 hover:text-gray-700' }}">
                            Riwayat Transaksi Saya
                        </a>
                    @endif

                    <a href="{{ route('pos.index') }}"
                        class="{{ request()->routeIs('pos.index') ? 'text-indigo-600' : 'text-gray-500 hover:text-gray-700' }}">
                        Transaksi
                    </a>
                </div>
            </div>

            <div class="hidden md:flex items-center space-x-4 text-sm">
                <span class="text-gray-600">{{ auth()->user()->name }}</span>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="text-gray-500 hover:text-red-600">Keluar</button>
                </form>
            </div>

            <!-- Hamburger button (mobile) -->
            <div class="flex items-center md:hidden">
                <button type="button" @click="open = !open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-500 hover:text-gray-700 hover:bg-gray-100 focus:outline-none"
                    aria-label="Toggle navigation">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div x-show="open" x-transition class="md:hidden border-t border-gray-200 bg-white">
        <div class="px-4 py-3 space-y-2 text-sm">
            @if (auth()->user()->role === 'admin')
                <a href="{{ route('categories.index') }}"
                    class="block py-1.5 {{ request()->routeIs('categories.*') ? 'text-indigo-600 font-medium' : 'text-gray-600' }}">
                    Kategori
                </a>
                <a href="{{ route('products.index') }}"
                    class="block py-1.5 {{ request()->routeIs('products.*') ? 'text-indigo-600 font-medium' : 'text-gray-600' }}">
                    Produk
                </a>
                <a href="{{ route('suppliers.index') }}"
                    class="block py-1.5 {{ request()->routeIs('suppliers.*') ? 'text-indigo-600 font-medium' : 'text-gray-600' }}">
                    Supplier
                </a>
                <a href="{{ route('purchases.index') }}"
                    class="block py-1.5 {{ request()->routeIs('purchases.*') ? 'text-indigo-600 font-medium' : 'text-gray-600' }}">
                    Pembelian Stok (PO)
                </a>
                <a href="{{ route('report.sales') }}"
                    class="block py-1.5 {{ request()->routeIs('report.sales') ? 'text-indigo-600 font-medium' : 'text-gray-600' }}">
                    Laporan
                </a>
            @endif

            @if (auth()->user()->role === 'kasir')
                <a href="{{ route('pos.history') }}"
                    class="block py-1.5 {{ request()->routeIs('pos.history') ? 'text-indigo-600 font-medium' : 'text-gray-600' }}">
                    Riwayat Transaksi Saya
                </a>
            @endif

            <a href="{{ route('pos.index') }}"
                class="block py-1.5 {{ request()->routeIs('pos.index') ? 'text-indigo-600 font-medium' : 'text-gray-600' }}">
                Transaksi
            </a>

            <div class="pt-3 mt-2 border-t border-gray-200 flex items-center justify-between">
                <span class="text-gray-600">{{ auth()->user()->name }}</span>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="text-red-600 font-medium">Keluar</button>
                </form>
            </div>
        </div>
    </div>
</nav>
