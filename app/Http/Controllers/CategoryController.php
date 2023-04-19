<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|min:2|max:30',
            'type' => 'required',
        ]);

        Category::create([
            'name' => $request->name,
            'type' => $request->type
        ]);

        return response()->json([
            'success' => 'Category Saved Successfully'
        ], 201);
    }
}
