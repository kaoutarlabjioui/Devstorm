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

    $randomPin = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

    $randomHashPin = Hash::make($randomPin);
        $juryMember = new JuryMember();
        $juryMember->username = $userName;
        $juryMember->pin = $randomHashPin;
        $juryMember->jury()->associate($jury);
        $juryMember->save();
    return response()->json([
        'message' => 'Compte JuryMember créé avec succès !',
        'jury_member_name' => $juryMember->userName,
        'pin'=>$randomPin
    ], 201);

}catch(JWTException $e) {
    return response()->json(['error' => 'Invalid token'], 400);
}
}
}
