<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Models\User;
use Validator;

class UserController extends Controller
{
    public function index()
    {
        $user=User::all();
        return UserResource::collection($user);
    }

    public function create(Request $request)
    {
        // return $request->all();
        $validation=Validator::make($request->all(),[
            'username'=>'required|string|max:255|unique:user',
            'password'=>'required|string|max:255',
        ]);
        if($validation->fails())
        {
            return $this->sendError($validation->errors());
        }
        else
        {
            $data=$validation->valid();
            $user=new User;
            $user->username=$data['username'];
            $user->password=bcrypt($data['password']);
            $user->save();
            return response()->json(['message'=>'User is created successfully']);
        }
    }

    public function show($id)
    {
        $user=User::find($id);
        if($user)
        {
            return new UserResource($user);
        }
        else
        {
            return $this->response404();
        }
    }

    public function update(Request $request,$id)
    {
        $validation=Validator::make($request->all(),[
            'username'=>'required|string|max:255|unique:user,username,'.$id,
            'password'=>'sometimes|string|max:255',
        ]);
        if($validation->fails())
        {
            return $this->sendError($validation->errors(),422);
        }

        $user=User::find($id);
        if($user)
        {
            $valid_data=$validation->valid();

            $user->username=$valid_data['username'];
            if(isset($valid_data['password']))
            {
            $user->password=bcrypt($valid_data['password']);
            }
            $user->save();
            
            return response()->json(['message'=>'user is updated successfully']);
        }
        // else
        // {
        //     return response()->json(['message'=>'the resource was not found'],404);
        // }
    }

    public function destroy($id)
    {
        $user=User::find($id);
        if($user)
        {
            $user->delete();
            return response()->json(['message'=>'the user is deleted successfully']);
        }
        else
        {
            return response()->json(['message'=>'the resource was not found'],404);
        }
    }

    public function sendError($errors)
    {
        return response()->json(['errors'=>$errors],422);
    }

    public function response404()
    {
        return response()->json(['message'=>'the requested resource was not found'],404);
    }
}
