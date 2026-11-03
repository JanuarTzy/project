import Alpine from 'alpinejs';

window.posCart = function(products) {
    return {
        products: products || [],
        cart: [],
        keyword: '',
        pay: 0,
        filteredProducts() {
            if (!this.keyword) return this.products;
            const k = this.keyword.toLowerCase();
            return this.products.filter(p =>
                (p.name && p.name.toLowerCase().includes(k)) ||
                (p.code && p.code.toLowerCase().includes(k))
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
    };
};

window.Alpine = Alpine;
Alpine.start();
