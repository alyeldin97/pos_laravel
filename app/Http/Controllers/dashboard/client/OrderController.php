<?php

namespace App\Http\Controllers\dashboard\client;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Client;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
   

    public function create(Client $client)
    {
        $categories = Category::all();
        $orders=$client->orders()->with('products')->paginate(5);
        return view('dashboard.clients.orders.create', compact('categories', 'client','orders'));
    }


    public function store(Request $request, Client $client)
    {
        $request->validate([
            'products' => ['required', 'array'],
        ]);
        $order = $client->orders()->create([
            'client_id' => $client->id,
        ]);
        $totalPrice = 0;
         

        $order->products()->attach($request->products);

        foreach ($request->products as $id => $data) {
            $quantity = $data['quantity'];
            $product = Product::findOrFail($id);
            $product->update([
                'stock' => $product->stock - $quantity,
            ]);
            $totalPrice += $product->sale_price * $quantity;
        }

        $order->update(['total_price' => $totalPrice]);
        session()->flash('success',__('site.added_successfully'));
        return redirect()->route('dashboard.orders.index');
    }


  


    public function edit(Client $client, Order $order)
    {   $categories=Category::all();
        $orders=$client->orders()->with('products')->paginate(5);

        return view('dashboard.clients.orders.edit',compact('client','order','categories','orders'));
    }


    public function update(Request $request, Client $client, Order $order)
    {   
        $request->validate([
            'products'=>['required','array'],
        ]);

        foreach($order->products as $product){

            $quantity=$product->pivot->quantity;

            $product->update([
                'stock'=>($product->stock + $quantity),
            ]);
        }


        $order->delete();

        $totalPrice = 0;
        $order=$client->orders()->create([
            'client_id' => $client->id,
        ]);
         

        $order->products()->attach($request->products);

        foreach ($request->products as $id => $data) {
            $quantity = $data['quantity'];
            $product = Product::findOrFail($id);
            $product->update([
                'stock' => $product->stock - $quantity,
            ]);
            $totalPrice += $product->sale_price * $quantity;
        }

        $order->update(['total_price' => $totalPrice]);

        session()->flash('success',__('site.updated_successfully'));
        return redirect()->route('dashboard.orders.index');
    }




  
}
