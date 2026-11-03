<?php

namespace App\Exports;

use App\Exports\Sheets\TopProductsSheet;
use App\Exports\Sheets\TransactionsSheet;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class SalesExport implements WithMultipleSheets
{
    public function __construct(
        protected $start,
        protected $end,
        protected $userId = null
    ) {}

    /**
     * @return array
     */
    public function sheets(): array
    {
        return [
            new TransactionsSheet($this->start, $this->end, $this->userId),
            new TopProductsSheet($this->start, $this->end, $this->userId),
        ];
    }
}
