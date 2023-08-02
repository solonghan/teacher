<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Privilege;

use Illuminate\Database\Eloquent\SoftDeletes;
class Member extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'role',
        'username',
        'ID_number',
        'line_id',
        'email',
        'mobile',
        'tel',
        'ext',
        'fax',
        'password',
        'avatar',
        'privilege_id',
        'department_id',
        'my_department_id'
    ];

    // public function department(){
        // print 12312;exit;
        // belongsToMany(目標表單名稱，中介表單名稱，中介表單上參照自己的外鍵，中介表單上參照目標的外鍵，自己的關聯鍵，目標的關聯鍵)
        // return $this->belongsToMany(MemberDepartment::class, 'member_id', 'id');
        // return $this->hasMany(Department::class,'id');
    // }

    //後台使用 使用with()先撈出值的情況下
    public function department_array(){
        $data = array();
        // print_r($this->get_member_department);exit;
        // print 123231312313;
        // exit;
        foreach ($this->get_member_department as $c) {
            $data[] = $c->title_id;
        }
        // print_r($data);exit;
        return $data;
    }

    public function get_member_department(){
        // return $this->belongsTo(Specialty::class, 'specialty_id', 'id');
        return $this->hasMany(MemberDepartment::class,'member_id','id');
    }
    public static function get_department($title_id){
        $department_member_id=MemberDepartment::where('title_id',$title_id)->get();
        // print_r($department_committeeman_id);exit;
        $k=0;
        $department_data=array();
        foreach($department_member_id as $d_member_id){
            $department_data_all=MemberDepartment::where('member_id',$d_member_id->member_id)->get();
            // print_r($specialty_data_all);
            
            $j=0;
            foreach($department_data_all as $d_title){
                // print_r($s_title);exit;
                $writer_data=Member::find($d_title->writer_id);
                $department_list_data=Department::find($d_title->title_id);

                $department_list[$k][$j]=$department_list_data->title;
                $j++;
            }
            // $department_data=implode("、",$department_list);
            foreach($department_list as $s_list){
                $department_data[$k]=implode("、",$s_list);
            }
            $k++;
            // $specialty_data=$specialty_list;
        }
print_r($department_data);exit;
        return $department_data;
        // return $this->belongsTo(Specialty::class, 'specialty_id', 'id');
        // return $this->hasMany(Department::class,'member_id','id');
    }

    public function writer(){
        return $this->hasMany(Academic::class,'writer_id','id');
    }

    public function privilege(){
        return $this->belongsTo(Privilege::class, 'privilege_id', 'id');
    }

    public function department(){
        return $this->belongsTo(MemberDepartment::class);
    }
    public function my_department(){
        return $this->belongsTo(Department::class);
    }

    public static function role_str($role){
        switch($role){
            case 'saler': return '業務';
            case 'mgr': return '主管';
            case 'super': return '最高權限';
            case 'accounting': return '會計';
            case 'assistant': return '業助';
            case 'depot': return '倉儲';
        }
    }

    /*
        會員
    */
    public function manage_user(){
        return $this->belongsToMany(User::class, 'user_managers', 'member_id', 'user_id', 'id', 'id');
    }

    //後台使用 使用with()先撈出值的情況下
    public function manage_user_array(){
        $data = array();
        foreach ($this->manage_user as $t) {
            $data[] = $t->id;
        }
        return $data;
    }

    //Update
    public function manage_user_refresh($new_arr){
        $this->manage_user()->detach($this->manage_user_array());
        if ($new_arr == null || $new_arr == '') return;
        foreach ($new_arr as $item) {
            $this->manage_user()->attach($item);
        }
    }

    //Insert
    public function manage_user_add($new_arr){
        if ($new_arr == null || $new_arr == '') return;
        foreach ($new_arr as $item) {
            $this->manage_user()->attach($item);
        }
    }

    /*
        產品
    */
    public function manage_product(){
        return $this->belongsToMany(Product::class, 'product_managers', 'member_id', 'product_id', 'id', 'id');
    }

    //後台使用 使用with()先撈出值的情況下
    public function manage_product_array(){
        $data = array();
        foreach ($this->manage_product as $t) {
            $data[] = $t->id;
        }
        return $data;
    }

    //Update
    public function manage_product_refresh($new_arr){
        $this->manage_product()->detach($this->manage_product_array());
        if ($new_arr == null || $new_arr == '') return;
        foreach ($new_arr as $item) {
            $this->manage_product()->attach($item);
        }
    }


    /*
        下屬
    */
    public function subordinate(){
        return $this->belongsToMany(Member::class, 'member_managers', 'member_id', 'subordinate', 'id', 'id');
    }

    //後台使用 使用with()先撈出值的情況下
    public function subordinate_array(){
        $data = array();
        foreach ($this->subordinate as $t) {
            $data[] = $t->id;
        }
        return $data;
    }

    //Update
    public function subordinate_refresh($new_arr){
        $this->subordinate()->detach($this->subordinate_array());
        if ($new_arr == null || $new_arr == '') return;
        foreach ($new_arr as $item) {
            $this->subordinate()->attach($item);
        }
    }

/*
    上司是誰
*/
    public function supervisor(){
        return $this->belongsToMany(Member::class, 'member_managers', 'subordinate', 'member_id', 'id', 'id');
    }
}
