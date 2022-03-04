<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use Auth; 

class AuthController extends Controller
{

    public function login(Request $request)
    {
        $validation=Validator::make($request->all(),[
            'username'=>'required|string|max:255',
            'password'=>'required|max:255'
        ]);
        if($validation->fails())
        {
            return response()->json($validation->errors(),422);
        }
        else
        {
            $token=auth('api')->attempt($validation->valid());
            if(!$token)
            {
                return response()->json(['message'=>'User is not authorized'],401);
            }
            else
            {
                return response()->json([
                    'token'=>$token
                ]);
            }
        }
    }
}
