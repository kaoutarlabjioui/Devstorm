<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Tymon\JWTAuth\Contracts\JWTSubject;
class JuryMember extends Model implements JWTSubject
{
    use HasFactory;

    protected $fillable =['username','pin'];


    public function note(){
        return $this->belongsTo(Note::class);
    }

    public function jury(){
        return $this->belongsTo(Jury::class);
    }

    public function getJWTIdentifier()
{
    return $this->getKey();
}

public function getJWTCustomClaims()
{
    return [];
}

}
