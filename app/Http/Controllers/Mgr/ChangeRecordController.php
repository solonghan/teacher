<?php

namespace App\Http\Controllers\Mgr;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\Committeeman;
use App\Models\Member;
use App\Models\Privilege;
use App\Models\MemberDepartment;
use App\Models\Product;
use App\Models\User;
use App\Models\ServiceUnit;
use App\Models\Specialty;
use App\Models\SpecialtyList;
use App\Models\JobTitle;
use App\Models\Source;
use App\Models\Academic;
use App\Models\ChangeRecord;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
// use Auth;

use Illuminate\Support\Facades\Input;

class ChangeRecordController extends Mgr
{

	public function __construct()
	{
		parent::__construct();
		$this->data['active'] = 'CHANGERECORD';
		$this->data['sub_active'] = 'CHANGERECORD';
		// $this->data['select']['manager'] = Member::where('id', '!=', 1)->get()->toArray();
		$this->data['select']['now_unit_id'] = ServiceUnit::where('id', '!=', 0)->get()->toArray();
		$this->data['select']['old_unit_id'] = ServiceUnit::where('id', '!=', 0)->get()->toArray();

		$this->data['select']['now_title_id'] = JobTitle::where('id', '!=', 0)->get()->toArray();
		$this->data['select']['old_title_id'] = JobTitle::where('id', '!=', 0)->get()->toArray();

		$this->data['select']['specialty_source'] = Source::where('id', '!=', 0)->get()->toArray();
		$this->data['select']['academic_source'] = Source::where('id', '!=', 0)->get()->toArray();

		$this->data['select']['specialty_id'] = SpecialtyList::where('id', '!=', 0)->get()->toArray();
		// $this->data['select']['old_unit_id'] = JobTitle::where('id', '!=', 0)->get()->toArray();
		// print_r($this->data['select']['specialty_id'] );exit;


		$this->data['select']['status'] = array(
                // array("id"=>"all", "text"=>"全部"),
            	array("id"=>"male", "text"=>"啟用"),
            	array("id"=>"female", "text"=>"關閉"),
            );
		// $this->data['select']['before'] = MemberDepartment::get()->toArray();
		// // $this->data['select']['products'] = Product::get()->toArray();
		// // $this->data['select']['users'] = User::where(['status'=>'normal'])->get()->toArray();

		
	}
	private $edit_param = [

		['姓名',		'username',     		'text',   TRUE, '', 3, 12, ''],
        ['',		    'username',     		'',   TRUE, '', 12, 12, ''],
        ['服務單位',		'now_unit_id',     		'select',   TRUE, '', 3, 12, '',['id','title']],
		// ['',		    'username',     		'',   TRUE, '', 12, 12, ''],
		['單位名稱',		'now_unit',     	'text',   TRUE, '請輸入單位名稱', 3, 12, ''],
		['職稱',		'now_title_id',     		'select',   TRUE, '', 3, 12, '',['id','title']],

		['',		    'username',     		'',   TRUE, '', 12, 12, ''],
		['曾任服務單位',		'old_unit_id',     		'select',   TRUE, '', 3, 12, '',['id','title']],
		// ['',		    'username',     		'',   TRUE, '', 12, 12, ''],
		['單位名稱',		'old_unit',     	'text',   TRUE, '請輸入單位名稱', 3, 12, ''],

		// ['',			'username',     		'',   TRUE, '', 12, 12, ''],
		['職稱',		'old_title_id',     		'select',   TRUE, '', 3, 12, '',['id','title']],
		['',			'username',     		'',   TRUE, '', 12, 12, ''],
	
		
        // ['',		'username',     		'',   TRUE, '', 12, 12, ''],
		// ['學門專長',		'specialty_id',     		'select',   TRUE, '', 3, 12, '',['id','text']],
		// ['學門專長資料來源',		'source',     		'select',   TRUE, '', 3, 12, '',['id','text']],
		// ['',		'username',     		'',   TRUE, '', 12, 12, ''],
		// ['學術專長',		'specialty',     		'text',   TRUE, '請輸入學術專長', 3, 12, ''],
		// ['學術專長資料來源',		'source',     		'select',   TRUE, '', 3, 12, '',['id','text']],
		['',		'username',     		'',   TRUE, '', 12, 12, ''],

		['',			'username',     		'',   TRUE, '', 12, 12, ''],
		['連絡電話',	'phone',     		'text',   false, '請輸入連絡電話', 3, 12, ''],
		['',			'username',     		'',   TRUE, '', 12, 12, ''],
		['電子郵件信箱',	'email',     		'text',   false, '請輸入電子郵件信箱', 3, 12, ''],
		['',			'username',     		'',   TRUE, '', 12, 12, ''],
		['相關資料網址',	'url',     		'text',   false, '請輸入相關資料網址', 3, 12, ''],
       
	];
	private $param = [
		['姓名',		'username',     		'text',   false, '', 3, 12, ''],
        ['',		    'username',     		'',   TRUE, '', 12, 12, ''],
        ['服務單位',		'unit',     		'select',   TRUE, '', 3, 12, '',['id','text']],
		['是否包含曾任單位?',		'now',     		'checkbox',   false, '', 3, 12, ''],
		['',			'username',     		'',   TRUE, '', 12, 12, ''],
		['職稱',		'now',     		'checkbox_2',   false, '', 3, 12, ''],
		['',			'username',     		'',   TRUE, '', 12, 12, ''],
		['專長',		'specialty',     		'select',   TRUE, '', 3, 12, '',['id','text']],
		['',			'username',     		'',   TRUE, '', 12, 12, ''],
		['學術專長',	'specialty',     		'text',   TRUE, '請輸入學術專長', 3, 12, ''],
		[' ',			'specialty_2',     		'text',   false, '請輸入學術專長', 3, 12, ''],
		['',			'username',     		'',   TRUE, '', 12, 12, ''],
		['最後異動時間',  'edit_date',     		'select',   TRUE, '', 3, 12, '',['id','text']],
	];
	private $department_param = [
		['姓名',		'username',     		'text',   false, '', 3, 12, ''],
        ['帳號',		    'username',     	'text',   false, '', 3, 12, ''],
        ['管理系所',		'unit',     		'text',   false, '', 3, 12, ''],
		['狀態',			'status',     		'select',   false, '', 3, 12, '',['id','text']],
		// ['',			'username',     		'',   TRUE, '', 12, 12, ''],
		// ['職稱',		'now',     		'checkbox_2',   false, '', 3, 12, ''],
		// ['',			'username',     		'',   TRUE, '', 12, 12, ''],
		// ['專長',		'specialty',     		'select',   TRUE, '', 3, 12, '',['id','text']],
		// ['',			'username',     		'',   TRUE, '', 12, 12, ''],
		// ['學術專長',	'specialty',     		'text',   TRUE, '請輸入學術專長', 3, 12, ''],
		// [' ',			'specialty_2',     		'text',   false, '請輸入學術專長', 3, 12, ''],
		// ['',			'username',     		'',   TRUE, '', 12, 12, ''],
		// ['最後異動時間',  'edit_date',     		'select',   TRUE, '', 3, 12, '',['id','text']],
	];
	private $add_param = [
		['姓名',		'username',     		'text',   TRUE, '', 3, 12, ''],
        ['',		    'username',     		'',   TRUE, '', 12, 12, ''],
        ['服務單位',		'now_unit_id',     		'select_button',   TRUE, '', 3, 12, '',['id','title']],
		// ['',		    'username',     		'',   TRUE, '', 12, 12, ''],
		['單位名稱',		'now_unit',     	'text',   TRUE, '請輸入單位名稱', 3, 12, ''],
		['職稱',		'now_title_id',     		'select',   TRUE, '', 3, 12, '',['id','title']],
		// ['問卷封面圖片',		'',        'text_button',            TRUE,   '',	3,	12,	''],
		['',		    'username',     		'',   TRUE, '', 12, 12, ''],
		['曾任服務單位',		'old_unit_id',     		'select',   TRUE, '', 3, 12, '',['id','title']],
		// ['',		    'username',     		'',   TRUE, '', 12, 12, ''],
		['單位名稱',		'old_unit',     	'text',   FALSE, '請輸入單位名稱', 3, 12, ''],
		['職稱',		'old_title_id',     		'select',   TRUE, '', 3, 12, '',['id','title']],

		// ['',			'username',     		'',   TRUE, '', 12, 12, ''],
		// ['職稱',		'now_title_id',     		'select',   TRUE, '', 3, 12, '',['id','text']],
		// ['',			'username',     		'',   TRUE, '', 12, 12, ''],
	
		['',			'username',     		'',   TRUE, '', 12, 12, ''],
		['連絡電話',	'phone',     		'text',   false, '請輸入連絡電話', 3, 12, ''],
		['',			'username',     		'',   TRUE, '', 12, 12, ''],
		['電子郵件信箱',	'email',     		'text',   false, '請輸入電子郵件信箱', 3, 12, ''],
		['',			'username',     		'',   TRUE, '', 12, 12, ''],
		['相關資料網址',	'url',     		'text',   false, '請輸入相關資料網址', 3, 12, ''],
		['',			'username',     		'',   TRUE, '', 12, 12, ''],
		
	];
	
	private $th_title = [
		['#', '', ''],
				['單位分類', '', ''],
				['單位', '', ''],
				['動作', '', '']
	];
	public function output_data(Request $request, $status = 'normal'){
		
		$this->data['controller'] = 'recommend_form';
		$this->data['title']      = "列印查詢結果頁";
		$this->data['parent']     = "";
		$this->data['parent_url'] = "";
		$this->data['th_title']   = $this->th_title_field(
			[
					['#', '', ''],
					['姓名', '', ''],
					['服務單位', '', ''],
					['單位名稱', '', ''],
					['職稱', '', ''],
					['曾任', '', ''],
					['單位名稱', '', ''],
					['專長', '', ''],
					['學術專長', '', ''],
					['最後異動時間', '', ''],
			]
		);
		
		$this->data['type']='output';
		$this->data['template_item'] = 'mgr/items/template_item';

		$data = array();
		// $data = Member::with('privilege')->with('department')->get();
		// $role = Auth::guard('mgr')->user()->role;

		$this->data['data'] = array();
		foreach ($data as $item) {
			$status = '正常';
			

			$obj = array();
			

            $priv_edit = TRUE;
			$priv_del = TRUE;
			

			$other_btns = array();
			

			$this->data['data'][] = array(
				"id"         => 1,
				"data"       => $obj,
                "priv_edit"  => $priv_edit,
                "priv_del"   => $priv_del,
				// "other_btns" => $other_btns
			);
		}
             
            $obj[] = '1';
            $obj[] = '王大文';
            $obj[] = '公立學校';
            $obj[] = '台灣大學';
            $obj[] = '教授';
			$obj[]  = '';
			$obj[]  = '';
			$obj[]  = '化學';
            $obj[] = '物理化學(XXX，2023/4/1建立)';
            $obj[]  = '2023/4/1';
           
			// $obj[]  = '';
            
            $priv_edit = false;
			$priv_del = false;

            $this->data['data'][] = array(
				"id"         => 1,
				"data"       => $obj,
                "priv_edit"  => $priv_edit,
                "priv_del"   => $priv_del,
				// "other_btns" => $other_btns
			);

        // print_r($this->data['data']);exit;
		return view('mgr/template_list', $this->data);
	}
    public function search(Request $request, $status = 'normal'){

        
        if ($request->isMethod('post')) {
            // print 123;exit;
			$formdata = $this->process_post_data($this->param, $request);

			$this->data['controller'] = 'committeeman';
            $this->data['title']      = "專家清單";
            $this->data['parent']     = "";
            $this->data['parent_url'] = "";
            $this->data['th_title']   = $this->th_title_field(
                [
					['#', '', ''],
					['姓名', '', ''],
					['服務單位', '', ''],
					['單位名稱', '', ''],
					['職稱', '', ''],
					['曾任', '', ''],
					['單位名稱', '', ''],
					['專長', '', ''],
					['學術專長', '', ''],
					['最後異動時間', '', ''],
                ]
            );
			$this->data['bar_btns'] = [
				['列印', 'window.open(\''.route('mgr.committeeman.output_data').'\');', 'primary', '1'],
				['列印+保存到伺服器', 'window.open(\''.route('mgr.committeeman.output_data').'\');', 'primary', '2']
			];
			$this->data['type']='search';
            $data = array();
            $data = array("123");
            // $data = array(array("123","123","123","123","123","123","123","123"));
			$this->data['template_item'] = 'mgr/items/template_item';
            $this->data['data'] = array();
            foreach ($data as $item) {
                $obj = array();
                $obj[] = 1;
                $obj[] = '王大文';
				$obj[] = '公立學校';
				$obj[] = '台大';
				$obj[] = '教授';
				$obj[] = '';
				$obj[] = '';
				$obj[] = '化學';
				$obj[] = '物理化學(XXX，2023/4/1建立)';
				$obj[] = '20231/2/4';
                // $obj[] = '自然科學類';

                $priv_edit = false;
                $priv_del = false;
                $priv_verified=false;
                $priv_block=false;
                $priv_reset_pwd=false;
                $priv_reset_pwd_zero=false;
                $priv_reset_pwd_ext=false;
                $this->data['data'][] = array(
                    "id"    =>  1,
                    "data"  =>   $obj,
                    "priv_edit"  => $priv_edit,
                    "priv_del"   => $priv_del,
                    "priv_verified" => $priv_verified,
                    "priv_block" => $priv_block,
                    "priv_reset_pwd" => $priv_reset_pwd,
                    "priv_reset_pwd_zero" => $priv_reset_pwd_zero,
                    "priv_reset_pwd_ext" => $priv_reset_pwd_ext,
                );
            // $this->data['btns'] = [
				
            //     ['<i class="ri-add-fill"></i>', '新增帳號', route('mgr.member.add'), 'primary'],
			// 	['新增帳號', '新增帳號', route('mgr.member.add'), 'primary','2']
            // ];
			
			
            // print_r($this->data);exit;
            }
			return view('mgr/template_list', $this->data);

		}
        
		$this->data['title'] = "查詢專家";
		$this->data['parent'] = "外審委員";
		$this->data['parent_url'] = route('mgr.committeeman');
		$this->data['action'] = route('mgr.committeeman.search');
		$this->data['submit_txt'] = '查詢';

		$this->data['params'] = $this->generate_param_to_view($this->param);
		$this->data['btns'] = [
			['<i class="ri-add-fill"></i>', '新增推薦資料', route('mgr.recommend_form.add'), 'primary']
		];
        // print_r($this->data);exit;
        // return view('mgr/template_list', $this->data);
		// return view('mgr/committeeman_lsit', $this->data);
		return view('mgr/template_form', $this->data);

    }
    public function search_old(Request $request, $status = 'normal'){

        
        if ($request->isMethod('post')) {
            
            $this->data['controller'] = 'committeeman';
            $this->data['title']      = "帳號管理";
            $this->data['parent']     = "";
            $this->data['parent_url'] = "";
            $this->data['th_title']   = $this->th_title_field(
                [
                    ['#', '', ''],
                    // ['', '', ''],
                    ['姓名', '', ''],
                    ['性別', '', ''],
                    ['服務單位', '', ''],
                    ['職稱', '', ''],
                    ['連絡電話', '', ''],
                    // ['狀態', '', ''],
                    // ['建立時間', '', ''],
                    ['學門/學類', '', ''],
                    ['學術專長(研究)', '', ''],
                    // ['審核未通過', '', ''],
                    // ['還原密碼', '', '']
                ]
            );
			$this->data['btns'] = [
				['<i class="ri-add-fill"></i>', '新增推薦資料', route('mgr.recommend_form.add'), 'primary']
			];
            $data = array();
            $data = array("123");
            // $data = array(array("123","123","123","123","123","123","123","123"));
            $this->data['data'] = array();
            foreach ($data as $item) {
                $obj = array();
                $obj[] = 1;
                $obj[] = '王大文';
                $obj[] = '男';
                $obj[] = '台灣大學';
                $obj[] = '教授';
                // $obj[] = $item->department->title;
                $obj[] = '0912345678';
                $obj[] = '生物科學類';
                $obj[] = '自然科學類';

                $priv_edit = false;
                $priv_del = false;
                $priv_verified=false;
                $priv_block=false;
                $priv_reset_pwd=false;
                $priv_reset_pwd_zero=false;
                $priv_reset_pwd_ext=false;
                $this->data['data'][] = array(
                    "id"    =>  1,
                    "data"  =>   $obj,
                    "priv_edit"  => $priv_edit,
                    "priv_del"   => $priv_del,
                    "priv_verified" => $priv_verified,
                    "priv_block" => $priv_block,
                    "priv_reset_pwd" => $priv_reset_pwd,
                    "priv_reset_pwd_zero" => $priv_reset_pwd_zero,
                    "priv_reset_pwd_ext" => $priv_reset_pwd_ext,
                );
            // $this->data['btns'] = [
            //     ['<i class="ri-add-fill"></i>', '新增帳號', route('mgr.member.add'), 'primary']
            // ];
            
            // print_r($this->data);exit;
            }
		    $this->data['template_item'] = 'mgr/items/template_item';
            // print 123;exit;
            // return view('mgr/template_list', $this->data);
            // print_r(123);exit;
			// $formdata = $this->process_post_data($this->param, $request);
			
			// if (Member::where('email', $formdata['email'])->count() > 0){
			// 	$this->js_output_and_back('Email已存在');
			// 	exit();
			// }

            
		

			// $res = Member::create($formdata);
			// if ($res) {
			// 	$this->js_output_and_redirect('新增成功', 'mgr.committeeman');
			// } else {
			// 	$this->js_output_and_back('新增發生錯誤');
			// }
			// exit();
		}
        // print 123;exit;
        
		$this->data['title'] = "查詢名單";
		$this->data['parent'] = "外審委員";
		$this->data['parent_url'] = route('mgr.committeeman');
		$this->data['action'] = route('mgr.committeeman.search');
		$this->data['submit_txt'] = '確認新增';

		$this->data['params'] = $this->generate_param_to_view($this->param);
		$this->data['btns'] = [
			['<i class="ri-add-fill"></i>', '新增推薦資料', route('mgr.recommend_form.add'), 'primary']
		];
		
        // print_r($this->data);exit;
		
        // return view('mgr/template_list', $this->data);
		return view('mgr/committeeman_lsit', $this->data);

    }
	public function index(Request $request, $status = 'normal')
	{
		$privilege_id=Auth::guard('mgr')->user()->privilege_id;
		if($privilege_id!=1) echo '<script> history.back(); </script>';

		// print 1231;exit; Committeeman::with('specialty')->with('service_units')->get()
		// $data=Committeeman::with('specialty')->with('service_units')->get();

		// foreach($data as $d){
		// 		print_r($d->specialty->title);exit;
		// 	}

		// print_r($data);exit;


		$this->data['controller'] = 'change_record';
		$this->data['title']      = "異動紀錄";
		$this->data['parent']     = "";
		$this->data['parent_url'] = "";
		$this->data['th_title']   = $this->th_title_field(
			[
				['#', '', ''],
				['專家名稱', '', ''],
				['異動資料', '', ''],
				['異動人', '', ''],
				['異動時間', '', ''],
				// ['異動時間', '', ''],
				
			]
		);
		// $this->data['btns'] = [
		// 	['<i class="ri-add-fill"></i>', '新增推薦資料', route('mgr.recommend_form.add'), 'primary']
		// ];
		$this->data['type']='';
		$this->data['template_item'] = 'mgr/items/change_record_item';

		// $data = array();
		// $data = ChangeRecord::get();
		// // $role = Auth::guard('mgr')->user()->role;

		// // print_r($data);exit;
		// $this->data['data'] = array();
		// $i=1;
		// foreach ($data as $item) {
		// 	$member = Member::find($item->user_id);
		// 	$committeeman = Committeeman::find($item->committeeman_id);
		// 	// print_r($member);exit;
		// 	$changer=$member->username;
		// 	$obj = array();
           
        //     $obj[] = $i;
        //     $obj[] = (isset($committeeman['username']))?$committeeman['username']:'';
        //     $obj[] = $item->action;
        //     $obj[] = $changer;
        //     $obj[] = $item->updated_at;
			
		// 	// $obj[]  = '';
            
        //     $priv_edit = false;
		// 	$priv_del = false;

        //     $this->data['data'][] = array(
		// 		"id"         => 1,
		// 		"data"       => $obj,
        //         "priv_edit"  => $priv_edit,
        //         "priv_del"   => $priv_del,
		// 		// "other_btns" => $other_btns
		// 	);
		// 	$i++;
		// }
        // print_r($this->data['data']);exit;
		// return view('mgr/template_list', $this->data);
		$this->data['is_search'] = true;
		return view('mgr/template_list_ajax', $this->data);
	}
	public function data(Request $request){
		// $search='21';
		$page   = $request->post('page')??'';
		$search = $request->post('search')??'';
		$action = $request->post('action')??'normal';
		$point_enabled = $request->post('point_enabled')??'';
		$page_count = $request->post('page_count')??$this->page_count;
		// print_r($search);exit;
        // print 1231;exit;
		$html='';
		$data = array();
		// $data= ChangeRecord::get();
		$data = ChangeRecord::where(function($query) use ($search) {
			if ($search != ''){
				$query->orWhere('username', 'like', '%'.$search.'%');
				$query->orWhere('action', 'like', '%'.$search.'%');
				$query->orWhere('committeeman', 'like', '%'.$search.'%');
				$query->orWhere('updated_at', 'like', '%'.$search.'%');
			}
		});
		// $role = Auth::guard('mgr')->user()->role;

		// print_r($data->count());exit;
		$total = $data->count();
        $total_page = ($page_count!='all')?(($total % $page_count == 0) ? floor(($total)/$page_count) : floor(($total)/$page_count) + 1):'1';
		
		$data = $data->take($page_count)->skip( ($page - 1) * $page_count )->orderBy('id','desc')->get();
		// print_r($data);exit;
		$this->data['data'] = array();
		$i=1;
		foreach ($data as $item) {
			$member = Member::find($item->user_id);
			$committeeman = Committeeman::find($item->committeeman_id);
			// print_r($member);exit;
			$changer=$member->username;
			$obj = array();
           
            $obj[] = $i;
            $obj[] = (isset($committeeman['username']))?$committeeman['username']:'';
            $obj[] = $item->action;
            $obj[] = $changer;
            $obj[] = $item->updated_at;
			// $obj[] = $item->updated_at;
			
			// $obj[]  = '';
            
            $priv_edit = false;
			$priv_del = false;
			$other_btns = array();

            $html .= view('mgr/items/change_record_item', [
				'item'      => array(
					"id"         => $item['id'],
					"data"       => $obj,
					"other_btns" => $other_btns,
					"priv_edit"  => $priv_edit,
					"priv_del"   => $priv_del
				),
				'th_title'  => $this->th_title_field($this->th_title)
			])->render();
			$i++;
		}
		$this->output(TRUE, 'success', array(
			'html'	=>	$html,
			'page'       => $page,
			'total_page' => $total_page,
			// 'total'      => $total,
			// 'page_count' => $page_count
		));
		
	}
	public function data_have(Request $request){
		// print 123;exit;
		
		$html='';
		
        // $data = DB::table('units')->get();
		$data[] = array('id'=>1,'unit_classify'=>'123','unit_name'=>'test');
		$this->data['data'] = array();
		foreach ($data as $item) {
			$obj = array();
            $obj[] = $item['id'];
			// $obj[] = $item['unit_classify'];
			// $obj[] = $item['unit_name'];
			$obj[] = '王大同';
			$obj[] = '公立學校';
			$obj[] = '台大';
			$obj[] = '教授';
			$obj[] = '';
			$obj[] = '';
			$obj[] = '化學';
			$obj[] = '分析化學....';
			// $obj[] = '20231/2/4';

			$priv_edit = false;
			$priv_del = false;
			$other_btns = array();
			
			
			$html .= view('mgr/items/template_item', [
				'item'      => array(
					"id"         => $item['id'],
					"data"       => $obj,
					"other_btns" => $other_btns,
					"priv_edit"  => $priv_edit,
					"priv_del"   => $priv_del
				),
				'th_title'  => $this->th_title_field($this->th_title)
			])->render();
		}

		$this->output(TRUE, 'success', array(
			'html'	=>	$html
		));

	}
	public function data_no(Request $request){
		// print 123;exit;
		
		$html='';
		
        // $data = DB::table('units')->get();
		$data[] = array('id'=>1,'unit_classify'=>'123','unit_name'=>'test');
		$this->data['data'] = array();
		foreach ($data as $item) {
			$obj = array();
            $obj[] = $item['id'];
			// $obj[] = $item['unit_classify'];
			// $obj[] = $item['unit_name'];
			$obj[] = '王大同';
			$obj[] = '公立學校';
			$obj[] = '台大';
			$obj[] = '教授';
			$obj[] = '';
			$obj[] = '';
			$obj[] = '化學';
			$obj[] = '分析化學....';
			$obj[] = '20231/2/4';

			$priv_edit = false;
			$priv_del = false;
			$other_btns = array();
			
			
			$html .= view('mgr/items/template_item', [
				'item'      => array(
					"id"         => $item['id'],
					"data"       => $obj,
					"other_btns" => $other_btns,
					"priv_edit"  => $priv_edit,
					"priv_del"   => $priv_del
				),
				'th_title'  => $this->th_title_field($this->th_title)
			])->render();
		}

		$this->output(TRUE, 'success', array(
			'html'	=>	$html
		));

	}
	public function unlink_line(Request $request, $id){
		if (Member::where('id', $id)->update(['line_id'=>''])) {
			$this->js_output_and_redirect("已解除綁定", 'mgr.member');
		}else{
			$this->js_output_and_back("解除發生錯誤");
		}
	}
	public function add_specialty(Request $request){
		// print_r($_COOKIE['formdata']);
		
		$session_data = $request->session()->all();
		// print_r($session_data['now_unit']);exit;
		
		$add_specialty_parm = [
			// ['姓名 : XXXzzz',		'username',     		'title',   TRUE, '', 3, 12, ''],
			// ['姓名',		'username',     	'text_disabled',   TRUE, '', 3, 12, ''],
			// ['單位名稱',	'now_unit',     	'text_disabled',   TRUE, '', 3, 12, ''],
			// ['職稱',		'now_title_id',     'text_disabled',   TRUE, '', 3, 12, ''],

			['姓名',		'username',     	'text',   TRUE, '', 3, 12, ''],
			['單位名稱',	'now_unit',     	'text',   TRUE, '', 3, 12, ''],
			['職稱',		'now_title_id',     'text',   TRUE, '', 3, 12, ''],
			
			// ['單位名稱 : XXX',		'now_unit',     	'title',   TRUE, '', 3, 12, ''],
			
			// ['職稱 : XXX',		'now_title',     		'title',   TRUE, '', 3, 12, ''],
		
			['',			'username',     		'',   TRUE, '', 12, 12, ''],
			['學門專長',	'specialty_id',     		'select',   TRUE, '', 3, 12, '',['id','title']],
			// ['',			'username',     		'',   TRUE, '', 12, 12, ''],
			['學門專長資料來源',	'specialty_source',     		'select',   TRUE, '', 3, 12, '',['id','title']],
			['',			'username',     		'',   TRUE, '', 12, 12, ''],
			['學術專長',	'academic_id',     		'text',   TRUE, '', 3, 12, ''],
			// ['',			'username',     		'',   TRUE, '', 12, 12, ''],
			['學術專長資料來源',	'academic_source',     		'select',   TRUE, '', 3, 12, '',['id','title']],
			
		];
		


		// print_r($session_data);exit;
		if ($request->isMethod('post')) {
			$formdata = $this->process_post_data($add_specialty_parm, $request);
			print_r($formdata);exit;
			$this->js_output_and_redirect('儲存成功', 'mgr.committeeman');
			print 123;exit;
		}
		$this->data['title'] = "新增外審專家";
		$this->data['parent'] = "標籤";
		$this->data['parent_url'] = route('mgr.committeeman');
		$this->data['action'] = route('mgr.committeeman.add_specialty');
		$this->data['submit_txt'] = '儲存';
		// $this->data['add_txt'] = '查詢';

		// $session_data = json_decode($session_data,true);
		if(empty($session_data['username'])) $session_data['username']='';
		if(empty($session_data['now_unit'])) $session_data['now_unit']='';
		if(empty($session_data['now_title_id'])) $session_data['now_title_id']='';
		
		$session_data['specialty_id']='';
		$session_data['specialty_source']='';
		$session_data['academic_id']='';
		$session_data['academic_source']='';

		$this->data['params'] = $this->generate_param_to_view($add_specialty_parm,$session_data);

		

		return view('mgr/template_form', $this->data);
	}
	public function add(Request $request){
		

		if ($request->isMethod('post')) {
			$formdata = $this->process_post_data($this->add_param, $request);
			$have_data=Committeeman::with('now_title')
									->with('academic')
									->where('username',$formdata['username'])
									->where('now_unit',$formdata['now_unit'])
									->first();

			// print_r($have_data);exit;
			if( !empty($have_data)){
				// $request->session()->forget('username');
				$request->session()->forget('username');
				$request->session()->forget('now_unit');
				$request->session()->forget('now_title_id');
				$i=0;
				foreach($have_data->academic as $a_title){
					$academic_list[$i]=$a_title->title;
					$i++;
				}
			// 	$academic_data=Academic::where("committeeman_id",$have_data['id'])->get();
			// 	// print_r($academic_data);exit;
			// foreach($academic_data as $a_data){
			// 	$academic_list[$i]=$a_data->title;
			// 	$i++;
			// }
		
			$academic_data=implode("，",$academic_list);
			
			// print_r($academic_data);exit;
			// $academic_data=implode("、",$academic_list);
				// print 'have';exit;
				$this->data['controller'] = 'committeeman';
				$this->data['title'] = "專家清單";
				$this->data['parent'] = "";
				$this->data['parent_url'] = "";
				$this->data['th_title'] = $this->th_title_field(
				
					[
						['#', '', ''],
						['姓名', '', ''],
						// ['服務單位', '', ''],
						['單位名稱', '', ''],
						// ['曾任職單位', '', ''],
						// ['單位名稱', '', ''],
						['職稱', '', ''],
						// ['曾任', '', ''],
						// ['單位名稱', '', ''],
						['專長', '', ''],
						['連絡電話', '', ''],
						['電子郵件信箱', '', ''],
						['相關資料網址', '', ''],

					]
				);
				$this->data['bar_btns'] = [
					['新增專長', 'window.open(\''.route('mgr.committeeman.add_specialty').'\');', 'primary', '2'],
					// ['列印+保存到伺服器', 'window.open(\''.route('mgr.committeeman.search').'\');', 'primary', '2']
				];
				// $this->data['btns'] = [
				// 	['新增專長', '新增推薦資料', route('mgr.recommend_form.add'), 'primary','2']
				// ];
				$this->data['type']='search_have';
				$this->data['template_item'] = 'mgr/items/search_have_item';
				$data = array();
				$data = $have_data;
				// print_r($data->username);exit;
				$this->data['data'] = array();
				$i=1;
				// foreach ($data as $item) {
					$obj = array();
					$obj[] = $i;
					$obj[] = $data->username;
					$obj[] = $data->now_unit;
					$obj[] = $have_data->now_title->title;
					// $obj[] = '政大';
					// $obj[] = '教授';
					$obj[] = $academic_data;
					$obj[] = $data->phone;
					$obj[] = $data->email;
					$obj[] = $data->url;
					// $obj[] = '分析化學....';
					// $obj[] = '自然科學類';

					$priv_edit = false;
					$priv_del = false;
					$priv_verified=false;
					$priv_block=false;
					$priv_reset_pwd=false;
					$priv_reset_pwd_zero=false;
					$priv_reset_pwd_ext=false;
					$this->data['data'][] = array(
						"id"    =>  1,
						"data"  =>   $obj,
						"priv_edit"  => $priv_edit,
						"priv_del"   => $priv_del,
						"priv_verified" => $priv_verified,
						"priv_block" => $priv_block,
						"priv_reset_pwd" => $priv_reset_pwd,
						"priv_reset_pwd_zero" => $priv_reset_pwd_zero,
						"priv_reset_pwd_ext" => $priv_reset_pwd_ext,
					);
					// return view('mgr/template_list', $this->data);
					// $i++;
				// }
				return view('mgr/template_list', $this->data);
			}else{
				// print 'no_have';
				
				$request->session()->put('username', $formdata['username']);
				$request->session()->put('now_unit', $formdata['now_unit']);
				$request->session()->put('now_title_id', 1);
				// session(['username' => $formdata['username']]);
				// session(['now_unit' => $formdata['now_unit']]);
				// session(['not_title' => '教授']);
				$request->session()->save();
				// $data = $request->session()->all();
				// $request->session()->flush('not_unit');
				// print_r($data);
				// exit;
				$this->js_output_and_next('查無資料', 'mgr.committeeman.add_specialty');
			}
			exit;
			print_r($formdata);exit;

			// if($formdata['username']=='王大文'){
				
			
		
		}

		$this->data['title'] = "新增外審專家";
		$this->data['parent'] = "標籤";
		$this->data['parent_url'] = route('mgr.committeeman');
		$this->data['action'] = route('mgr.committeeman.add');
		$this->data['submit_txt'] = '新增';
		$this->data['submit_search_txt'] = '查詢';

		$this->data['params'] = $this->generate_param_to_view($this->add_param);
		
		return view('mgr/template_form', $this->data);
	}

	public function switch_toggle(Request $request){
		if ($request->isMethod('post')) {
			$id     = $request->post('id');
			$status = $request->post('status');

			if (Member::where(['id'=>$id])->update(['status'=>$status])) {
				$this->output(TRUE, "success");
			}else{
				$this->output(FALSE, "fail");
			}
		}
	}
	public function department_add(request $request){
		// print 123;
		if ($request->isMethod('post')) {

			$this->js_output_and_redirect('新增成功', 'mgr.committeeman.department_manage');
			// print 123;
			// $formdata = $this->process_post_data($this->department_param, $request);
			$this->data['title'] = "新增管理系所";
			$this->data['parent'] = "系所管理";
			$this->data['parent_url'] = route('mgr.committeeman');
			$this->data['action'] = route('mgr.committeeman');
			$this->data['submit_txt'] = '新增';

		$this->data['params'] = $this->generate_param_to_view($this->department_param);
		

		return view('mgr/template_form', $this->data);
			// $this->department_manage($request);
			// exit;
			// print_r($formdata);exit;

			// if($formdata['username']=='王大文'){
				$this->data['controller'] = 'committeeman';
				$this->data['title'] = "專家清單~";
				$this->data['parent'] = "";
				$this->data['parent_url'] = "";
				$this->data['th_title'] = $this->th_title_field(
				
					[
						['#', '', ''],
						['姓名', '', ''],
						// ['服務單位', '', ''],
						['單位名稱', '', ''],
						['曾任職單位', '', ''],
						['單位名稱', '', ''],
						['職稱', '', ''],
						// ['曾任', '', ''],
						// ['單位名稱', '', ''],
						['專長', '', ''],
						['連絡電話', '', ''],
						['電子郵件信箱', '', ''],
						['相關資料網址', '', ''],

					]
				);
				$this->data['bar_btns'] = [
					['新增專長', 'window.open(\''.route('mgr.committeeman.add_specialty').'\');', 'primary', '2'],
					// ['列印+保存到伺服器', 'window.open(\''.route('mgr.committeeman.search').'\');', 'primary', '2']
				];
				// $this->data['btns'] = [
				// 	['新增專長', '新增推薦資料', route('mgr.recommend_form.add'), 'primary','2']
				// ];
				$this->data['type']='search_have';
				$this->data['template_item'] = 'mgr/items/template_item';
				$data = array();
				$data = array("123");
				$this->data['data'] = array();
				foreach ($data as $item) {
					$obj = array();
					$obj[] = 1;
					$obj[] = '王大文';
					$obj[] = '台大';
					$obj[] = '公立學校';
					$obj[] = '政大';
					$obj[] = '教授';
					$obj[] = '化學';
					$obj[] = '0912345678';
					$obj[] = 'XXX@gmail.com';
					$obj[] = 'www.xxx.com';
					// $obj[] = '分析化學....';
					// $obj[] = '自然科學類';

					$priv_edit = false;
					$priv_del = false;
					$priv_verified=false;
					$priv_block=false;
					$priv_reset_pwd=false;
					$priv_reset_pwd_zero=false;
					$priv_reset_pwd_ext=false;
					$this->data['data'][] = array(
						"id"    =>  1,
						"data"  =>   $obj,
						"priv_edit"  => $priv_edit,
						"priv_del"   => $priv_del,
						"priv_verified" => $priv_verified,
						"priv_block" => $priv_block,
						"priv_reset_pwd" => $priv_reset_pwd,
						"priv_reset_pwd_zero" => $priv_reset_pwd_zero,
						"priv_reset_pwd_ext" => $priv_reset_pwd_ext,
					);
					// return view('mgr/template_list', $this->data);
				}
				return view('mgr/template_list', $this->data);
			
				
		
			

			
		}

		$this->data['title'] = "新增管理系所";
		$this->data['parent'] = "系所管理";
		$this->data['parent_url'] = route('mgr.committeeman');
		$this->data['action'] = route('mgr.committeeman.department_add');
		$this->data['submit_txt'] = '新增';

		$this->data['params'] = $this->generate_param_to_view($this->department_param);
		

		return view('mgr/template_form', $this->data);
	}
	public function department_manage(request $request){
		$this->data['controller'] = 'committeeman';
		$this->data['title']      = "選擇管理科系";
		$this->data['parent']     = "";
		$this->data['parent_url'] = "";
		$this->data['th_title']   = $this->th_title_field(
			[
				['#', '', ''],
				['姓名', '', ''],
				['帳號', '', ''],
				['管理系所', '', ''],
				['狀態', '', ''],
				['動作', '', ''],
				
			]
		);
		$this->data['btns'] = [
			['<i class="ri-add-fill"></i>', '新增管理科系', route('mgr.committeeman.department_add'), 'primary']
		];
		$this->data['type']='';
		$this->data['template_item'] = 'mgr/items/template_item';

		$data = array();
		// $data = Member::with('privilege')->with('department')->get();
		// $role = Auth::guard('mgr')->user()->role;

		$this->data['data'] = array();
		foreach ($data as $item) {
		
			$obj = array();
	
            $priv_edit = TRUE;
			$priv_del = TRUE;
			$other_btns = array();
			
			$this->data['data'][] = array(
				"id"         => 1,
				"data"       => $obj,
                "priv_edit"  => $priv_edit,
                "priv_del"   => $priv_del,
				// "other_btns" => $other_btns
			);
		}
             
            $obj[] = '1';
            $obj[] = 'admin';
            $obj[] = 'admin';
            $obj[] = '教育系、經濟系';
            $obj[] = '啟用';
			
			// $obj[]  = '';
            
            $priv_edit = true;
			$priv_del = false;

            $this->data['data'][] = array(
				"id"         => 1,
				"data"       => $obj,
                "priv_edit"  => $priv_edit,
                "priv_del"   => $priv_del,
				// "other_btns" => $other_btns
			);

        // print_r($this->data['data']);exit;
		return view('mgr/template_list', $this->data);
	}
	public function modification_record(request $request){
		$this->data['controller'] = 'recommend_form';
		$this->data['title']      = "異動紀錄";
		$this->data['parent']     = "";
		$this->data['parent_url'] = "";
		$this->data['th_title']   = $this->th_title_field(
			[
				['#', '', ''],
				['專家名稱', '', ''],
				['異動資料', '', ''],
				['異動人', '', ''],
				['異動時間', '', ''],
				
			]
		);
		// $this->data['btns'] = [
		// 	['<i class="ri-add-fill"></i>', '新增推薦資料', route('mgr.recommend_form.add'), 'primary']
		// ];
		$this->data['type']='';
		$this->data['template_item'] = 'mgr/items/template_item';

		$data = array();
		// $data = Member::with('privilege')->with('department')->get();
		// $role = Auth::guard('mgr')->user()->role;

		$this->data['data'] = array();
		foreach ($data as $item) {
		
			$obj = array();
	
            $priv_edit = TRUE;
			$priv_del = TRUE;
			$other_btns = array();
			
			$this->data['data'][] = array(
				"id"         => 1,
				"data"       => $obj,
                "priv_edit"  => $priv_edit,
                "priv_del"   => $priv_del,
				// "other_btns" => $other_btns
			);
		}
             
            $obj[] = '1';
            $obj[] = 'XXX';
            $obj[] = '新增專家';
            $obj[] = 'XXX';
            $obj[] = '2023/4/1 11:00';
			
			// $obj[]  = '';
            
            $priv_edit = false;
			$priv_del = false;

            $this->data['data'][] = array(
				"id"         => 1,
				"data"       => $obj,
                "priv_edit"  => $priv_edit,
                "priv_del"   => $priv_del,
				// "other_btns" => $other_btns
			);

        // print_r($this->data['data']);exit;
		return view('mgr/template_list', $this->data);
	}
	public function edit(Request $request, $id){

		
		$data=Committeeman::find($id);
		if ($request->isMethod('post')) {
			$formdata = $this->process_post_data($this->edit_param, $request);
			// print_r($formdata);exit;

			
			$res = Committeeman::updateOrCreate(['id'=>$id], $formdata);
			if ($res) {
				$this->js_output_and_redirect('編輯成功', 'mgr.committeeman');
			} else {
				$this->js_output_and_back('編輯發生錯誤');
			}
			exit();
		}
		// $2y$10$4366i0NSO7jboKNbaVLkFubgYg5qnovDQWFks4uPKW0zZ7nIpfDue
		$this->data['title'] = "編輯推薦資料 ";
		$this->data['parent'] = "推薦資料列表";
		$this->data['parent_url'] = route('mgr.committeeman');
		$this->data['action'] = route('mgr.committeeman.edit', ['id'=>$id]);
		$this->data['submit_txt'] = '確認編輯';

		
		// print_r($data->username);exit;
		
		
		
		$this->data['params'] = $this->generate_param_to_view($this->edit_param, $data);
		// $this->data['params'] = $this->generate_param_to_view($this->param, $data);

		// print_r($this->data);exit;
		
		//使用者僅要撈出自己管的＆尚未指派
		$this->data['select']['users'] = array();
		

		
		

		return view('mgr/template_form', $this->data);
	}

	public function del(Request $request){
		$id = $request->post('id');

		$obj = Member::find($id);
		if ($obj->delete()) {
			$this->output(TRUE, "Delete success");
		}else{
			$this->output(FALSE, "Delete fail");
		}
	}

	public function edit_department(Request $request, $id){


		print_r(123);exit;
		$data = Member::find($id);
		if ($request->isMethod('post')) {
			$formdata = $this->process_post_data($this->param, $request);

			if (Member::where('email', $formdata['email'])->where('id','!=',$id)->count() > 0){
				$this->js_output_and_back('Email已存在');
				exit();
			}
			if ($formdata['password'] == ''){
				unset($formdata['password']);
			}else{
				if ($formdata['password'] != $formdata['password_confirm']) $this->js_output_and_back('兩次密碼輸入不相同');
				$formdata['password'] = Hash::make($formdata['password']);
			}
			unset($formdata['password_confirm']);

			$formdata['update_by'] = Auth::guard('mgr')->user()->id;
			$res = Member::updateOrCreate(['id'=>$id], $formdata);
			if ($res) {
				$res->subordinate_refresh($request->post('subordinate'));
				$res->manage_product_refresh($request->post('products'));
				$res->manage_user_refresh($request->post('users'));

				Product::where(['assistant'=>$id])->update(['assistant'=>0]);
				if ($request->post('products_assistant')){
					foreach ($request->post('products_assistant') as $product_id) {
						Product::where('id', $product_id)->update(['assistant'=>$id]);
					}
				}

				$this->js_output_and_redirect('編輯成功', 'mgr.member');
			} else {
				$this->js_output_and_back('編輯發生錯誤');
			}
			exit();
		}
		// $2y$10$4366i0NSO7jboKNbaVLkFubgYg5qnovDQWFks4uPKW0zZ7nIpfDue
		$this->data['title'] = "編輯 ".$data->username;
		$this->data['parent'] = "帳號管理";
		$this->data['parent_url'] = route('mgr.member');
		$this->data['action'] = route('mgr.member', ['id'=>$id]);
		$this->data['submit_txt'] = '確認編輯';

		$subordinate = $data->subordinate_array();
		$users = $data->manage_user_array();
		$products = $data->manage_product_array();
		$data = $data->toArray();

		$data['subordinate'] = $subordinate;
		$data['users'] = $users;
		$data['products'] = $products;
		
		$data['products_assistant'] = array();
		foreach (Product::where(['assistant'=>$id])->get() as $p) {
			$data['products_assistant'][] = $p->id;
		}
		
		$this->data['params'] = $this->generate_param_to_view($this->param, $data);
		
		$this->data['select']['subordinate'] = Member::where('id', '!=', Auth::guard('mgr')->user()->id)->get()->toArray();

		//使用者僅要撈出自己管的＆尚未指派
		$this->data['select']['users'] = array();
		foreach (User::where(['status'=>'normal'])->whereNull('deleted_at')->with('manage_user')->get() as $user) {
			if (is_null($user->manage_user) || count($user->manage_user) <= 0){
				$this->data['select']['users'][] = $user->toArray();
				continue;
			}
			if (count($user->manage_user) > 0) {
				$mine = false;
				foreach ($user->manage_user as $m) {
					if ($m['id'] == $id) $mine = true;
				}
				if ($mine) $this->data['select']['users'][] = $user->toArray();
			}			
		}

		//僅要撈出自己管的＆尚未指派的商品
		$this->data['select']['products'] = array();//Product::get()->toArray();
		foreach (Product::where(['status'=>'on', 'lang'=>'tw'])->whereNull('deleted_at')->with('manager')->get() as $product) {
			if (is_null($product->manager) || count($product->manager) <= 0){
				$this->data['select']['products'][] = $product->toArray();
				continue;
			}
			if (count($product->manager) > 0) {
				$mine = false;
				foreach ($product->manager as $m) {
					if ($m['id'] == $id) $mine = true;
				}
				if ($mine) $this->data['select']['products'][] = $product->toArray();
			}	
		}

		$this->data['select']['products_assistant'] = Product::where(['lang'=>'tw', 'status'=>'on'])->where(function($q) use ($id) {
			$q->where('assistant', 0)
			  ->orWhere('assistant', $id);
		})->get()->toArray();

		return view('mgr/template_form', $this->data);

	}
}