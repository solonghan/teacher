<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrivilegeMenuRelated extends Model
{
    use HasFactory;

    protected $fillable = [
        'privilege_id',
        'menu_id',
        'action_id',
        'enabled'
    ];

    public static function updateRelated($privilege_id, $data){
        PrivilegeMenuRelated::where(['privilege_id'=>$privilege_id])->delete();

        foreach ($data as $key => $item) {
            if (substr($key, 0 ,2) !== 'p_') continue;
            $p = explode("_", $key);
            PrivilegeMenuRelated::create(
                [
                    "privilege_id" => $privilege_id,
                    "menu_id"      => $p[1],
                    "action_id"    => $p[2]
                ]
            );
        }
    }

    public static function fetchRelated($privilege_id){
        $data = array();
        foreach (PrivilegeMenuRelated::where(['privilege_id'=>$privilege_id])->get() as $item) {
            if (!array_key_exists($item['menu_id'], $data)) $data[$item['menu_id']] = array();
            $data[$item['menu_id']][] = $item['action_id'];
        }
        return $data;
    }
}
