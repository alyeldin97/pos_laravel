<?php

namespace App\Http\Controllers\dashboard\client;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {   

        return view('dashboard.clients.orders.index',compact('',''));
    }

   
    public function create(Client $client)
    {
        //
    }

  
    public function store(Request $request,Client $client)
    {
        //
    }

 
    public function show(Order $order)
    {
        //
    }

   
    public function edit(Client $client,Order $order)
    {
        //
    }

  
    public function update(Request $request,Client $client, Order $order)
    {
        //
    }

 
    public function destroy(Client $client,Order $order)
    {
        //
    }
}
