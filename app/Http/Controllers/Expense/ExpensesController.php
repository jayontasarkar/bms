<?php

namespace App\Http\Controllers\Expense;

use App\Http\Controllers\Controller;
use App\Http\Filters\ExpenseFilter;
use App\Http\Requests\ExpenseFormRequest;
use App\Models\Expense;
use Excel;
use PDF;
use App\Exports\ExpensesExport;
use Illuminate\Http\Request;

class ExpensesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }   
     
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ExpenseFilter $filters)
    {
        $query = Expense::filter($filters)->with('vendor')->latest();
        $counter['total'] = $query->get()->sum('amount');
        $counter['qty'] = $query->count();
        $expenses = Expense::filter($filters)->orderBy('expense_date', 'desc')->paginate(config('bms.items_per_page'));

        return view('expense.index', compact('expenses', 'counter'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ExpenseFormRequest $request)
    {
        $expense = Expense::create(array_merge($request->all(), ['user_id' => auth()->id()]));

        session()->flash('flash', 'Expense item created');
        return response()->json(['msg' => 'Expense created']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function update(ExpenseFormRequest $request, Expense $expense)
    {
        $expense->update($request->all());

        session()->flash('flash', 'Expense item updated successfully');
        return response()->json(['msg' => 'Expense updated']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Expense $expense)
    {
        $expense->delete();
        session()->flash('flash', 'Expense item removed successfully');
        return response()->json(['msg' => 'Expense item removed']);
    }

    public function excel()
    {
        return Excel::download(new ExpensesExport, search_options() . ' - Expense Bill.xlsx');
    }

    public function pdf(ExpenseFilter $filters)
    {
        $expenses = Expense::filter($filters)->select('title', 'expense_date', 'amount')->latest()->get();
        $pdf = PDF::loadView('export.pdf.expense', compact('expenses'));

        return $pdf->download(search_options() . '_' . time() . '.pdf');
    }
}
