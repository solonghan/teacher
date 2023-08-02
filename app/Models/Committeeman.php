<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

use Illuminate\Database\Eloquent\SoftDeletes;

use DateTimeInterface;

class Committeeman extends Model
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'username', 
        'member_id',    
        'email',           
        'phone' ,         
        'url' ,                 
        'now_unit_id',       
        'now_unit',        
        'now_title_id' ,      
        'old_unit_id' ,     
        'old_unit' ,        
        'old_title_id' ,       
        'specialty_id'  ,      
        'specialty_source'  ,
        'academic_id'  ,       
        'academic_source'  ,
        'status',
        'updated_at',
        // 'member_id',

        
    ];
    // public function specialty_list(){
    //     // return $this->belongsTo(Specialty::class, 'specialty_id', 'id');
    //     return $this->hasOne(SpecialtyList::class,'id','specialty_id');
    // }

    // public function specialty(){
    //     // return $this->belongsTo(Specialty::class, 'specialty_id', 'id');
    //     return $this->hasOne(Specialty::class,'id','specialty_id');
    // }
    // public function get_member_name(){
    //     // return $this->belongsTo(ServiceUnit::class,'now_unit_id','id');
    //     return $this->hasOne(Member::class,'id','member_id');
    // }

    public function now_service_unit(){
        // return $this->belongsTo(ServiceUnit::class,'now_unit_id','id');
        return $this->hasOne(ServiceUnit::class,'id','now_unit_id');
    }

    public function old_service_unit(){
        // return $this->belongsTo(ServiceUnit::class,'old_unit_id','id');
        return $this->hasOne(ServiceUnit::class,'id','old_unit_id');
    }

    public function now_title(){
        // return $this->belongsTo(JobTitle::class,'now_title_id','id');
        return $this->hasOne(JobTitle::class,'id','now_title_id');
    }

    public function now_other_title(){
        // return $this->belongsTo(JobTitle::class,'now_title_id','id');
        return $this->hasOne(OtherTitle::class,'committeeman_id','id');
    }

    public function old_title(){
        // return $this->belongsTo(JobTitle::class,'old_title_id','id');
        return $this->hasOne(JobTitle::class,'id','old_title_id');
    }

    public function academic(){
        return $this->hasMany(Academic::class,'committeeman_id','id');
    }

    public function specialty(){
        // return $this->belongsTo(Specialty::class, 'specialty_id', 'id');
        return $this->hasMany(Specialty::class,'committeeman_id','id');
    }
    public function get_member(){
        // return $this->belongsTo(Specialty::class, 'specialty_id', 'id');
        return $this->hasOne(Member::class,'id','member_id');
    }

    public static function get_member_name($member_id){
        // return $this->belongsTo(ServiceUnit::class,'now_unit_id','id');
        $data=Member::find($member_id);
        // print_r($data->username);exit;
        return $data->username;
    }

    public static function get_specialty($title_id,$id){
        
        $specialty_committeeman_id=Specialty::where('title_id',$title_id)->where('committeeman_id',$id)->get();
        // print_r($specialty_committeeman_id);exit;
        $k=0;
        $specialty_data=array();
        foreach($specialty_committeeman_id as $s_committeeman_id){
            $specialty_data_all=Specialty::where('committeeman_id',$s_committeeman_id->committeeman_id)->get();
            // print_r($specialty_data_all);
            
            $j=0;
            foreach($specialty_data_all as $s_title){
                // print_r($s_title);exit;
                $writer_data=Member::find($s_title->writer_id);
                $specialty_list_data=SpecialtyList::find($s_title->title_id);

                $specialty_list[$k][$j]=$specialty_list_data->title;
                $j++;
            }
           
            
            foreach($specialty_list as $s_list){
                $specialty_data[$k]=implode("、",$s_list);
            }
            $k++;
            // $specialty_data=$specialty_list;
        }
        // print_r($specialty_data);exit;
        return $specialty_data;
       
    }

    public static function get_academic($title,$id){
        
        $academic_committeeman_id=Academic::where('title','like', '%'. $title.'%')->where('committeeman_id',$id)->groupBy('committeeman_id')->get();
        // print_r($academic_committeeman_id);exit;
        $k=0;
        $academic_data=array();
        foreach($academic_committeeman_id as $s_committeeman_id){
            // print_r($s_committeeman_id->committeeman_id);
            // exit;
            $academic_data_all=Academic::where('committeeman_id',$s_committeeman_id->committeeman_id)->get();
            // print_r(count($academic_data_all));exit;
            // print_r($academic_data_all);exit;
            
            $j=0;
            foreach($academic_data_all as $a_title){
                // print_r($s_title['title']);
                $writer_data=Member::find($a_title->writer_id);
                $academic_list_data=Academic::where('title','like', '%'. $a_title['title'].'%')->first();
                // find($s_title->title_id);
                
                $academic_list[$k][$j]=$academic_list_data->title.'('.$writer_data->username.'，'.$a_title->create_date.'建立)';
                $j++;
            }
            // print_r($academic_list);
            // exit;
            foreach($academic_list as $a_list){
                $academic_data[$k]=implode("、",$a_list);
            }
            $k++;
            // $specialty_data=$specialty_list;
        }
        // exit;
        // print_r($academic_data);exit;
        return $academic_data;
       
    }

    public static function get_job_title($now_title){
        $data_title=array();
        // print_r($now_title);exit;
        foreach($now_title as $n_title){
            
            $data=JobTitle::find($n_title[1]);
            $data_title[]=$data->title;
            
        }
        $search_title=implode("、",$data_title);

        return $search_title;
    }

    public static function get_unit_title($now_unit){
        $data_title=array();
        // print_r($now_unit);exit;
        foreach($now_unit as $n_unit){
            if($n_unit=='have'){
                // print 21321;exit;
                unset($now_unit['have']);
                continue;
            }
            $data=ServiceUnit::find($n_unit[1]);
            $data_title[]=$data->title;
            
        }

        // print_r($data_title);exit;
        $search_title=implode("、",$data_title);

        return $search_title;
    }

    // public static function save_search($data){
    //     print_r(13);
    //     print_r($data);
    //     exit;
    //     // $data_title=array();
    //     // foreach($now_title as $n_title){
            
    //     //     $data=JobTitle::find($n_title[1]);
    //     //     $data_title[]=$data->title;
            
    //     // }
    //     // $search_title=implode("、",$data_title);

    //     // return $search_title;
    // }
}
