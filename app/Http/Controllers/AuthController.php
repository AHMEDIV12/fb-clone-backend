<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Arr;
use App\User;


class AuthController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }
    
    public function register(Request $request){

        try {
        $data = $request->all();
        $data['password'] = bcrypt($request->password);
        $data['followers'] = [];
        $data['following'] = [];
        $new_user = User::create($data);

        $credentials = ['email' => $new_user->email, 'password' => $request->password];
        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized', 'success' => false], 201);
        }

        return response()->json($new_user);
            } catch (\Exception $e) {

                return $e->getMessage();
            }
    }

    public function login(){
        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $this->respondWithToken($token);
        // return response()->json([ $this->respondWithToken($token),'user' => $credentials ]) ;
    }

    public function updateUser (Request $request , $id){
        try {

            $data = $request->all();
            $user = User::findOrFail($id);
            $user->update($data);
            return response()->json($user); 

        }catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function me()
    {
        return response()->json(auth()->user());
    }

    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user'       => auth()->user()
        ]);
    }

    public function follow(Request $request, $id){
        try{
            $data = $request->all();
            if($id == $data['id']){
                return response()->json(['messege' => 'you cannot follow your self']);
            }else{
                // find current user's following array who wanna follow another user 
                $loggedinuser = User::findOrFail($data['id']) ;
                
                // find current user's followers array who is followed by another user
                $clickedonuser = User::findOrFail($id);

                if(!in_array($id,$loggedinuser['following'])|| !in_array($data['id'],$clickedonuser['followers']))
                {
                    $loggedinuser['following'] = Arr::prepend($loggedinuser['following'], $id);
                    $clickedonuser['followers'] = Arr::prepend($clickedonuser['followers'], $data['id']);
                    $loggedinuser->save();
                    $clickedonuser->save();
                    return response()->json(['followed' => true]);

                }else{
                    return response()->json(['messege' => 'you already follow that person']);
                }

                return response()->json([$loggedinuser]);

            }
        }catch(\Exception $e){
            
            dd($e);
        }
    }    

    public function unfollow(Request $request, $id){
        try{
            // url is the clicked on user $id
            // request is the logged in user $data['id]
            $data = $request->all();
            if($id == $data['id']){
                return response()->json(['messege' => 'you cannot follow your self']);
            }else{
                $loggedinuser = User::findOrFail($data['id']) ;

                $clickedonuser = User::findOrFail($id);

                if(in_array($id,$loggedinuser['following'])|| in_array($data['id'],$clickedonuser['followers']))
                {
                    $dataid = $data['id'] ;
                    $loggedinuser['following'] = array_diff($loggedinuser['following'], array($id));
                    $clickedonuser['followers'] = array_diff($clickedonuser['followers'], array($dataid));
                    $loggedinuser->save();
                    $clickedonuser->save();
                    return response()->json(['messege' => 'unfollow done', 'followed' => false]);
                }else{
                    return response()->json(['messege' => 'you you already follow that person',
                    'logedinuser' => $loggedinuser,'clickedonuser'=> $clickedonuser]);
                }

                return response()->json([$loggedinuser]);

            }
        }catch(\Exception $e){
            
            dd($e);
        }
    }
}