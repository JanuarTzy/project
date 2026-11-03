<?php

namespace App\Exports\Sheets;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class TransactionsSheet implements FromCollection, WithHeadings, WithMapping, WithTitle
{
    public function __construct(
        protected $start,
        protected $end,
        protected $userId = null
    ) {}

    public function collection()
    {
        $query = Transaction::with('user')
            ->whereDate('created_at', '>=', $this->start)
            ->whereDate('created_at', '<=', $this->end);

        if ($this->userId) {
            $query->where('user_id', $this->userId);
        }

        return $query->latest()->get();
    }

    public function headings(): array
    {
        return ['No. Invoice', 'Tanggal & Waktu', 'Kasir', 'Total Belanja', 'Bayar', 'Kembalian'];
    }

    public function map($transaction): array
    {
        return [
            $transaction->invoice_number,
            $transaction->created_at->format('d-m-Y H:i'),
            $transaction->user->name ?? 'Kasir',
            $transaction->total,
            $transaction->pay,
            $transaction->change,
        ];
    }

    public function title(): string
    {
        return 'Transaksi Penjualan';
    }
}

