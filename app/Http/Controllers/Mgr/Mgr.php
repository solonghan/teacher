<?php

namespace App\Http\Controllers\Mgr;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Http\Controllers\BaseController;
use App\Models\User;
use App\Models\Order;
use App\Models\Contact;
use App\Models\Notification;
use App\Models\PrivilegeMenu;
use Auth;
// use Illuminate\Support\Facades\Auth;

class Mgr extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    protected $data = array("active"=>"dashboard", "sub_active"=>"", "nav"=>array());

    public function __construct()
    {
        // $this->middleware('auth');
        
        $this->middleware('mgr');
        
        $this->data['nav'] = array();
        
        $this->middleware(function ($request, $next) {

            
            $nav_to_db = false;
            // if (PrivilegeMenu::count() > 0) $nav_to_db = false;
            $privilege_id=Auth::guard('mgr')->user()->privilege_id;
           
            // $this->data['notification'] = 
            // Notification::where('member_id', Auth::guard('mgr')->user()->id)
            //             ->where('is_notification', 1)
            //             ->orderBy('id','desc')
            //             ->get();
            
            // $this->data['unread'] = 
            // Notification::where('member_id', Auth::guard('mgr')->user()->id)
            //             ->where('is_notification', 1)
            //             ->where('is_read', 0)
            //             ->count();
            
            // $this->data['unread_msg'] = Contact::where('status', 'pending')->whereNull('deleted_at')->count();

            // $role = Auth::guard('mgr')->user()->role;
            // $manage_user = Auth::guard('mgr')->user()->manage_user_array();
            // $manage_product = Auth::guard('mgr')->user()->manage_product_array();
            // $department_id = Auth::guard('mgr')->user()->department_id;

            // $orders = Order::list('all', $role, $manage_user, $manage_product, $department_id);
            // $order_cnt = 0;
            // foreach ($orders as $o) {
            //     if ($o->status != 'complete' && $o->status != 'cancel') $order_cnt++;
            // }


            // $new_users = User::where(['status'=>'inreview'])->count();
            $nav = [
              
                [['老師功能', '', 'COMMITTEEMAN', '', 'ri-rocket-line'], [
                    ['新增外審專家', 'mgr.committeeman.add', 'COMMITTEEMAN', ''],
                    ['名單管理', 'mgr.committeeman', 'COMMITTEEMAN', ''],
                  
                ]],
                [['系、院功能', '', 'COMMITTEEMAN', '', 'ri-sim-card-2-line'], [
                    // ['名單管理', 'mgr.committeeman', 'COMMITTEEMAN', ''],
                    ['查詢專家', 'mgr.committeeman.search', 'SETTING_SHOPPING_FLOW', ''],
                    ['系所管理', 'mgr.member.department_view', 'MEMBER_DEPARTMENT_VIEW', ''],
                   
                ]],
                [['最高管理', '', 'COMMITTEEMAN', '', 'ri-mail-open-line'], [
                    // ['系所管理', 'mgr.member.department_manage', 'MEMBER_DEPARTMENT_MANAGE', ''],
                    ['異動記錄', 'mgr.change_record', 'CHANGE_RECORD', ''],
                    ['帳號管理', 'mgr.member', 'MEMBER', ''],
                ]],
                
            ];
            if($privilege_id==2){
                unset($nav[2]);
            }
            if($privilege_id==3){
                unset($nav[1]);
                unset($nav[2]);
            }
            // print_r($nav);exit;
            // $this->data['nav'] = PrivilegeMenu::allMenu(Auth::guard('mgr')->user()->privilege_id, true);
            // print_r($this->data);exit;
            // //聯絡我們
            // if (array_key_exists(27, $this->data['nav'])) {
            //     $this->data['nav'][27]['badge'] = $this->data['unread_msg'];
            // }
            // //會員管理
            // if (array_key_exists(29, $this->data['nav'])) {
            //     $this->data['nav'][29]['badge'] = $new_users;
            //     $this->data['nav'][29]['sub'][0]['badge'] = $new_users;
            // }
            // //訂單管理
            // if (array_key_exists(32, $this->data['nav'])) {
            //     $this->data['nav'][32]['badge'] = $order_cnt;
            // }
            // dd($this->data['nav']);
            $sort = 1;
            foreach ($nav as $n) {
                // print_r($n[1]);
                // exit;
                $sub = array();
                // if ($nav_to_db){
                //     print_r($n);exit;
                //     $menu = PrivilegeMenu::create([
                //         "name"     => $n[0][0],
                //         "url"      => array_key_exists(1, $n[0])?$n[0][1]:"",
                //         "function" => ((isset($n[0][2]))?$n[0][2]:''),
                //         "icon"     => ((isset($n[0][4]))?$n[0][4]:''),
                //         "badge"    => '',                                       //((isset($n[0][3]))?$n[0][3]:''),
                //         "action"   => "1",
                //         "sort"     => $sort++
                //     ]);
                // }
                foreach ($n[1] as $s) {
                    // if ($nav_to_db){
                    //     PrivilegeMenu::create([
                    //         "parent_id" => $menu->id,
                    //         "name"      => $s[0],
                    //         "url"       => array_key_exists(1, $s)?$s[1]:"",
                    //         "function"  => (isset($s[2])?$s[2]:""),
                    //         "badge"     => '',//((isset($s[3]))?$s[3]:''),
                    //         "action"    => "1,2,3,4"
                    //     ]);
                    // }
                        
                    $sub[] = array(
                        "id"       => uniqid(),
                        "name"     => $s[0],
                        "function" => (isset($s[2])?$s[2]:""),
                        "url"      => array_key_exists(1, $s)?$s[1]:"",
                        "badge"    => ((isset($s[3]))?$s[3]:''),
                    );
                }
                $this->data['nav'][] = array(
                    "id"       => uniqid(),
                    "name"     => $n[0][0],
                    "url"      => array_key_exists(1, $n[0])?$n[0][1]:"",
                    "function" => ((isset($n[0][2]))?$n[0][2]:''),
                    "badge"    => ((isset($n[0][3]))?$n[0][3]:''),
                    "sub"      => $sub,
                    "icon"     => ((isset($n[0][4]))?$n[0][4]:'ri-menu-fill'),
                );
            }
            // exit;
			return $next($request);
		});
    }

    public function th_title_field($th_arr){
        $data = array();
        foreach ($th_arr as $th) {
            $data[] = array(
                "title" =>  $th[0],
                "width" =>  $th[1],
                "field" =>  $th[2]
            );
        }
        return $data;
    }
}
