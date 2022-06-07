<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use CodeZero\UniqueTranslation\UniqueTranslationRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Intervention\Image\Facades\Image;


class ProductController extends Controller
{
  
    public function index(Request $request)
    {   
        $categories= Category::all();
        $products=Product::when($request->search, function ($q) use ($request) {
            return $q->where('name', 'like', '%' . $request->search . '%');
        })->when($request->category_id,function ($query) use ($request){
            return $query->where('category_id',$request->category_id);
        })->latest()->paginate(10);
        return view('dashboard.products.index',compact('categories','products'));
    }

 
    public function create()
    {   
        $categories= Category::all();
        return view('dashboard.products.create',compact('categories'));
        
    }

    public function store(Request $request)
    {
        $request->validate([
            'name.*' => 'required|unique_translation:products',
            'description.*' => 'required|unique_translation:products',
            'stock'=>'required',
            'purchase_price'=>'required',
            'sale_price'=>'required',
            'category_id'=>'required',
            

        ]);
        $requestData=$request->except('image');
        if ($request->image) {

            Image::make($request->image)->resize(300, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save(public_path('uploads/product_images/' . $request->image->hashName()));

            $requestData['image'] = $request->image->hashName();
        }

        Product::create($requestData);
        
        session()->flash('success', __('site.added_successfully'));
        return redirect()->route('dashboard.products.index');
    }

  
    public function show(Product $product)
    {
        //
    }

 
    public function edit(Product $product)
    {
        $categories= Category::all();

        return view('dashboard.products.edit',compact('product','categories'));
    }

  
    public function update(Request $request, Product $product)
    {   
        if ($request->image != 'default.png') {
            Storage::disk('public_uploads')->delete('/' . 'product_images/' . $product->image);
        }
        $request->validate([
            'name.*' => ['required',UniqueTranslationRule::for('products','name')->ignore($product->id)],
            'description.*' => ['required',UniqueTranslationRule::for('products','description')->ignore($product->id)],
            'stock'=>'required',
            'purchase_price'=>'required',
            'sale_price'=>'required',
            'category_id'=>'required',
            

        ]);
        $requestData=$request->except('image');
        if ($request->image) {

            Image::make($request->image)->resize(300, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save(public_path('uploads/product_images/' . $request->image->hashName()));

            $requestData['image'] = $request->image->hashName();
        }

        $product->update($requestData);
        
        session()->flash('success', __('site.updated_successfully'));
        return redirect()->route('dashboard.products.index');
        
    }


    public function destroy(Product $product)
    {   
        if ($product->image != 'default.png') {
            Storage::disk('public_uploads')->delete('/' . 'product_images/' . $product->image);
        }
        $product->delete();
        session()->flash('success', __('site.deleted_successfully'));
        return redirect()->route('dashboard.products.index');
    }
}
