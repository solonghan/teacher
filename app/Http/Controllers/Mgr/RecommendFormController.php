<?php

namespace App\Http\Controllers\Mgr;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\RecommendForm;
use App\Models\Privilege;
use App\Models\MemberDepartment;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth;

class RecommendFormController extends Mgr
{

	public function __construct()
	{
        
		parent::__construct();
		$this->data['active'] = 'RECOMMEND_FORM';
		$this->data['sub_active'] = 'RECOMMEND_FORM';
		$this->data['select']['now'] = array(
			array("id"=>"公立學校", "text"=>"公立學校"),
			array("id"=>"研究機構", "text"=>"研究機構"),
			array("id"=>"企業(含私立學校)", "text"=>"企業(含私立學校)"),
			);
		$this->data['select']['gender'] = array(
				array("id"=>"all", "text"=>"全部"),
				array("id"=>"male", "text"=>"男"),
				array("id"=>"female", "text"=>"女"),
			);
		$this->data['select']['source'] = array(
				array("id"=>"1", "text"=>"教育部"),
				array("id"=>"2", "text"=>"國科會"),
				array("id"=>"3", "text"=>"個人網頁"),
				array("id"=>"4", "text"=>"其他"),
			);
		$this->data['select']['specialty'] = array(
				array("id"=>"1", "text"=>"教育學門"),
				array("id"=>"2", "text"=>"藝術學門"),
				array("id"=>"3", "text"=>"人文學門"),
				array("id"=>"4", "text"=>"其他學門"),
			);
		$this->data['select']['specialty_classify'] = array(
				array("id"=>"1", "text"=>"教育學"),
				array("id"=>"2", "text"=>"幼兒師資教育"),
				array("id"=>"3", "text"=>"普通科目師資教育"),
				array("id"=>"3", "text"=>"專業科目師資教育"),
				array("id"=>"4", "text"=>"其他教育"),
			);
		$this->data['select']['specialty'][0][]=array("id"=>"1", "text"=>"教育學");
		// print_r($this->data['select']['specialty']);exit;
		
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
        ['',		'username',     		'',   TRUE, '', 12, 12, ''],
        ['性別',		'gender',     		'select',   TRUE, '', 3, 12, '',['id','text']],
        ['',		'username',     		'',   TRUE, '', 12, 12, ''],
        
        ['服務單位',		'',     		'header',   TRUE, '', 3, 12, ''],
        ['',		'username',     		'',   TRUE, '', 12, 12, ''],
        ['目前',		'now',     		'select',   TRUE, '', 3, 12, '',['id','text']],
		['曾任',		'now',     		'select',   TRUE, '', 3, 12, '',['id','text']],
		['',		'username',     		'',   TRUE, '', 12, 12, ''],
        [' ',		'username',     		'text',   TRUE, '請輸入單位名稱', 3, 12, ''],
		[' ',		'username',     		'text',   TRUE, '請輸入單位名稱', 3, 12, ''],
		['',		'username',     		'',   TRUE, '', 12, 12, ''],
		[' ',		'username',     		'text',   TRUE, '補充說明', 3, 12, ''],
		[' ',		'username',     		'text',   TRUE, '補充說明', 3, 12, ''],
        ['',		'username',     		'',   TRUE, '', 12, 12, ''],

        // ['曾任',		'now',     		'select',   TRUE, '', 3, 12, '',['id','text']],
        // [' ',		'username',     		'text',   TRUE, '請輸入單位名稱', 3, 12, ''],
		// [' ',		'username',     		'text',   TRUE, '補充說明', 3, 12, ''],

        ['',		'username',     		'',   TRUE, '', 12, 12, ''],
        // ['專題計畫類別',		'',     		'header',   TRUE, '', 3, 12, ''],
        // ['',		'username',     		'',   TRUE, '', 12, 12, ''],
        // ['大分類',		'username',     		'text',   TRUE, '', 3, 12, ''],
        // ['小分類',		'username',     		'text',   TRUE, '', 3, 12, ''],
        // ['',		'username',     		'',   TRUE, '', 12, 12, ''],

        ['學術專長',		'',     		'header',   TRUE, '', 3, 12, ''],
        ['',		'username',     		'',   TRUE, '', 12, 12, ''],
		['學門/學類',		'specialty',     		'select',   TRUE, '', 3, 12, '',['id','text']],
		['學術專長(研究)',		'specialty',     		'text',   TRUE, '請輸入學術專長', 3, 12, ''],

		['',		'username',     		'',   TRUE, '', 12, 12, ''],
		['',		'specialty_classify',     		'select',   TRUE, '', 3, 12, '',['id','text']],
		['',		'username',     		'',   TRUE, '', 12, 12, ''],

		// if($this->data['select']['specialty_classify'][3]){
		
		// }
		
        // ['',		'source',     		'select',   TRUE, '', 3, 12, '',['id','text']],
        
		['',		'username',     		'',   TRUE, '', 12, 12, ''],
		['資料來源',		'source',     		'select',   TRUE, '', 3, 12, '',['id','text']],
        ['資料來源',		'source',     		'select',   TRUE, '', 3, 12, '',['id','text']],
        ['',		'username',     		'',   TRUE, '', 12, 12, ''],
		[' ',		'specialty',     		'text',   TRUE, '請輸入其他資料來源', 3, 12, ''],
		[' ',		'specialty',     		'text',   TRUE, '請輸入其他資料來源', 3, 12, ''],
        // ['大分類',		'username',     		'text',   TRUE, '', 3, 12, ''],
        // ['小分類',		'username',     		'text',   TRUE, '', 3, 12, ''],
		// ['帳號(Email)',	'email',        		'text',   TRUE, '', 3, 12, ''],
		// ['身分別',		'role', 			'select',   TRUE, '', 2, 12, '', ['id','text']],
		// ['群組權限',	'privilege_id', 	'select',   TRUE, '', 2, 12, '', ['id','title']],
		// ['所屬部門',	'department_id', 	'select',   TRUE, '', 2, 12, '', ['id','title']],
		// ['手機',	   'mobile',        	'text',   TRUE, '', 2, 12, ''],
		// ['公司電話', 	'tel',        		'text',   TRUE, '', 2, 12, ''],
		// ['分機',	   'ext',        		'text',   FALSE, '', 2, 12, ''],
		// ['傳真',	   'fax',        		'text',   TRUE, '', 2, 12, ''],

		// ['變更密碼',	'',		'header',   TRUE, '', 12, 12, ''],
		// ['輸入密碼',	'password',      	'password',   FALSE, '', 4, 12, ''],
		// ['再次輸入密碼',	'password_confirm',      'password',   FALSE, '', 4, 12, ''],

		// ['可管理下屬',	'',		'header',   TRUE, '', 12, 12, ''],
		// ['請選擇業務/下屬帳號',	'subordinate',		'select_multi',		FALSE, '', 6, 12, '', ['id', 'username']],

		// ['可管理產品',	'',		'header',   TRUE, '', 12, 12, ''],
		// ['請選擇產品',	'products',		'select_multi',		FALSE, '', 6, 12, '', ['id', 'name']],
		// ['業助管理產品(業助only)',	'products_assistant',		'select_multi',		FALSE, '', 6, 12, '', ['id', 'name']],

		// ['可管理會員',	'',		'header',   TRUE, '', 12, 12, ''],
		// ['請選擇會員',	'users',		'select_multi',		FALSE, '', 6, 12, '', ['id', 'username']],
	];
	public function index(Request $request, $status = 'normal')
	{
        // print 123;exit;
		$this->data['controller'] = 'recommend_form';
		$this->data['title']      = "推薦資料列表";
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
				['狀態', '', ''],
				['動作', '', ''],
			]
		);
		$this->data['btns'] = [
			['<i class="ri-add-fill"></i>', '新增推薦資料', route('mgr.recommend_form.add'), 'primary']
		];
		$this->data['type']='';
		$this->data['template_item'] = 'mgr/items/template_item';

		$data = array();
		// $data = Member::with('privilege')->with('department')->get();
		// $role = Auth::guard('mgr')->user()->role;

		$this->data['data'] = array();
		foreach ($data as $item) {
			$status = '正常';
			// if (
			// 	($role == 'super' || $role == 'mgr')
			// ) {
			// 	$status = '
			// 		<div class="form-check form-switch">
			// 			<input class="form-check-input switch_toggle" data-id="'.$item->id.'" type="checkbox" role="switch" '.(($item->status=='on')?'checked':'').'>
			// 		</div>
			// 	';
			// }else{
			// 	if($item->status == 'off') $status = '<span class="">關閉</span>';
			// }

			$obj = array();
			// $obj[] = $item->id;
			// if (Auth::guard('mgr')->user()->role == 'super') {
			// 	$obj[] = '<a href="'.route('mgr.simulate', ['id'=>$item->id]).'">'.$item->username.'</a>';	
			// }else{
			// 	$obj[] = $item->username;
			// }			
			// $email = $item->email;
			// if ($item->line_id != '') {
			// 	$email .= '<br><span class="badge rounded-pill bg-success">Line@綁定</span>';
			// }
			// $obj[] = $email;
			// $obj[] = $item->department->title??'';
			// $obj[] = $this->select_array_to_key_array($this->data['select']['role'])[$item->role];
			// $obj[] = $item->privilege->title;
			// $obj[] = $status;
			// $obj[] = $item->created_at;

            $priv_edit = TRUE;
			$priv_del = TRUE;
			// $obj[] = $item->department->title??'';
			// $obj[] = $this->select_array_to_key_array($this->data['select']['role'])[$item->role];
			// $obj[] = $item->privilege->title;
			// $obj[] = $status;
			// $obj[] = $item->created_at;

			$other_btns = array();
			// if ($item->line_id != '') {
			// 	$other_btns[] = array(
			// 		"class"  => "btn-success",
			// 		"action" => "location.href='".route('mgr.member.unlink_line', ['id'=>$item->id])."'",
			// 		"text"   => "解除Line綁定"
			// 	);
			// }

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
            $obj[]  = '啟用';
			// $obj[]  = '';
            
            $priv_edit = TRUE;
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

	public function unlink_line(Request $request, $id){
		if (Member::where('id', $id)->update(['line_id'=>''])) {
			$this->js_output_and_redirect("已解除綁定", 'mgr.member');
		}else{
			$this->js_output_and_back("解除發生錯誤");
		}
	}

	public function add(Request $request){

        
		if ($request->isMethod('post')) {
            // print 123;exit;
			// 
			$formdata = $this->process_post_data($this->param, $request);
			$this->js_output_and_redirect('新增成功', 'mgr.recommend_form');
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

			$res = Member::create($formdata);
			if ($res) {
				$res->subordinate_refresh($request->post('subordinate'));
				$res->manage_product_refresh($request->post('products'));
				$res->manage_user_refresh($request->post('users'));

				
				$this->js_output_and_redirect('新增成功', 'mgr.recommend_form');
			} else {
				$this->js_output_and_back('新增發生錯誤');
			}
			exit();
		}

		$this->data['title'] = "新增推薦資料";
		$this->data['parent'] = "推薦資料列表";
		$this->data['parent_url'] = route('mgr.member');
		$this->data['action'] = route('mgr.member.add');
		$this->data['submit_txt'] = '確認新增';

		$this->data['params'] = $this->generate_param_to_view($this->param);
		

		
		
		
		
		

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
		
		
		// print_r(123);exit;
		// $data = Member::find($id);
		if ($request->isMethod('post')) {
			// $formdata = $this->process_post_data($this->param, $request);

			// if (Member::where('email', $formdata['email'])->where('id','!=',$id)->count() > 0){
			// 	$this->js_output_and_back('Email已存在');
			// 	exit();
			// }
			// if ($formdata['password'] == ''){
			// 	unset($formdata['password']);
			// }else{
			// 	if ($formdata['password'] != $formdata['password_confirm']) $this->js_output_and_back('兩次密碼輸入不相同');
			// 	$formdata['password'] = Hash::make($formdata['password']);
			// }
			// unset($formdata['password_confirm']);

			// $formdata['update_by'] = Auth::guard('mgr')->user()->id;
			// $res = Member::updateOrCreate(['id'=>$id], $formdata);
			// if ($res) {
			// 	$res->subordinate_refresh($request->post('subordinate'));
			// 	$res->manage_product_refresh($request->post('products'));
			// 	$res->manage_user_refresh($request->post('users'));

			// 	Product::where(['assistant'=>$id])->update(['assistant'=>0]);
			// 	if ($request->post('products_assistant')){
			// 		foreach ($request->post('products_assistant') as $product_id) {
			// 			Product::where('id', $product_id)->update(['assistant'=>$id]);
			// 		}
			// 	}

				$this->js_output_and_redirect('編輯成功', 'mgr.recommend_form');
			// } else {
			// 	$this->js_output_and_back('編輯發生錯誤');
			// }
			// exit();
		}
		// $2y$10$4366i0NSO7jboKNbaVLkFubgYg5qnovDQWFks4uPKW0zZ7nIpfDue
		$this->data['title'] = "編輯推薦資料";
		$this->data['parent'] = "推薦資料列表";
		$this->data['parent_url'] = route('mgr.member');
		$this->data['action'] = route('mgr.member', ['id'=>$id]);
		$this->data['submit_txt'] = '確認編輯';

		

		
		
		
		$this->data['params'] = $this->generate_param_to_view($this->param);
		// $this->data['params'] = $this->generate_param_to_view($this->param, $data);
		
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
}