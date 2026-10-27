<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Http\Requests\StoreCategoryRequest;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::withCount('products')->latest()->paginate(10);

        return view('master-data.category.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
         return view('master-data.category.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
    Category::create($request->validated());

    return redirect()
        ->route('categories.index')
        ->with('success', 'Kategori berhasil ditambahkan.');
    }
    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        $category->load('products');

        $totalStock = $category->products->sum('stock');
        $totalNilaiStock = $category->products->sum(function ($product) {
            return $product->stock * $product->price;
        });

        return view('master-data.category.show', compact('category', 'totalStock', 'totalNilaiStock'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        //
    }
}
