<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\PrivilegeMenuRelated;
class PrivilegeMenu extends Model
{
    use HasFactory;
    protected $fillable = [
        'sort',
        'parent_id',
        'name',
        'icon',
        'url',
        'function',
        'action',
        'badge',
        'status'
    ];

    public static function allMenu($privilege_id = false, $action_enabled = false){
        $related = array();
        if ($privilege_id !== false) {
            $related = PrivilegeMenuRelated::fetchRelated($privilege_id);
        }
        $data = array();
        // $test=PrivilegeMenu::orderBy('parent_id','ASC')->orderBy('sort','ASC')->get();
        // print_r($test);exit;
        foreach (PrivilegeMenu::orderBy('parent_id','ASC')->orderBy('sort','ASC')->get() as $menu) {
            // print_r($menu);exit;
            if ($menu->parent_id == 0) {
                $m = $menu->toArray();
                $m['sub'] = array();
                $m['action'] = explode(",", $m['action']);
                $m['action_enabled'] = array();
                if ($privilege_id !== false && array_key_exists($m['id'], $related)) {
                    $m['action_enabled'] = $related[$m['id']];
                }
                
                if ($action_enabled && !in_array(1, $m['action_enabled'])) continue;
                $data[$menu->id] = $m;
                // print_r($m);exit;
                
            }else{
                $sub_menu = $menu->toArray();
                $sub_menu['action'] = explode(",", $sub_menu['action']);
                $sub_menu['action_enabled'] = array();
                if ($privilege_id !== false && array_key_exists($sub_menu['id'], $related)) {
                    $sub_menu['action_enabled'] = $related[$sub_menu['id']];
                }
                if ($action_enabled && !in_array(1, $sub_menu['action_enabled'])) continue;
                if (!array_key_exists($menu->parent_id, $data)) continue;
                $data[$menu->parent_id]['sub'][] = $sub_menu;
            }
        }
        // print_r($data);exit;
        return $data;
    }

    public static function menuAction(){
        return DB::table("privileges_action")->get();
    }
}
