<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public $token = true;

    public function register(Request $request)
    {

         $request->validate(
                      [
                      'name' => 'required',
                      'email' => 'required|email',
                      'password' => 'required',
                      'role' => 'required|string',

                     ]);


            $roleName = $request->role ? $request->role : 'user';
            $role = Role::where('role_name', $roleName)->first();
            
                // return ['role'=>$role,"role name "=>$request->role];
                //         if (!$role) {
                //             $role = Role::create([
                //                 'role_name' => $roleName,
                //             ]);

    if (!$role) {
        return response()->json(['error' => 'Rôle non valide'], 400);
    }


        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        // $user->role_id = $role->id;
        $user->role()->associate($role);
        $user->save();

        $user->load('role');
        // return ['user'=>$user];

        // if ($this->token) {
        //     return $this->login($request);
        // }
          $token=Auth::login($user);
        return response()->json([
            'success' => true,
            'token' => $token,
            'data' => $user,
        ], Response::HTTP_OK);
    }

    public function login(Request $request)
    {
        $input = $request->only('email', 'password');
        $jwt_token = null;

        if (!$jwt_token = JWTAuth::attempt($input)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid Email or Password',
            ], Response::HTTP_UNAUTHORIZED);
        }

        return response()->json([
            'success' => true,
            'token' => $jwt_token,
            'user'=> Auth::user(),
            ]);
    }

    public function logout(Request $request)
    {

        try {
            JWTAuth::invalidate(JWTAuth::parseToken($request->token));

            return response()->json([
                'success' => true,
                'message' => 'User logged out successfully'
            ]);
        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, the user cannot be logged out'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }

    public function getUser(Request $request)
    {
        try{
            $user = JWTAuth::authenticate($request->token);
            return response()->json(['user' => $user]);

        }catch(Exception $e){
            return response()->json(['success'=>false,'message'=>'something went wrong']);
        }
    }
}





