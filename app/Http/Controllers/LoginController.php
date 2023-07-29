<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8'
        ]);
        $email = $request->email;
        $password = $request->password;

        if (Auth::attempt(['email' => $email, 'password' => $password])) {
            $user = Auth::user();
            $data['user'] = $user;
            $token =  $user->createToken($user->email);
            $data['token'] = $token->plainTextToken;
            return response()->json($data, 200);
        } else {
            return response()->json(['invalid' => 'Email or password is invalid'], 401);
        }
    }
    public function Register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'retypePassword' => 'required|min:8|same:password'
        ]);
        $data['name'] = $request->name;
        $data['email'] = $request->email;
        $data['password'] = bcrypt($request->password);
        $user = User::create($data);
        $user->roles()->sync(2);
        $user->permissions()->sync([1, 2]);
        return response()->json(201);
    }
    public function logout()
    {
        $id = Auth::user()->id;
        $data['online_status'] = date('Y-m-d H:i:s');
        broadcast(new  \App\Events\OnlineReport('true'));
        $user = User::find($id);
        $user->update($data);
        Auth::logout();
        return response()->json(['success' => 'success'], 200);
    }
}
