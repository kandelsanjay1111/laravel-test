<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\TicketResource;
use App\Http\Resources\UserResource;
use App\Models\Source;
use App\Models\User;
use Validator;
use Http;
use Cookie;

class TicketController extends Controller
{

    public function index()
    {
        return TicketResource::collection(Source::all());
    }

    public function store(Request $request)
    {
        $validation=Validator::make($request->all(),[
            'hostname'=>'required|url|max:255|unique:sources',
            'username'=>'required|string|max:255',
            'password'=>'required|string|max:255',
            'user_id'=>'required|numeric'
        ]);
        if($validation->fails())
        {
            return response()->json(['errors'=>$validation->errors()],422);
        }
        else
        {
            $valid_data=$validation->valid();

            $source=new Source;
            $source->hostname=$valid_data['hostname'];
            $source->username=$valid_data['username'];
            $source->password=$valid_data['password'];
            $source->user_id=$valid_data['user_id'];
            $source->save();

            return response()->json(['success'=>'Source is created successfully'],200);
        }
    }

    public function show($id)
    {
        $url=Source::find($id);
        if($url)
        {
            return new TicketResource($url);
        }
        else
        {
            return response()->json([
                'errors'=>'Ticket is not found'
                
            ]);
        }
    }

    public function update(Request $request,$id)
    {
        $validation=Validator::make($request->all(),[
            'hostname'=>'required|url|max:255|unique:sources,hostname,'.$id,
            'username'=>'required|string|max:255',
            'password'=>'required|string|max:255',
            'user_id'=>'required|numeric'
        ]);
        if($validation->fails())
        {
            return response()->json(['errors'=>$validation->errors()],422);
        }

        $source=Source::find($id);
        if($source)
        {
            $data=$validation->valid();
            $source->hostname= $data['hostname'];
            $source->username=$data['username'];
            $source->password=$data['password'];
            $source->user_id=$data['user_id'];
            $source->save();
            return response()->json([
                'message'=>'the user is successfully updated',

            ]);
        }
        else
        {
            return $this->invalid_data();
        }
    }

    public function destroy($id)
    {
        // return $id;
         $source=Source::find($id);
         if($source)
         {
            $source->delete();
            return response()->json(['success'=>'data is deleted successfully']);
         }
         else{
            return $this->invalid_data();
         }
    }

   

    public function invalid_data()
    {
        return response()->json(['message'=>'the requested data is not found'],404);
    }

    public function getData($route,$token)
    {
        $response=Http::withToken($token)->get($route);
        return $response;
    }

    public function auth($credentials,$route)
    {
        $response=Http::post($route,$credentials);
        // return $response->json()['token'];
        
       return $response;
    }

    public function getTicket($id)
    {
        $user=User::with('sources')->find($id);
        if($user)
        {
            $data=array();

            foreach($user->sources as $key=>$source)
            {
                $hostname=$source->hostname;
                $token=isset($_COOKIE[$source->id.'login_token'])?$_COOKIE[$source->id.'login_token']:'';
                if(empty($token))
                {
                    //proceed to login 
                    $auth_response=$this->auth(['username'=>$source->username,'password'=>$source->password],$source->hostname.'/auth/login');
                    if(isset($auth_response['token']))
                    {
                        $token=$auth_response->json()['token'];
                    }
                    // else
                    // {
                    //     if($auth_response->status()==='401')
                    //     {
                    //         return $auth_response;
                    //     }
                    //     else
                    //     {
                    //         return response()->json(['message'=>'error in getting data'],$auth_response->status());
                    //     }
                    //     break;
                    // }
                    setcookie($source->id.'login_token',$token,time()+86400);
                    $ticket=$this->getData($source->hostname.'/mms/ticket',$token)->json();
                    $action=$this->getData($source->hostname.'/mms/ticket/action',$token)->json();
                    $reason=$this->getData($source->hostname.'/mms/ticket/reason',$token)->json();
                    $status=$this->getData($source->hostname.'/mms/ticket/status',$token)->json();
                    foreach($ticket as $key=>$value)
                    {
                        foreach($action as $act)
                        {
                            if($value['action_id']===$act['id'])
                            {
                                 $ticket[$key]['action_id']=$act['caption'];
                            }

                        }
                        foreach($reason as $reas)
                        {
                            if($value['reason_id']===$reas['id'])
                            {
                            $ticket[$key]['reason_id']=$reas['caption'];
                            }
                        }
                        foreach($status as $sts)
                        {
                            if($value['status_id']==$sts['id'])
                            {
                                $ticket[$key]['status_id']=$sts['caption'];
                            }
                        }
                    }
                    // return $ticket;
                }
                 else
                {
                    // return $token;
                    $ticket=$this->getData($source->hostname.'/mms/ticket',$token)->json();
                    // $ticket_array=json_decode($ticket);
                    $action=$this->getData($source->hostname.'/mms/ticket/action',$token)->json();
                    $reason=$this->getData($source->hostname.'/mms/ticket/reason',$token)->json();
                    $status=$this->getData($source->hostname.'/mms/ticket/status',$token)->json();
                    foreach($ticket as $key=>$value)
                    {
                        foreach($action as $act)
                        {
                            if($value['action_id']===$act['id'])
                            {
                                 $ticket[$key]['action_id']=$act['caption'];
                            }

                        }
                        foreach($reason as $reas)
                        {
                            if($value['reason_id']===$reas['id'])
                            {
                            $ticket[$key]['reason_id']=$reas['caption'];
                            }
                        }
                        foreach($status as $sts)
                        {
                            if($value['status_id']==$sts['id'])
                            {
                                $ticket[$key]['status_id']=$sts['caption'];
                            }
                        }
                    }
                }
                
                $data[$source->id]=$ticket;
            }
            // return 'hello';
            $data['token']=$_COOKIE;
            return response()->json(['data'=>$data]);
        }   

        else
        {
            return response()->json(['message'=>'User is not found'],404);
        }        
    }


    // public function getUserId()
    // {
    //     $user=User::all();
    //     return $user->pluck('id');
    // }

    // public function getUser()
    // {
    //     return UserResource::collection(User::all());
    // }

}
