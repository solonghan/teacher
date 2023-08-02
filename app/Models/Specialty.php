<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Specialty extends Model
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'title_id',
        'committeeman_id',
        'writer_id',
        'create_date',
        'specialty_sources_id',
        
    ];
    public function committeeman(){
         return $this->belongsTo(Committeeman::class);
        // return $this->hasMany(Committeeman::class);
    }

    // public function specialty_title(){
    //     return $this->hasMany(SpecialtyList::class);
    // }
}
