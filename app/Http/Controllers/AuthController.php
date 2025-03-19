<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Validator;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator as FacadesValidator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public $token = true;

    public function register(Request $request)
    {

         $validator = FacadesValidator::make($request->all(),
                      [
                      'name' => 'required',
                      'email' => 'required|email',
                      'password' => 'required',
                      'role' => 'nullable|exists:roles,name',

                     ]);

         if ($validator->fails()) {

               return response()->json(['error'=>$validator->errors()], 401);

            }

            $roleName = $request->role ? $request->role : 'user';
            $role = Role::where('role_name', $roleName)->first();

            if (!$role) {
                $role = Role::create([
                    'role_name' => $roleName,
                ]);

    if (!$role) {
        return response()->json(['error' => 'Rôle non valide'], 400);
    }


        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->role_id = $role->id;
        $user->save();





        if ($this->token) {
            return $this->login($request);
        }

        return response()->json([
            'success' => true,
            'data' => $user
        ], Response::HTTP_OK);
    }
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





