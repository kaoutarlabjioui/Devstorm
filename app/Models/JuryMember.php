<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JuryMember extends Model
{
    use HasFactory;

    protected $fillable =['user_name','pin'];


    public function note(){
        return $this->belongsTo(Note::class);
    }

    public function jury(){
        return $this->belongsTo(Jury::class);
    }
}
