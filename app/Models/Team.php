<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'github_link','status'];

    public function hackathon()
    {
        return $this->belongsTo(Hackathon::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
    public function theme()
    {
        return $this->belongsTo(Theme::class);
    }
}
