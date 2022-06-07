<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
   
    public function index()
    {
        $clients = Client::latest()->paginate(5);
        return view('dashboard.clients.index', compact('clients'));
    }

   
    public function create()
    {
        return view('dashboard.clients.create');
        
    }

 
    public function store(Request $request)
    {
        $request->validate([
            'name'=>['required'],
            'phone'=>['required','array','min:1'],
            'phone.0'=>['required'],

            'address'=>['required'],

        ]);
        $requestData=$request->except('phone');
        $requestData['phone']=array_filter($request->phone);

        Client::create($requestData);
        session()->flash('success','site.created.successfully');
        return redirect()->route('dashboard.clients.index');
    }

   
    public function show(Client $client)
    {
        //
    }

    public function edit(Client $client)
    {
        return view('dashboard.clients.edit',compact('client'));
        
    }

 
    public function update(Request $request, Client $client)
    {
        $request->validate([
            'name'=>['required'],
            'phone'=>['required','array','min:1'],
            'phone.0'=>['required'],
            'address'=>['required'],

        ]);
        $requestData=$request->except('phone');
        $requestData['phone']=array_filter($request->phone);

        $client->update($requestData);
        session()->flash('success','site.updated.successfully');
        return redirect()->route('dashboard.clients.index');
    }

  
    public function destroy(Client $client)
    {   $client->delete();
        session()->flash('success','site.deleted.successfully');
        return redirect()->route('dashboard.clients.index');
    }
}
