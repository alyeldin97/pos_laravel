<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\Category;
use CodeZero\UniqueTranslation\UniqueTranslationRule;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoryConroller extends Controller
{

    public function index(Request $request)
    {
        $categories = Category::when($request->search, function ($q) use ($request) {
            return $q->where('name', 'like', '%' . $request->search . '%');
        })->latest()->paginate(10);

        return view('dashboard.categories.index', compact('categories'));
    } //end of index


    public function create()
    {
        return view('dashboard.categories.create');
    } //end of create


    public function store(Request $request)
    {
        $request->validate([
            'name.*' => 'required|unique_translation:categories',

        ]);

        Category::create(
            $request->all()

        );


        session()->flash('success', __('site.added_successfully'));
        return redirect()->route('dashboard.categories.index');
    }


    public function show(Category $category)
    {
        //
    }


    public function edit(Category $category)
    {
        return view('dashboard.categories.edit', compact('category'));
    }


    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name.*' => ['required',UniqueTranslationRule::for('categories','name')->ignore($category->id)],

        ]);

        $category->update($request->all());
        session()->flash('success', __('site.updated_successfully'));
        return redirect()->route('dashboard.categories.index');
    }


    public function destroy(Category $category)
    {
        $category->delete();

        session()->flash('success', __('site.deleted_successfully'));

        return redirect()->route('dashboard.categories.index');
    }
}
