<?php

namespace App\Http\Controllers\Mgr;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\Privilege;
use App\Models\MemberDepartment;
use App\Models\Department;
use App\Models\SpecialtyList;
use App\Models\Committeeman;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
// use Auth;

class MemberController extends Mgr
{

	public function __construct()
	{
		parent::__construct();
		$this->data['active'] = 'MEMBER';
		$this->data['sub_active'] = 'MEMBER';

		$this->data['select']['privilege_id'] = Privilege::get()->toArray();
		$this->data['select']['department_id'] = Department::get()->toArray();
		$this->data['select']['my_department_id'] = Department::get()->toArray();
		$this->data['select']['username'] = Member::where('id', '!=', 0)->get()->toArray();
		$this->data['select']['email'] = Member::where('id', '!=', 0)->get()->toArray();
		// print_r($this->data['select']['department_id']);exit;
		// $this->data['select']['products'] = Product::get()->toArray();
		// $this->data['select']['users'] = User::where(['status'=>'normal'])->get()->toArray();

		$this->data['select']['role'] = array(
			array("id"=>"super", "text"=>"最高權限"),
			array("id"=>"college", "text"=>"學院"),
			array("id"=>"assistant", "text"=>"系助教"),
			array("id"=>"professor", "text"=>"教授"),
		
		);
		$this->data['select']['status'] = array(
			// array("id"=>"all", "text"=>"全部"),
			array("id"=>"male", "text"=>"啟用"),
			array("id"=>"female", "text"=>"關閉"),
		);
	}
	private $add_param = [
		['帳號資料',	'',		'header',   TRUE, '', 12, 12, ''],
		['姓名',		'username',     		'text',   TRUE, '', 3, 12, ''],
		['帳號(身分證字號)',	'ID_number',        		'text',   TRUE, '', 3, 12, ''],
		// ['身分別',		'role', 			'select',   TRUE, '', 2, 12, '', ['id','text']],
		['權限',	'privilege_id', 	'select',   TRUE, '', 2, 12, '', ['id','title']],
		['所屬科系',	'my_department_id', 	'select',   TRUE, '', 2, 12, '', ['id','title']],

		['變更密碼',	'',		'header',   TRUE, '', 12, 12, ''],
		// ['(如不修改密碼，留空白即可)',	'',		'content',   FALSE, '', 12, 12, ''],
		['輸入密碼',	'password',      	'password',   FALSE, '', 3, 12, ''],
		['再次輸入密碼',	'password_confirm',      'password',   FALSE, '', 3, 12, ''],
		['',			'username',     		'',   TRUE, '', 12, 12, ''],

	];

	private $param = [
		['帳號資料',	'',		'header',   TRUE, '', 12, 12, ''],
		['姓名',		'username',     		'text',   TRUE, '', 2, 12, ''],
		['帳號(身分證字號)',	'ID_number',        		'text',   TRUE, '', 2, 12, ''],
		// ['身分別',		'role', 			'select',   TRUE, '', 2, 12, '', ['id','text']],
		['權限',	'privilege_id', 	'select',   TRUE, '', 2, 12, '', ['id','title']],
		['所屬科系',	'my_department_id', 	'select',   TRUE, '', 2, 12, '', ['id','title']],
		['管理系所',		'department_id',     		'select_multi',   false, '', 3, 12, '', ['id','title']],

		['變更密碼',	'',		'header',   TRUE, '', 12, 12, ''],
		['(如不修改密碼，留空白即可)',	'',		'content',   FALSE, '', 12, 12, ''],
		['輸入密碼',	'password',      	'password',   FALSE, '', 3, 12, ''],
		['再次輸入密碼',	'password_confirm',      'password',   FALSE, '', 3, 12, ''],
		['',			'username',     		'',   TRUE, '', 12, 12, ''],

	];
	private $department_param = [
		// ['姓名',		'username',     		'select',   false, '', 3, 12, '',['id','username']],
        ['帳號',		    'email',     	'select',   false, '', 3, 12, '',['id','email']],
        ['管理系所',		'department_id',     		'select_multi',   false, '', 3, 12, '',['id','title']],
		// ['狀態',			'status',     		'select',   false, '', 3, 12, '',['id','text']],
		['',			'username',     		'',   TRUE, '', 12, 12, ''],
		// ['職稱',		'now',     		'checkbox_2',   false, '', 3, 12, ''],
		// ['',			'username',     		'',   TRUE, '', 12, 12, ''],
		// ['專長',		'specialty',     		'select',   TRUE, '', 3, 12, '',['id','text']],
		// ['',			'username',     		'',   TRUE, '', 12, 12, ''],
		// ['學術專長',	'specialty',     		'text',   TRUE, '請輸入學術專長', 3, 12, ''],
		// [' ',			'specialty_2',     		'text',   false, '請輸入學術專長', 3, 12, ''],
		// ['',			'username',     		'',   TRUE, '', 12, 12, ''],
		// ['最後異動時間',  'edit_date',     		'select',   TRUE, '', 3, 12, '',['id','text']],
	];
	private $add_department_param = [
		// ['姓名',		'username',     		'select',   false, '', 3, 12, '',['id','username']],
        ['帳號',		    'email',     	'select',   false, '', 3, 12, '',['id','email']],
        ['管理系所',		'department_id',     		'select_multi',   false, '', 3, 12, '',['id','title']],
		// ['狀態',			'status',     		'select',   false, '', 3, 12, '',['id','text']],
		['',			'username',     		'',   TRUE, '', 12, 12, ''],
		// ['職稱',		'now',     		'checkbox_2',   false, '', 3, 12, ''],
		// ['',			'username',     		'',   TRUE, '', 12, 12, ''],
		// ['專長',		'specialty',     		'select',   TRUE, '', 3, 12, '',['id','text']],
		// ['',			'username',     		'',   TRUE, '', 12, 12, ''],
		// ['學術專長',	'specialty',     		'text',   TRUE, '請輸入學術專長', 3, 12, ''],
		// [' ',			'specialty_2',     		'text',   false, '請輸入學術專長', 3, 12, ''],
		// ['',			'username',     		'',   TRUE, '', 12, 12, ''],
		// ['最後異動時間',  'edit_date',     		'select',   TRUE, '', 3, 12, '',['id','text']],
	];
	private $edit_department_param = [
		['帳號資料',	'',		'header',   TRUE, '', 12, 12, ''],
		['姓名',		'username',     		'text',   TRUE, '', 3, 12, ''],
		['帳號(Email)',	'email',        		'text',   TRUE, '', 3, 12, ''],
        // ['管理系所',		'department_id',     		'select_multi',   false, '', 3, 12, '', ['id','title']],
		// ['狀態',			'status',     		'select',   false, '', 3, 12, '',['id','text']],
		['',			'username',     		'',   TRUE, '', 12, 12, ''],
		['變更密碼',	'',		'header',   TRUE, '', 12, 12, ''],
		['(如不修改密碼，留空白即可)',	'',		'content',   FALSE, '', 12, 12, ''],
		['輸入密碼',	'password',      	'password',   FALSE, '', 3, 12, ''],
		['再次輸入密碼',	'password_confirm',      'password',   FALSE, '', 3, 12, ''],
		['',			'username',     		'',   TRUE, '', 12, 12, ''],
		
	];
	public function index(Request $request, $status = 'normal')
	{
		$privilege_id=Auth::guard('mgr')->user()->privilege_id;
		if($privilege_id!=1) echo '<script> history.back(); </script>';
		// print_r($privilege_id);exit;
		// print 123;exit;
		$this->data['controller'] = 'member';
		$this->data['title']      = "帳號管理";
		$this->data['parent']     = "";
		$this->data['parent_url'] = "";
		$this->data['th_title']   = $this->th_title_field(
			[
				['#', '', ''],
				['姓名', '', ''],
				['帳號', '', ''],
				['所屬科系', '', ''],
				['管理系所', '', ''],
				// ['身分別', '', ''],
				['權限群組', '', ''],
				['狀態', '', ''],
				['建立時間', '', ''],
				['更新時間', '', ''],
				['動作', '', '']
			]
		);
		$this->data['btns'] = [
			['<i class="ri-add-fill"></i>', '新增帳號', route('mgr.member.add'), 'primary'],
		];

		// $this->data['bar2_btns'] = [
		// 	['新增專長', 'window.open(\''.route('mgr.committeeman.add_specialty').'\');', 'primary', '2'],
		// ];
		
		$this->data['type']='';
		$this->data['template_item'] = 'mgr/items/template_item';

		$data = array();
		// $data = Member::with('privilege')->get();
		$data = Member::with('privilege')->with('my_department')->get();
		// $data = Member::with('department')->get();
		$role = Auth::guard('mgr')->user()->role;
		// print_r($data);exit;

		$this->data['data'] = array();
		foreach ($data as $item) {
			// print_r($item);exit;
			// print 123;exit;
			$status = '正常';
			if (
				($role == 'super' || $role == 'mgr')
			) {
				$status = '
					<div class="form-check form-switch">
						<input class="form-check-input switch_toggle" data-id="'.$item->id.'" type="checkbox" role="switch" '.(($item->status=='on')?'checked':'').'>
					</div>
				';
			}else{
				if($item->status == 'off') $status = '<span class="">關閉</span>';
			}
			$department_title=array();
			$department=$item->department_array();
			foreach($item->department_array() as $department_id){
				$data=Department::where('id',$department_id)->first();
				$department_title[]=$data->title;
				// print_r($data->title);exit;
			}
			$departmen_all=implode("、",$department_title);

			$obj = array();
			$obj[] = $item->id;
			if (Auth::guard('mgr')->user()->role == 'super') {
				$obj[] = '<a href="'.route('mgr.simulate', ['id'=>$item->id]).'">'.$item->username.'</a>';	
			}else{
				$obj[] = $item->username;
			}			
			$email = $item->email;
			// if ($item->line_id != '') {
			// 	$email .= '<br><span class="badge rounded-pill bg-success">Line@綁定</span>';
			// }
			// $obj[] = $email;
			$obj[] = $item->ID_number;
			$obj[] = $item->my_department->title??'';
			$obj[] = $departmen_all;
			// $obj[] = $this->select_array_to_key_array($this->data['select']['role'])[$item->role];
			$obj[] = $item->privilege->title;
			$obj[] = $status;
			$obj[] = $item->created_at;
			$obj[] = $item->updated_at;
			$priv_edit_academics = false;
			$other_btns = array();
			if ($item->line_id != '') {
				$other_btns[] = array(
					"class"  => "btn-success",
					"action" => "location.href='".route('mgr.member.unlink_line', ['id'=>$item->id])."'",
					"text"   => "解除Line綁定"
				);
			}

			$this->data['data'][] = array(
				"id"         => $item->id,
				"data"       => $obj,
				"other_btns" => $other_btns,
				"priv_edit_academics"  => $priv_edit_academics,
			);
		}
		
		// return view('mgr/template_list', $this->data);
		$this->data['is_search'] = false;
		return view('mgr/template_list_ajax', $this->data);
	}

	public function data(Request $request){
		// $search='21';
		// print 12321;exit;
		$page   = $request->post('page')??'';
		$search = $request->post('search')??'';
		$action = $request->post('action')??'normal';
		$point_enabled = $request->post('point_enabled')??'';
		$page_count = $request->post('page_count')??$this->page_count;
		// print_r($search);exit;
        // print 1231;exit;
		$html='';
		$data = array();
		// $data = Member::with('privilege')->get();
		$data = Member::with('privilege')->with('my_department');
		// $data = Member::with('department')->get();
		$role = Auth::guard('mgr')->user()->role;
		// print_r($data);exit;

		$total = $data->count();
        $total_page = ($page_count!='all')?(($total % $page_count == 0) ? floor(($total)/$page_count) : floor(($total)/$page_count) + 1):'1';
		
		$data = $data->take($page_count)->skip( ($page - 1) * $page_count )->get();

		$this->data['data'] = array();
		foreach ($data as $item) {
			// print_r($item);exit;
			// print 123;exit;
			// $status = '正常';
			if (
				($role == 'super' || $role == 'mgr')
			) {
				$status = '
					<div class="form-check form-switch">
						<input class="form-check-input switch_toggle" data-id="'.$item->id.'" type="checkbox" role="switch" '.(($item->status=='on')?'checked':'').'>
					</div>
				';
			}else{
				if($item->status == 'off') $status = '<span class="">關閉</span>';
			}
			$department_title=array();
			$department=$item->department_array();
			foreach($item->department_array() as $department_id){
				$data=Department::where('id',$department_id)->first();
				$department_title[]=$data->title;
				// print_r($data->title);exit;
			}
			$departmen_all=implode("、",$department_title);

			$obj = array();
			$obj[] = $item->id;
			if (Auth::guard('mgr')->user()->role == 'super') {
				$obj[] = '<a href="'.route('mgr.simulate', ['id'=>$item->id]).'">'.$item->username.'</a>';	
			}else{
				$obj[] = $item->username;
			}			
			$email = $item->email;
			// if ($item->line_id != '') {
			// 	$email .= '<br><span class="badge rounded-pill bg-success">Line@綁定</span>';
			// }
			// $obj[] = $email;
			$obj[] = $item->ID_number;
			$obj[] = $item->my_department->title??'';
			$obj[] = $departmen_all;
			// $obj[] = $this->select_array_to_key_array($this->data['select']['role'])[$item->role];
			$obj[] = $item->privilege->title;
			$obj[] = $status;
			$obj[] = $item->created_at;
			$obj[] = $item->updated_at;
			$priv_edit_academics = false;
			$priv_edit = TRUE;
			$priv_del = TRUE;
			$other_btns = array();

			//////////////////////////////////////
			// $this->data['data'][] = array(
			// 	"id"         => $item->id,
			// 	"data"       => $obj,
			// 	"other_btns" => $other_btns,
			// 	"priv_edit_academics"  => $priv_edit_academics,
			// );
		// print_r($obj);exit;

            $html .= view('mgr/items/template_item', [
				'item'      => array(
					"id"         => $item->id,
					"data"       => $obj,
					"other_btns" => $other_btns,
					"priv_edit_academics"  => $priv_edit_academics,
					"priv_edit"  => $priv_edit,
					"priv_del"   => $priv_del
				),
				// 'th_title'  => $this->th_title_field($this->th_title)
			])->render();
			
		}
		
		$this->output(TRUE, 'success', array(
			'html'	=>	$html,
			'page'       => $page,
			'total_page' => $total_page,
			// 'total'      => $total,
			// 'page_count' => $page_count
		));
		
	}

	public function unlink_line(Request $request, $id){
		if (Member::where('id', $id)->update(['line_id'=>''])) {
			$this->js_output_and_redirect("已解除綁定", 'mgr.member');
		}else{
			$this->js_output_and_back("解除發生錯誤");
		}
	}

	public function add(Request $request){
		
		if ($request->isMethod('post')) {
			$formdata = $this->process_post_data($this->add_param, $request);
			
			if (Member::where('email', $formdata['email'])->count() > 0){
				$this->js_output_and_back('Email已存在');
				exit();
			}

			if ($formdata['password'] == ''){
				unset($formdata['password']);
			}else{
				if ($formdata['password'] != $formdata['password_confirm']) $this->js_output_and_back('兩次密碼輸入不相同');
				$formdata['password'] = md5($formdata['password']);
				// $formdata['password'] = Hash::make($formdata['password']);
			}
			unset($formdata['password_confirm']);

			// print_r($formdata);exit;
			$formdata['department_id']=$formdata['my_department_id'];

			$res = Member::create($formdata);
			if ($res) {
				
				$this->js_output_and_redirect('新增成功', 'mgr.member');
			} else {
				$this->js_output_and_back('新增發生錯誤');
			}
			exit();
		}

		$this->data['title'] = "新增帳號";
		$this->data['parent'] = "帳號管理";
		$this->data['parent_url'] = route('mgr.member');
		$this->data['action'] = route('mgr.member.add');
		$this->data['submit_txt'] = '確認新增';

		$this->data['params'] = $this->generate_param_to_view($this->add_param);
		$this->data['select']['subordinate'] = Member::where('id', '!=', '1')->get()->toArray();


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

	public function edit(Request $request, $id){
		$writer_id=Auth::guard('mgr')->user()->id;

		$member_id=Auth::guard('mgr')->user()->id;
		$member_username=Auth::guard('mgr')->user()->username;
		
		$data = Member::find($id);
		if ($request->isMethod('post')) {
			$formdata = $this->process_post_data($this->param, $request);
			// print_r($formdata);exit;
			// // print_r($formdata);exit;
			// $change_data=array();
			// if($formdata['username']!=$data->username){
			// 	$change_data[]= '修改姓名:'.$formdata['username'];	
			// }
			// if($formdata['email']!=$data->email){
			// 	$change_data[]= '修改連絡電話:'.$formdata['email'];
			// }
			// if($formdata['privilege_id']!=$data->privilege_id){
			// 	$change_data[]= '修改管理權限:'.$formdata['privilege_id'];
			// }
			// if($formdata['my_department_id']!=$data->my_department_id){
			// 	$change_data[]= '修改所屬系所:'.$formdata['my_department_id'];
			// }
			// if($formdata['email']!=$data->email){
			// 	$change_data[]= '修改連絡電話:'.$formdata['email'];
			// }
			// $change_list=implode("、",$change_data);


			if (Member::where('ID_number', $formdata['ID_number'])->where('id','!=',$id)->count() > 0){
				$this->js_output_and_back('此身分證字號 已存在');
				exit();
			}
			if ($formdata['password'] == ''){
				unset($formdata['password']);
			}else{
				if ($formdata['password'] != $formdata['password_confirm']) $this->js_output_and_back('兩次密碼輸入不相同');
				$formdata['password'] = md5($formdata['password']);
				// $formdata['password'] = Hash::make($formdata['password']);
			}
			unset($formdata['password_confirm']);

			$old_department=explode('、', $data->department_id);
			for($i=0;$i<count($old_department);$i++){
				$d_data['member_id']=$id;
				// $d_data['writer_id']=$writer_id;
				$d_data['title_id']=$old_department[$i];
				$res = MemberDepartment::where('member_id', $d_data['member_id'])
										// ->where('writer_id', $d_data['writer_id'])
										->where('title_id', $d_data['title_id'])
										->first();
				// print_r($res->id);
				if(isset($res)){
					$res->delete($res->id);
				}
				
				
			}
			$new_department=$formdata['department_id'];
			if($new_department==''){
				// $formdata['department_id']
				$num=1;
				$department_data['department_id']=$formdata['my_department_id'];
				$formdata['department_id']=$formdata['my_department_id'];
			}else{
				$num=count($formdata['department_id']);
				$department_data['department_id']=implode("、",$new_department);
			}
			
			// unset($formdata['department_id']);
			// print_r($formdata['department_id']);exit;
			for($i=0;$i<$num;$i++){
				
				$d_data['member_id']=$id;
				$d_data['writer_id']=$writer_id;
				$d_data['title_id']=$formdata['department_id'][$i];
				// unset($formdata['email']);
				// unset($formdata['department_id']);
				// print_r($d_data);

				$res = MemberDepartment::updateOrCreate($d_data);
						Member::updateOrCreate(['id'=>$id],$department_data);
				
			}
			$formdata['department_id']=$department_data['department_id'];
			
			$res = Member::updateOrCreate(['id'=>$id], $formdata);
			if ($res) {
			
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


		$department=$data->department_array();
		// print_r($department);exit;
		$data['department_id'] = $department;
		// print_r($data);exit;
		// $data = $data->toArray();
		
		$this->data['params'] = $this->generate_param_to_view($this->param, $data);
		// print_r($this->data['params']);exit;
		// $this->data['select']['subordinate'] = Member::where('id', '!=', Auth::guard('mgr')->user()->id)->get()->toArray();
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

	///0522
    public function department_manage(request $request){
		$this->js_output_and_redirect('編輯成功', 'mgr.member.department_view');


		$member_id=Auth::guard('mgr')->user()->id;
		$member_username=Auth::guard('mgr')->user()->username;

		$this->data['controller'] = 'member';
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
		// $this->data['btns'] = [
		// 	['<i class="ri-add-fill"></i>', '新增管理科系', route('mgr.member.department_add'), 'primary']
		// ];
		$this->data['type']='';
		$this->data['template_item'] = 'mgr/items/department_item';

		$data = array();
		// $data = Member::with('privilege')->with('department')->get();
		// $role = Auth::guard('mgr')->user()->role;
		$data = Member::with('get_member_department')->where('id',$member_id)->get();
		// print_r($data);exit;
		// print_r(count($data));exit;
		$role = Auth::guard('mgr')->user()->role;
		$this->data['data'] = array();
		$x=1;
		// print_r(count($data));exit;
		foreach ($data as $item) {
			$status = '正常';
			if (
				($role == 'super' || $role == 'mgr')
			) {
				$status = '
					<div class="form-check form-switch">
						<input class="form-check-input switch_toggle" data-id="'.$item->id.'" type="checkbox" role="switch" '.(($item->status=='on')?'checked':'').'>
					</div>
				';
			}else{
				if($item->status == 'off') $status = '<span class="">關閉</span>';
			}
			$department_title=array();
			$department=$item->department_array();
			foreach($item->department_array() as $department_id){
				$data=Department::where('id',$department_id)->first();
				$department_title[]=$data->title;
				// print_r($data->title);exit;
			}
			$departmen_all=implode("、",$department_title);
		
			// print_r($department_data);
			// exit;
			$obj = array();
			$obj[] = $x;
            $obj[] = $item->username;
            $obj[] = $item->email;
            $obj[] = $departmen_all;
            $obj[] = $status;
            $priv_edit_department = true;
			$priv_view_department = true;
            $priv_edit = false;
			$priv_del = false;
			$item_member_id=$item->member_id;
			// $member_id=Auth::guard('mgr')->user()->id;
			// $other_btns = array();

			
			$this->data['data'][] = array(
				"id"         => $item->id,
				"data"       => $obj,
                "priv_edit"  => $priv_edit,
                "priv_del"   => $priv_del,
				// "item_member_id"  => $item_member_id,
				// "member_id"  => $member_id,
				// "other_btns" => $other_btns
			);
			$x++;
		}
		// exit;
            // $obj[] = '1';
            // $obj[] = 'admin';
            // $obj[] = 'admin';
            // $obj[] = $department_data;
            // $obj[] = '啟用';
			
			// // $obj[]  = '';priv_edit_department
			// $priv_edit_department = true;
            // $priv_edit = false;
			// $priv_del = false;

            // $this->data['data'][] = array(
			// 	"id"         => 1,
			// 	"data"       => $obj,
            //     "priv_edit"  => $priv_edit,
            //     "priv_del"   => $priv_del,
			// 	"priv_edit_department"   => $priv_edit_department,
			// 	// "other_btns" => $other_btns
			// );

        // print_r($this->data['data']);exit;
		return view('mgr/template_list', $this->data);
	}
	public function department_view(Request $request){
		// $member_id=Auth::guard('mgr')->user()->id;
		// print_R($id);
		$member_id=Auth::guard('mgr')->user()->id;
		$my_department_id=Auth::guard('mgr')->user()->my_department_id;
		// print_r($my_department_id);exit;
		$member_id=Auth::guard('mgr')->user()->id;
		$privilege_id=Auth::guard('mgr')->user()->privilege_id;
		$department_id=Auth::guard('mgr')->user()->department_id;
		// print_r($department_id);exit;
		$department_id= explode('、', $department_id);
		// foreach($department_id as $d_id){
		// 	print_r($d_id);
		// }
		// exit;
		// print_r($department_id);exit;
		$this->data['controller'] = 'committeeman';
		$this->data['title']      = "管理系所資料列表";
		$this->data['parent']     = "";
		$this->data['parent_url'] = "";
		$this->data['th_title']   = $this->th_title_field(
			[
				['#', '', ''],
				['姓名', '65px', ''],
				['服務單位', '80px', ''],
				['單位名稱', '80px', ''],
				['職稱', '', ''],
				['曾任', '80px', ''],
				['單位名稱', '', ''],
				['職稱', '', ''],
				['連絡電話', '', ''],
				['電子郵件信箱', '120px', ''],
				['相關資料網址', '', ''],
				['學門專長', '150px', ''],	
				['學術專長', '200px', ''],
				['最後異動時間', '120px', ''],
				['狀態', '', ''],
				['動作', '', ''],
			]
		);
		// $this->data['btns'] = [
		// 	['<i class="ri-add-fill"></i>', '新增推薦資料', route('mgr.recommend_form.add'), 'primary']
		// ];
		$this->data['type']='';
		$this->data['template_item'] = 'mgr/items/department_view_item';

		$data = array();
		// $data_writer=array();
		// $data_writer=Member::with('writer')->get();
		// // print_r($data_writer);exit;
		// $j=0;
		// foreach($data_writer as $d_writer){
		// 	$academic[$j]=$d_writer->username;
		// 	$j++;
		// }
		// print_r($academic);exit;
		$academic_list=array();
		DB::enableQueryLog();
		if($privilege_id==1){
			$data=Committeeman::with('specialty')
							->with('academic')
							// ->with('writer')
							->with('get_member')
							// ->where('member_id',$member_id) //帳號本人才顯示
							->get();
		}else{
			$data=Committeeman::
			select('committeemen.*','academics.writer_id','members.my_department_id','members.department_id')
			->leftJoin('academics', function($leftJoin) use($academic_list)
									{
										$leftJoin->on('academics.committeeman_id', '=', 'committeemen.id');
									})
			->leftJoin('members', function($leftJoin) use($academic_list)
									{
										$leftJoin->on('members.id', '=', 'committeemen.member_id');
									})
				
			->with('specialty')
			// ->with('now_service_unit')
			// ->with('old_service_unit')
			// ->with('now_title')
			// ->with('old_title')
			->with('academic')
			// ->with('writer')
			->with('get_member')
			->where('member_id',$member_id) //帳號本人才顯示
			// ->orwhere('academics.writer_id',$member_id)
			->orwhere('my_department_id',$my_department_id)
			->orwhere(function($query) use ($department_id) {
				// print_r($now_title);exit;
					if(isset($department_id)){
						foreach($department_id as $d_id){
							
							$query->orWhere('members.department_id','like', '%'. $d_id.'%');
						}
					}
				})
			->groupBy('id')
			->get();
		}

		// dd(DB::getQueryLog());exit;
		// $data = Member::with('privilege')->with('department')->get();
		// $role = Auth::guard('mgr')->user()->role;

		// print_r($data);exit;

		$this->data['data'] = array();
		foreach ($data as $item) {
			
			
			
			// print_r($item->get_member->department_id);
			// print_r($item->get_member->my_department_id);
			//取得所有科系
			// $department=explode('、', $item->get_member->department_id);
			// $department[]=$item->get_member->my_department_id;
			// print_r($department);

			//取得多筆學門專長
			$j=0;
			$specialty_list=array();
			foreach($item->specialty as $s_title){
				// print_r($s_title->title_id);
				// exit;
				$writer_data=Member::find($s_title->writer_id);
				$specialty_list_data=SpecialtyList::find($s_title->title_id);
				// print_r($writer_data);exit;
				// $academic[$i]['title']=$a_title->title;
				// $academic[$i]['writer_name']=$writer_data->username;
				// $academic[$i]['create_date']=$a_title->create_date;

				$specialty_list[$j]=$specialty_list_data->title;
				$j++;
			}
			
			$specialty_data=implode("、",$specialty_list);
			// print_r($specialty_list);
			// print '___';
			//取得多筆學術專長
			$i=0;
			$academic_list=array();
			// print_r($item->academic);
			// exit;
			foreach($item->academic as $a_title){
				// print_r($a_title);exit;
				$writer_data=Member::find($a_title->writer_id);

				// print_r($writer_data);exit;
				// $academic[$i]['title']=$a_title->title;
				// $academic[$i]['writer_name']=$writer_data->username;
				// $academic[$i]['create_date']=$a_title->create_date;
				$academic_updated_at[$i]=$a_title->updated_at;
				$academic_writer_id[$i]=$a_title->writer_id;
				$academic_list[$i]=$a_title->title.'('.$writer_data->username.'，'.$a_title->create_date.'建立)';
				$i++;
			}
			// print_r($academic_writer_id);exit;
			// $academic_w_id=implode("、",$academic_writer_id);
			// print_r($academic_w_id);
			
			$academic_data=implode("、",$academic_list);
			// print_r($academic_data);
			// if(isset($item->now_service_unit->title)){
			// 	print_r($item->now_service_unit->title);
			// }

		// }
		// exit;
		// $status = '正常';
		
			// $status = '
			// 		<div class="form-check form-switch">
			// 			<input class="form-check-input switch_toggle" data-id="'.$item->id.'
			// 			" type="checkbox" role="switch" '.(($item->status=='on')?'checked':'').'>
			// 		</div>
			// 	';
			
			// if($item->status=='on'){
			// 	$status_str='啟用';
			// }else{
			// 	$status_str='關閉';
			// }
			// if(Auth::guard('mgr')->user()->id!=1){
			// 	if($item->member_id!=Auth::guard('mgr')->user()->id){
			// 		$status=$status_str;
			// 	}
			// }
			$last_updated_at=$item->updated_at;
			//抓出最後修改時間
		// foreach($academic_updated_at as  $key => $a_updated_at){
			
		// 	if($item->updated_at > $a_updated_at){
		// 		// print 123;
		// 		$last_updated_at=$item->updated_at;
		// 	}else{
		// 		// print 456;
		// 		$last_updated_at=$item->a_updated_at;
		// 	}
		// 	// print '__';
		// 	// print_r($a_updated_at);
		// }
		// foreach($academic_updated_at as   $a_updated_at){
		// 	if($last_updated_at < $a_updated_at){
		// 		$last_updated_at=$a_updated_at;
		// 	}

		// }
			$role = Auth::guard('mgr')->user()->role;
			$status = '正常';
			if (
				($role == 'super' || $role == 'mgr')
			) {
				$status = '
					<div class="form-check form-switch">
						<input class="form-check-input switch_toggle" data-id="'.$item->id.'" type="checkbox" role="switch" '.(($item->status=='on')?'checked':'').'>
					</div>
				';
			}else{
				if($item->status == 'off') $status = '<span class="">關閉</span>';
			}
			
			$obj = array();
			$obj[] = $item->id;  
            $obj[] = $item->username; 					//姓名
            $obj[] = (isset($item->now_service_unit->title))? $item->now_service_unit->title:"";  	//服務單位
			$obj[] = $item->now_unit;  					//單位名稱
            $obj[] = $item->now_title->title; 		 	//職稱
            $obj[] = (isset($item->old_service_unit->title))?$item->old_service_unit->title:''; 	//曾任
			// $obj[]  = $item->old_title->title; 			//單位名稱
			$obj[]  = (isset($item->old_unit))?$item->old_unit:""; 			 		//單位名稱
			$obj[]  = (isset($item->old_title->title))?$item->old_title->title:""; 			//職稱

			$obj[] = $item->phone; 
			$obj[] = $item->email; 
			$obj[] = $item->url; 

			$obj[]  = $specialty_data; 			//學門專長
            // $obj[] = '物理化學(XXX，2023/4/1建立)';		//學術專長
			$obj[] =  $academic_data;				//學術專長
            $obj[]  = $last_updated_at;
            $obj[]  = $status;
			// $academics_writer_id=
            $priv_edit = TRUE;
			$priv_edit_academics = TRUE;
			$priv_edit_specialty = TRUE;
			$priv_del = false;
			$item_member_id=$item->member_id;
			$member_id=Auth::guard('mgr')->user()->id;
			$my_department=$item->get_member->my_department_id;
			// print_r($my_department);
			// exit;
			// exit;
			// 登入人科系
			// $member_id=Auth::guard('mgr')->user()->id;
			$member_login=Member::find($member_id);
			$member_department=explode('、', $member_login->department_id);
			// $member_department[]=$member_login->my_department_id;
			// print_r($member_department);
			// exit;
			$other_btns = array();
	
			$this->data['data'][] = array(
				"id"         => $item->id,
				"data"       => $obj,
                "priv_edit"  => $priv_edit,
				"priv_edit_academics"  => $priv_edit_academics,
				"priv_edit_specialty"  => $priv_edit_specialty,
                "priv_del"   => $priv_del,
				"item_member_id"  => $item_member_id,
				"member_id"  => $member_id,
				"my_department"  => $my_department,
				"member_department"  => $member_department,
				"academic_w_id"		=>$academic_writer_id,
				"privilege_id"		=>$privilege_id
				// "other_btns" => $other_btns
			);
			// print_r($member_department);
			// print_r($my_department);
			// print '_';
		}
		
		// exit;
           

        // print_r($this->data['data']);exit;
		return view('mgr/template_list', $this->data);
	}
	public function department_edit(Request $request, $id){
		$writer_id=Auth::guard('mgr')->user()->id;
		// print 12123;exit;
		$data=Member::find($id);
		// print_r($data->department_id);
		
		// print_r($old_department);exit;

		if ($request->isMethod('post')) {
			$formdata = $this->process_post_data($this->edit_department_param, $request);
			// print_r($formdata);exit;
			if (Member::where('ID_number', $formdata['ID_number'])->where('id','!=',$id)->count() > 0){
				$this->js_output_and_back('此身分證字號 已存在');
				exit();
			}
			if ($formdata['password'] == ''){
				unset($formdata['password']);
			}else{
				if ($formdata['password'] != $formdata['password_confirm']) $this->js_output_and_back('兩次密碼輸入不相同');
				$formdata['password'] = md5($formdata['password']);
				// $formdata['password'] = Hash::make($formdata['password']);
			}
			unset($formdata['password_confirm']);

			
			// $old_department=explode('、', $data->department_id);
			// // print_r($old_department);exit;
			
			// for($i=0;$i<count($old_department);$i++){
			// 	$d_data['member_id']=$id;
			// 	// $d_data['writer_id']=$writer_id;
			// 	$d_data['title_id']=$old_department[$i];
			// 	$res = MemberDepartment::where('member_id', $d_data['member_id'])
			// 							// ->where('writer_id', $d_data['writer_id'])
			// 							->where('title_id', $d_data['title_id'])
			// 							->first();
			// 	// print_r($res->id);
			// 	if(isset($res)){
			// 		$res->delete($res->id);
			// 	}
				
				
			// }
			
			// // exit;
			// $new_department=$formdata['department_id'];
			// $department_data['department_id']=implode("、",$new_department);
			// // unset($formdata['department_id']);
			// // print_r($department_data);exit;
			// for($i=0;$i<count($formdata['department_id']);$i++){
				
			// 	$d_data['member_id']=$id;
			// 	$d_data['writer_id']=$writer_id;
			// 	$d_data['title_id']=$formdata['department_id'][$i];
			// 	// unset($formdata['email']);
			// 	// unset($formdata['department_id']);
			// 	// print_r($d_data);

			// 	$res = MemberDepartment::updateOrCreate($d_data);
			// 			Member::updateOrCreate(['id'=>$id],$department_data);
				
			// }
			// $formdata['department_id']=$department_data['department_id'];
			// print_r($formdata);exit;
			$res = Member::updateOrCreate(['id'=>$id], $formdata);
			// print_r($data->department_id);
			// exit;

			
			// $res = MemberDepartment::updateOrCreate(['id'=>$id], $formdata);
			if ($res) {
				$this->js_output_and_redirect('編輯成功', 'mgr.member.department_manage');
			} else {
				$this->js_output_and_back('編輯發生錯誤');
			}
			exit();
		}
		// $2y$10$4366i0NSO7jboKNbaVLkFubgYg5qnovDQWFks4uPKW0zZ7nIpfDue
		$this->data['title'] = "編輯管理系所 ".$data['username'];
		$this->data['parent'] = "管理系所列表";
		$this->data['parent_url'] = route('mgr.committeeman');
		$this->data['action'] = route('mgr.committeeman.edit', ['id'=>$id]);
		$this->data['submit_txt'] = '確認編輯';

		$department=$data->department_array();
		// print_r($department);exit;
		$data['department_id'] = $department;
		// print_r($data);exit;
		
		
		
		$this->data['params'] = $this->generate_param_to_view($this->edit_department_param, $data);
		// print_r($this->data['params']);exit;
		// $this->data['params'] = $this->generate_param_to_view($this->param, $data);

		// print_r($this->data);exit;
		
		//使用者僅要撈出自己管的＆尚未指派
		$this->data['select']['users'] = array();
		

		
		

		return view('mgr/template_form', $this->data);
	}

	public function department_add(request $request){
		$writer_id=Auth::guard('mgr')->user()->id;
		// print 123;
		// exit;
		if ($request->isMethod('post')) {

			// $this->js_output_and_redirect('新增成功', 'mgr.member.department_manage');
			// print 123;
			$formdata = $this->process_post_data($this->add_department_param, $request);
			// print_r(count($formdata['department_id']));exit;
			for($i=0;$i<count($formdata['department_id']);$i++){
				
				$data['member_id']=$formdata['email'];
				$data['writer_id']=$writer_id;
				$data['title_id']=$formdata['department_id'][$i];
				// unset($formdata['email']);
				// unset($formdata['department_id']);
				print_r($data);
			}
			exit;
			// $member_data=Member::where($formdata['email']);
			// $formdata['title_id']=;
			// $formdata['member_id']=$formdata['email'];
			// $formdata['writer_id']=$writer_id;
			
			// $formdata['title_id']=;
			// print_r($formdata);exit;
			$res=MemberDepartment::updateOrCreate($data);
			// ChangeRecord::add_change_record($change_data);
			exit;
			// $res = Committeeman::updateOrCreate($formdata);
			if ($res) {
				$this->js_output_and_redirect('儲存成功', 'mgr.committeeman');
			} else {
				$this->js_output_and_back('編輯發生錯誤');
			}
			exit();
			
		}

		$this->data['title'] = "新增管理系所";
		$this->data['parent'] = "系所管理";
		$this->data['parent_url'] = route('mgr.committeeman');
		$this->data['action'] = route('mgr.committeeman.department_add');
		$this->data['submit_txt'] = '新增';

		$this->data['params'] = $this->generate_param_to_view($this->add_department_param);
		

		return view('mgr/template_form', $this->data);
	}
}