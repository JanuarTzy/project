<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;

class CategoryController extends Controller
{
    /**
     * Menampilkan daftar kategori dengan paginasi.
     */
    public function index()
    {
        $categories = Category::latest()->paginate(10);
        return view('master-data.category.index', compact('categories'));
    }

    /**
     * Menampilkan form pembuatan kategori baru.
     */
    public function create()
    {
        return view('master-data.category.create');
    }

    /**
     * Menyimpan kategori baru ke database menggunakan Form Request.
     */
    public function store(StoreCategoryRequest $request)
    {
        Category::create($request->validated());

        return redirect()
            ->route('categories.index')
            ->with('success', 'Kategori berhasil ditambahkan.');
    }
}