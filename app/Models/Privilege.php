<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Member;
class Privilege extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'title',
        'op_user',
        'op_product'
    ];

    public function member(){
        return $this->hasMany(Member::class);
    }
}
