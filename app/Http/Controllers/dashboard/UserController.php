<?php

namespace App\Http\Controllers\dashboard;

use App\Models\User;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Intervention\Image\Facades\Image;


class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read_users')->only('index');
        $this->middleware('permission:create_users')->only('create');
        $this->middleware('permission:update_users')->only(['edit', 'update']);
        $this->middleware('permission:delete_users')->only('destroy');
    }

    public function index(Request $request)
    {   //WHEN KEDA BA@ET KA@ENA orWHERE
        // $users = User::whereRoleIs('admin')->when($request->search,function ($q) use ($request){
        //     return $q->where('first_name','like','%'.$request->search.'%')->orWhere('last_name','like','%'.$request->search.'%');
        // })->latest()->paginate(2);

        //! di el sa7
        $users = User::whereRoleIs('admin')->where(function ($q) use ($request) {
            return $q->when($request->search, function ($q) use ($request) {
                return $q->where('first_name', 'like', '%' . $request->search . '%')->orWhere('last_name', 'like', '%' . $request->search . '%');
            });
        })->latest()->paginate(10);

        return view('dashboard.users.index', compact('users'));
    }


    public function create()
    {
        return view('dashboard.users.create');
    }
    public function show(Request $request)
    {
    }


    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required',
            'image' => 'image',
            'password' => 'required|confirmed',
            'permissions' => 'required',


        ]);

        $requestData = $request->except(['password', 'password_confirmation', 'prmissions', 'image']);
        $requestData['password'] = bcrypt($request->password);
        if ($request->image) {

            Image::make($request->image)->resize(300, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save(public_path('uploads/user_images/' . $request->image->hashName()));

            $requestData['image'] = $request->image->hashName();
        }

        $user = User::create($requestData);
        $user->attachRole('admin');
        $user->syncPermissions($request->permissions);
        session()->flash('success', __('site.added_successfully'));

        return redirect()->route('dashboard.users.index');
    }






    public function edit(User $user)
    {
        return view('dashboard.users.edit', compact('user'));
    }


    public function update(Request $request, User $user)
    {
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => ['required', Rule::unique('users', 'email')->ignore($user->id)],
            'image' => 'image',
            'permissions' => 'required',



        ]);

        $requestData = $request->except(['permissions', 'image']);
        if ($request->image) {
            if ($request->image != 'default.png') {
                Storage::disk('public_uploads')->delete('/' . 'user_images/' . $user->image);
            }

            Image::make($request->image)->resize(300, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save(public_path('uploads/user_images/' . $request->image->hashName()));

            $requestData['image'] = $request->image->hashName();
        }

        $user->update($requestData);
        $user->syncPermissions($request->permissions);
        session()->flash('success', __('site.updated_successfully'));
        return redirect()->route('dashboard.users.index');
    }


    public function destroy(User $user)

    {
        if ($user->image != 'default.png') {
            Storage::disk('public_uploads')->delete('/' . 'user_images/' . $user->image);
        }
        $user->delete();
        session()->flash('success', __('site.deleted_successfully'));
        return redirect()->route('dashboard.users.index');
    }
}
