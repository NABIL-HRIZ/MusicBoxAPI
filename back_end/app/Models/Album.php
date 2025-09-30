<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Artist;
use App\Models\Chanson;

class Album extends Model
{
    use HasFactory;

     protected $fillable = [
        'titre',
        'annee',
        'artist_id',
    ];


    public function artist()
    {
        return $this->belongsTo(Artist::class);
    }

    public function chansons(){
        return $this->hasMany(Chanson::class);
    }
}
