<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use App\Models\Expense;

class ExpenseExport implements FromCollection
{
    use Exportable;

    private $filters;

    public function __construct($filters)
    {
        $this->filters = $$filters;
    }

    public function collection()
    {
        return Expense::filter($this->filters)->latest()->get();
    }
}