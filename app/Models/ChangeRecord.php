<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChangeRecord extends Model
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'username',
        'user_id',
        'action',
        'committeeman_id',
        'committeeman'
    ];

    public static function add_change_record($change_data){
        // print_r($change_data);exit;
        return ChangeRecord::updateOrCreate($change_data);

       
        // print_r($change_data);

    }
}
