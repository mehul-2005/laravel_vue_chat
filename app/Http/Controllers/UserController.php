<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        return User::with(['roles', 'permissions'])->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'role' => 'required',
            'permission' => 'required'
        ]);


        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name = time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/storage/image/admin/user');
            $image->move($destinationPath, $name);
            $data['image'] = '/image/admin/user/' . $name;
        }

        $data['name'] = $request->name;
        $data['email'] = $request->email;
        $data['password'] = bcrypt($request->password);

        $user = User::create($data);
        $user->roles()->sync(explode(',', $request->role));
        $user->permissions()->sync(explode(',', $request->permission));
        return $user;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return $user;
    }

    public function update(Request $request, User $user)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'image' => $request->hasFile('image') ? 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048' : '',
            'role' => 'required',
            'permission' => 'required'
        ]);

        if ($request->hasFile('image')) {
            if ($user->image && file_exists('storage/' . $user->image)) {
                unlink('storage/' . $user->image);
            }
            $image = $request->file('image');
            $name = time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/storage/image/admin/user');
            $image->move($destinationPath, $name);
            $data['image'] = '/image/admin/user/' . $name;
        }

        $data['name'] = $request->name;
        $data['email'] = $request->email;

        $user->update($data);
        $user->roles()->sync(explode(',', $request->role));
        $user->permissions()->sync(explode(',', $request->permission));
        return $user;
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if ($user->image && file_exists('storage/' . $user->image)) {
            unlink('storage/' . $user->image);
        }
        return $user->delete();
    }
}
