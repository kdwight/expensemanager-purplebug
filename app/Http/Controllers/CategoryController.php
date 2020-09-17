<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryController extends Controller
{
    public function index()
    {
        return view('expenses.categories');
    }

    public function store()
    {
        $attr = request()->validate([
            'name' => 'required',
            'description' => 'required',
        ]);

        Category::create($attr);

        return response(['success' => 'Category successfully created.'], 200);
    }

    public function update(Category $category)
    {
        $attr = request()->validate([
            'name' => 'required',
            'description' => 'required',
        ]);

        $category->update($attr);


        return response(['success' => 'Category successfully updated.'], 200);
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return response(['success' => 'Category successfully deleted.'], 200);
    }

    public function list()
    {
        return response(Category::all(), 200);
    }

    public function records()
    {
        $query = Category::orderBy(request('column'), request('order'));

        return JsonResource::collection($query->paginate(5));
    }
}
