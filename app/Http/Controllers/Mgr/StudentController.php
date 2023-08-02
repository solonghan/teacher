<?php

namespace App\Http\Controllers\Mgr;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Privilege;
use App\Models\MemberDepartment;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth;

class StudentController extends Mgr
{

	public function __construct()
	{
		parent::__construct();
		$this->data['active'] = 'MEMBER';
		$this->data['sub_active'] = 'MEMBER';

		// $this->data['select']['privilege_id'] = Privilege::get()->toArray();
		// $this->data['select']['department_id'] = MemberDepartment::get()->toArray();
		// // $this->data['select']['products'] = Product::get()->toArray();
		// // $this->data['select']['users'] = User::where(['status'=>'normal'])->get()->toArray();

		// $this->data['select']['role'] = array(
		// 	array("id"=>"super", "text"=>"最高權限"),
		// 	array("id"=>"director", "text"=>"大主管/總監"),
		// 	array("id"=>"mgr", "text"=>"主管"),
		// 	array("id"=>"saler", "text"=>"業務"),
		// 	array("id"=>"accounting", "text"=>"會計"),
		// 	array("id"=>"depot", "text"=>"倉管"),
		// 	array("id"=>"assistant", "text"=>"業助"),
		// );
	}

	private $param = [
		['姓名',		'username',     		'text',   TRUE, '', 3, 12, ''],
		['帳號(Email)',	'email',        		'text',   TRUE, '', 3, 12, ''],
		['身分別',		'role', 			'select',   TRUE, '', 2, 12, '', ['id','text']],
		['群組權限',	'privilege_id', 	'select',   TRUE, '', 2, 12, '', ['id','title']],
		['所屬部門',	'department_id', 	'select',   TRUE, '', 2, 12, '', ['id','title']],
		['手機',	   'mobile',        	'text',   TRUE, '', 2, 12, ''],
		['公司電話', 	'tel',        		'text',   TRUE, '', 2, 12, ''],
		['分機',	   'ext',        		'text',   FALSE, '', 2, 12, ''],
		['傳真',	   'fax',        		'text',   TRUE, '', 2, 12, ''],

		['變更密碼',	'',		'header',   TRUE, '', 12, 12, ''],
		['輸入密碼',	'password',      	'password',   FALSE, '', 4, 12, ''],
		['再次輸入密碼',	'password_confirm',      'password',   FALSE, '', 4, 12, ''],

		// ['可管理下屬',	'',		'header',   TRUE, '', 12, 12, ''],
		// ['請選擇業務/下屬帳號',	'subordinate',		'select_multi',		FALSE, '', 6, 12, '', ['id', 'username']],

		['可管理產品',	'',		'header',   TRUE, '', 12, 12, ''],
		['請選擇產品',	'products',		'select_multi',		FALSE, '', 6, 12, '', ['id', 'name']],
		['業助管理產品(業助only)',	'products_assistant',		'select_multi',		FALSE, '', 6, 12, '', ['id', 'name']],

		['可管理會員',	'',		'header',   TRUE, '', 12, 12, ''],
		['請選擇會員',	'users',		'select_multi',		FALSE, '', 6, 12, '', ['id', 'username']],
	];
	public function index(Request $request, $status = 'normal')
	{
		$this->data['controller'] = 'member';
		$this->data['title']      = "帳號管理";
		$this->data['parent']     = "";
		$this->data['parent_url'] = "";
		$this->data['th_title']   = $this->th_title_field(
			[
				['#', '', ''],
				['姓名', '', ''],
				['帳號', '', ''],
				['部門', '', ''],
				['身分別', '', ''],
				['權限群組', '', ''],
				['狀態', '', ''],
				['建立時間', '', ''],
				['動作', '', '']
			]
		);
		$this->data['btns'] = [
			['<i class="ri-add-fill"></i>', '新增帳號', route('mgr.member.add'), 'primary']
		];

		$this->data['template_item'] = 'mgr/items/template_item';

		$data = array();
		$data = Member::with('privilege')->with('department')->get();
		$role = Auth::guard('mgr')->user()->role;

		$this->data['data'] = array();
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

			$obj = array();
			$obj[] = $item->id;
			if (Auth::guard('mgr')->user()->role == 'super') {
				$obj[] = '<a href="'.route('mgr.simulate', ['id'=>$item->id]).'">'.$item->username.'</a>';	
			}else{
				$obj[] = $item->username;
			}			
			$email = $item->email;
			if ($item->line_id != '') {
				$email .= '<br><span class="badge rounded-pill bg-success">Line@綁定</span>';
			}
			$obj[] = $email;
			$obj[] = $item->department->title??'';
			$obj[] = $this->select_array_to_key_array($this->data['select']['role'])[$item->role];
			$obj[] = $item->privilege->title;
			$obj[] = $status;
			$obj[] = $item->created_at;

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
				"other_btns" => $other_btns
			);
		}

		return view('mgr/template_list', $this->data);
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
			$formdata = $this->process_post_data($this->param, $request);
			
			if (Member::where('email', $formdata['email'])->count() > 0){
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
			$formdata['create_by'] = Auth::guard('mgr')->user()->id;
			$formdata['update_by'] = Auth::guard('mgr')->user()->id;

			$res = Member::create($formdata);
			if ($res) {
				$res->subordinate_refresh($request->post('subordinate'));
				$res->manage_product_refresh($request->post('products'));
				$res->manage_user_refresh($request->post('users'));

				//若為業助，則可管理商品
				if ($formdata['role'] == 'assistant') {
					if ($request->post('products_assistant')){
						foreach ($request->post('products_assistant') as $product_id) {
							Product::where('id', $product_id)->update(['assistant'=>$res->id]);
						}
					}
				}
				$this->js_output_and_redirect('新增成功', 'mgr.member');
			} else {
				$this->js_output_and_back('新增發生錯誤');
			}
			exit();
		}

		$this->data['title'] = "新增標籤";
		$this->data['parent'] = "熱門標籤";
		$this->data['parent_url'] = route('mgr.member');
		$this->data['action'] = route('mgr.member.add');
		$this->data['submit_txt'] = '確認新增';

		$this->data['params'] = $this->generate_param_to_view($this->param);
		$this->data['select']['subordinate'] = Member::where('id', '!=', '1')->get()->toArray();

		$this->data['select']['users'] = array();
		foreach (User::where(['status'=>'normal'])->with('manage_user')->get() as $user) {
			if (is_null($user->manage_user) || count($user->manage_user) <= 0){
				$this->data['select']['users'][] = $user->toArray();
				continue;
			}		
		}
		
		//僅要撈出自己管的＆尚未指派的商品
		$this->data['select']['products'] = array();//Product::get()->toArray();
		foreach (Product::where(['status'=>'on', 'lang'=>'tw'])->whereNull('deleted_at')->with('manager')->get() as $product) {
			if (is_null($product->manager) || count($product->manager) <= 0){
				$this->data['select']['products'][] = $product->toArray();
				continue;
			}
		}
		$this->data['select']['products_assistant'] = Product::where(['lang'=>'tw', 'status'=>'on'])->where(function($q) {
			$q->where('assistant', 0);
		})->get()->toArray();
		

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

	public function del(Request $request){
		$id = $request->post('id');

		$obj = Member::find($id);
		if ($obj->delete()) {
			$this->output(TRUE, "Delete success");
		}else{
			$this->output(FALSE, "Delete fail");
		}
	}
}