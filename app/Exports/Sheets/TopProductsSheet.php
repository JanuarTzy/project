<?php

namespace App\Exports\Sheets;

use App\Models\TransactionDetail;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class TopProductsSheet implements FromCollection, WithHeadings, WithMapping, WithTitle
{
    protected int $rank = 0;

    public function __construct(
        protected $start,
        protected $end,
        protected $userId = null
    ) {}

    public function collection()
    {
        return TransactionDetail::whereHas('transaction', function ($q) {
            $q->whereDate('created_at', '>=', $this->start)
              ->whereDate('created_at', '<=', $this->end);

            if ($this->userId) {
                $q->where('user_id', $this->userId);
            }
        })
        ->select('product_id', DB::raw('SUM(qty) as total_qty'), DB::raw('SUM(subtotal) as total_revenue'))
        ->with('product.category')
        ->groupBy('product_id')
        ->orderByDesc('total_qty')
        ->get();
    }

    public function headings(): array
    {
        return ['Peringkat', 'Kode Produk', 'Nama Produk', 'Kategori', 'Satuan', 'Jumlah Terjual (Qty)', 'Total Pendapatan'];
    }

    public function map($item): array
    {
        $this->rank++;

        return [
            $this->rank,
            $item->product->code ?? '-',
            $item->product->name ?? 'Produk Dihapus',
            $item->product->category->name ?? '-',
            $item->product->unit ?? 'pcs',
            $item->total_qty,
            $item->total_revenue,
        ];
    }

    public function title(): string
    {
        return 'Rekap Produk Terlaris';
    }
}

