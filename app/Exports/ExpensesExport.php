<?php

namespace App\Exports;

use App\Models\Expense;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ExpensesExport implements FromView, ShouldAutoSize
{
    public function view(): View
    {
        $filters = app()->make('App\Http\Filters\ExpenseFilter');
        $expenses = Expense::filter($filters)->select('title', 'expense_date', 'amount')->latest()->get();
        return view('export.excel.expense', [
            'expenses' => $expenses
        ]);
    }
}
