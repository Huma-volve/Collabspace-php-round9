<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Trait\ApiResponse;
use GrahamCampbell\ResultType\Success;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    use ApiResponse;
    public function register(Request $request){
        $validated=$request->validate([
            'full_name'=>'required|string',
            'email'=>'required|email|unique:users,email',
            'password'=>'required|min:6',
            'phone'=>'nullable',
            'image'=>'nullable',
            'job_title'=>'nullable',
            'experience'=>'required|in:junior,mid,senior'
        ]);

        $user=User::create($validated);
        $token=$user->createToken('auth_token')->plainTextToken;
        $token=[
            'token'=>$token,
        ];
        return $this->success('Registered Successfully',$token);
    }
    public function login(Request $request){
        $validated=$request->validate([
            'email'=>'required',
            'password'=>'required'
        ]);
        $user=User::where('email',$request->email)->first();
        $token=$user->createToken('auth_token')->plainTextToken;
        if(!$user || !Hash::check($request->password,$user->password)){
            return $this->error('This credentials is false');
        }
        $token=[
            'token'=>$token
        ];
        return $this->success('Login Successfully',$token);
    }

    
}
