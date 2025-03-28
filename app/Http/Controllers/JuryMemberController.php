<?php

namespace App\Http\Controllers;

use App\Models\Jury;
use App\Models\JuryMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
class JuryMemberController extends Controller
{

public function store(Request $request)
{

    if (!Gate::allows('isAdmin')) {
        return response()->json(['error' => 'You do not have permission to register a JuryMember.'], 403);
    }

    try{

    // $user = JWTAuth::parseToken()->authenticate();
    // return ['user'=>$user];

    // if (!$user) {
    //     return response()->json(['error' => 'Unauthorized'], 401);
    // }


    $request->validate([
        'jury_name' => 'nullable|string'
    ]);


    $jury = Jury::where('name',$request->input('jury_name'))->first();




    do {
        $userName = 'jury_' . Str::random(6);
    } while (JuryMember::where('username', $userName)->exists());
// return ["zer"=>$userName];
    $randomPin = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

    $randomHashPin = Hash::make($randomPin);
        $juryMember = new JuryMember();
        $juryMember->username = $userName;
        $juryMember->pin = $randomHashPin;
        $juryMember->jury()->associate($jury);
        $juryMember->save();
    return response()->json([
        'message' => 'Compte JuryMember créé avec succès !',
        'jury_member_name' => $userName,
        'pin'=>$randomPin
    ], 201);

    }catch(JWTException $e) {
        return response()->json(['error' => 'Invalid token'], 400);
    }
}

public function login(Request $request)
{
    $request->validate([
        'username' => 'required|string',
        'pin' => 'required|string',
    ]);

    $juryMember = JuryMember::where('username', $request->username)->first();

    if (!$juryMember) {
        return response()->json(['error' => 'Jury member not found.'], 404);
    }

    if (!Hash::check($request->pin, $juryMember->pin)) {
        return response()->json(['error' => 'Invalid pin.'], 401);
    }

    try {
        $token = JWTAuth::fromUser($juryMember);

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'token' => $token
        ]);
    } catch (JWTException $e) {
        return response()->json(['error' => 'Could not create token'], 500);
    }
}







}
