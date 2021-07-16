<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{
    //register

    public function register(Request $request){
        // validate fields

        $attrs = request()->validate([
            'name1'=> 'required|string',
            'email'=> 'required|email|unique:users,email',
            'password'=> 'required|min:6|confirmed'

        ]);

        //create user

        $user = User::create([
            'name'=>$attrs['name1'],
            'email'=>$attrs['email'],
            'password'=>Hash::make($attrs['password']),
        ]);

        //return user and token in response

        return response([
            'email'=>$user,
            'token'=>$user->createToken('password')->plainTextToken,


        ],200);

    }



    //login user

    public function login(Request $request){
        // validate fields
        $attrs = request()->validate([
            'email'=> 'required|email',
            'password'=> 'required|min:6'

        ]);

        //attempt login

        if(!Auth::attempt($attrs)){
            return response([
                'message'=>'les informations d\'identification invalides'
            ], 403);
        }


        //return user and token in response

        return response([
            'email'=>auth()->user(),
            'token'=>auth()->user()->plainTextToken,


        ],200);
    }

    //user logout
    public function logout(){
        auth()->user()->tokens()->delete();

        return response([
            'message'=> 'déconnexion effectuée avec succès'
        ],200);
    }

    //get user details

    public function user(){
        return response([
            'user'=>auth()->user(),
        ],200);
    }


}


