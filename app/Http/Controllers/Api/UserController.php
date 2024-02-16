<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register(Request $request){

        $rules = [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required'
        ];
        $validate = \Validator::make($request->all(), $rules);
        
        if($validate->fails()) {
            return response()->json([
                'status' => false,
                'msg' => 'Error de validaciones',
                'data' => $validate->errors()
            ], 403);
        }

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json([
            "status" => true,
            "msg" => "Usuario creado correctamente",
            "user" => $user,

        ]);
    }

    public function login(Request $request){
        $request->validate([
            "email" => "required|email",
            "password" => "required"
        ]);

        $user = User::where("email", "=", $request->email)->first();
        
        if(isset($user->id)){
            if(Hash::check($request->password, $user->password)){
                $token = $user->createToken("auth_token")->plainTextToken;
                
                return response()->json([
                    "status" => true,
                    "msg" => "Usuario logueado correctamente",
                    "access_token" => $token
                ], 200);

            }else{
                return response()->json([
                    "status" => false,
                    "msg" => "credenciales incorrectas"
                ], 401);
            }

        }else{
            return response()->json([
                "status" => false,
                "msg" => "Usuario no encontrado",
            ],404);
        }

        
    }

    public function logout(){
        auth()->user()->tokens()->delete();

        return response()->json([
            'status' => true,
            'msg' => 'Sesión cerrada correctamente'
        ]);

    }




}
