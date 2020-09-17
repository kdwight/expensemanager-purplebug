<?php

namespace App\Http\Controllers;

use App\Expense;
use Illuminate\Http\Resources\Json\JsonResource;

class ExpenseController extends Controller
{
    public function index()
    {
        return view('expenses.index');
    }

    public function store()
    {
        $attr = request()->validate([
            'category_id' => 'required|exists:categories,id',
            'amount' => "required|numeric|min:1|max:9999999999.99",
            'entry_date' => ['required', 'date', 'before_or_equal:' . date('Y-m-d')]
        ]);

        Expense::create(array_merge($attr, [
            'user_id' => auth()->id()
        ]));

        return response(["success" => "Expense successfully created."], 200);
    }

    public function update(Expense $expense)
    {
        $attr = request()->validate([
            'category_id' => 'required|exists:categories,id',
            'amount' => 'required|numeric',
            'entry_date' => 'required|date'
        ]);

        $expense->update($attr);

        return response(['success' => 'Expense successfully updated.'], 200);
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();

        return response(['success' => 'Expense successfully deleted.'], 200);
    }

    public function records()
    {
        if (auth()->user()->role_id == 1) {
            $query = Expense::with(['category', 'user'])->orderBy(request('column'), request('order'));
        } else {
            $query = auth()->user()->expenses()->with('category')->orderBy(request('column'), request('order'));
        }

        return JsonResource::collection($query->paginate(5));
    }
}
