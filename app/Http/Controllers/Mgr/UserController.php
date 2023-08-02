<?php

namespace App\Http\Controllers\Mgr;

use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\User;
use App\Models\Order;
use App\Models\Cart;
use App\Models\UserProduct;
use App\Models\Product;
use App\Models\userEmailVerified;
use Auth;
use Illuminate\Support\Facades\Hash;
use App\Mail\NormalMail;
use Illuminate\Support\Facades\Mail;
class UserController extends Mgr
{
    
	public function __construct()
	{
		parent::__construct();
		$this->data['active'] = 'USER';
		$this->data['sub_active'] = 'USER';
		
		$this->data['select']['manager'] = Member::where('id', '!=', 1)->get()->toArray();

		$this->data['select']['transaction_type'] = array(
			array("id"=>"normal", "text"=>"款到發貨"),
			array("id"=>"month", "text"=>"月結??日 (下月1日起算)"),
			array("id"=>"day", "text"=>"日結??日 (下單後隔日起算)"),
		);
	}

	private $param = [
		['公司名稱',       'company',        'text',   TRUE, '', 4, 12, ''],
        ['統一編號',        'tax_id',        'text',   TRUE, '', 4, 12, ''],
        ['email',        'email',        'text',   FALSE, '', 4, 12, ''],
        ['密碼',        'password',        'password',   FALSE, '', 4, 12, ''],
        ['再次輸入密碼',   'password_confirm',        'password',   FALSE, '', 4, 12, ''],
        ['聯繫人資訊',        '',        'header',   FALSE, '', 12, 12, ''],
        ['聯繫人姓名',        'username',        'text',   FALSE, '', 3, 12, ''],
        ['電話',        'phone',        'text',   FALSE, '', 3, 12, ''],
        ['電話分機',        'ext',        'text',   FALSE, '', 3, 12, ''],

		['交易方式',        '',        		'header',   FALSE, '', 12, 12, ''],
		['交易方式',        'transaction_type',  'select',   FALSE, '若為款到發貨則留空', 3, 12, '', ['id','text']],
		['月結/日結天數',    'transaction_day',        	'number',   FALSE, '', 3, 12, ''],
		['',        '',        		'block',   FALSE, '', 12, 12, ''],
		['備註',    		'transaction_remark',      'textarea',   FALSE, '', 6, 12, ''],
		
		['其它資訊',        '',        'header',   FALSE, '', 12, 12, ''],
        ['信用額度',        'credits',        'number',   TRUE, '', 4, 12, ''],
        ['指派業務',        'manager',        'select',   TRUE, '', 4, 12, '', ['id', 'username']],
	];

	private $review_param = [
		['公司名稱',       'company',        'text',   TRUE, '', 4, 12, ''],
        ['統一編號',        'tax_id',        'text',   TRUE, '', 4, 12, ''],
        ['email',        'email',        'text',   FALSE, '', 4, 12, ''],
        ['聯繫人資訊',        '',        'header',   FALSE, '', 12, 12, ''],
        ['聯繫人姓名',        'username',        'text',   FALSE, '', 3, 12, ''],
        ['電話',        	'phone',        'text',   FALSE, '', 3, 12, ''],
        ['電話分機',        'ext',        'text',   FALSE, '', 3, 12, ''],

		['交易方式',        '',        		'header',   FALSE, '', 12, 12, ''],
		['交易方式',        'transaction_type',  'select',   FALSE, '若為款到發貨則留空', 3, 12, '', ['id','text']],
		['月結/日結天數',    'transaction_day',        	'number',   FALSE, '', 3, 12, ''],
		['',        '',        		'block',   FALSE, '', 12, 12, ''],
		['備註',    		'transaction_remark',      'textarea',   FALSE, '', 6, 12, ''],

		['其它資訊',        '',        'header',   FALSE, '', 12, 12, ''],
        ['信用額度',        'credits',        'number',   TRUE, '', 4, 12, ''],
        ['指派業務',        'manager',        'select',   TRUE, '', 4, 12, '', ['id', 'username']],

	];
	private $th_title = [
		['#', '', ''],
		['姓名', '', ''],
		['綁定業務', '', ''],
		['Email', '', ''],
		['交易方式', '', ''],
		['狀態', '', ''],
		['建立時間', '', ''],
		['動作', '', '']
	];
	public function index(Request $request, $status = 'normal')
	{
		$this->data['controller'] = 'users';
		$this->data['title'] = "會員管理";
		$this->data['parent'] = "";
		$this->data['parent_url'] = "";
		$this->data['th_title'] = $this->th_title_field($this->th_title);
		$this->data['btns'] = [
			['<i class="ri-add-fill"></i>', '新增會員', route('mgr.users.add'), 'primary']
		];

		$this->data['status'] = $status;

		return view('mgr/template_list_ajax', $this->data);
	}

	public function data(Request $request){
		$page   = $request->post('page')??'';
		$search = $request->post('search')??'';
		$status = $request->post('status')??'normal';

		$role = Auth::guard('mgr')->user()->role;
		$html = "";
		// $this->data['template_item'] = 'mgr/items/template_item';

        $data = array();
        if ($status == 'normal') {
            $data = User::where(['status'=>'normal'])
						->where(function($query) use ($search) {
							if ($search != ''){
								$query->orWhere('username', 'like', '%'.$search.'%');
								$query->orWhere('company', 'like', '%'.$search.'%');
								$query->orWhere('tax_id', 'like', '%'.$search.'%');
								$query->orWhere('phone', 'like', '%'.$search.'%');
								$query->orWhere('email', 'like', '%'.$search.'%');
							}
						})
						->with('manage_user')->get();    
        }else{
            $this->data['sub_active'] = 'USER_NEW';
            $data = User::whereIn('status',['inreview'])
						->where(function($query) use ($search) {
							if ($search != ''){
								$query->orWhere('username', 'like', '%'.$search.'%');
								$query->orWhere('company', 'like', '%'.$search.'%');
								$query->orWhere('tax_id', 'like', '%'.$search.'%');
								$query->orWhere('phone', 'like', '%'.$search.'%');
								$query->orWhere('email', 'like', '%'.$search.'%');
							}
						})->get();
        }

        $this->data['data'] = array();
        foreach ($data as $item) {
            $status = '正常';
				
			if ($item->status == 'normal') {
				$status = '<span class="badge rounded-pill bg-primary">正常</span>';
			}else if ($item->status == 'not_verify') {
				$status = '<span class="badge rounded-pill bg-secondary">未驗證</span>';
				$c = userEmailVerified::where(['user_id'=>$item->id,'status'=>'pending'])->first();
				if ($c != null) {
					$status .= '<br>驗證碼： '.$c->code;
				}
			}else if ($item->status == 'inreview') {
				$status = '<span class="badge rounded-pill bg-warning">等待審核</span>';
			}else if ($item->status == 'block') {
				$status = '<span class="badge rounded-pill bg-warning">已關閉</span>';
			}
			

            $obj = array();
            $obj[] = $item->id;
			$username = $item->username;
			$credit = User::credit_check($item->id);
			if (!$credit['status']) {
				$username .= '<br><span class="badge bg-danger">超過信用額度</span>';
			}
			$obj[] = $username;
			if ($item->status == 'normal') {
				$manager = '';
				foreach ($item->manage_user as $m) {
					$manager .= '<span class="badge badge-soft-primary">'.$m['username'].'</span><br>';
				}
				if ($manager != ''){
					$obj[] = $manager;
				}else{
					$obj[] = '<span class="badge bg-danger">未綁定</span>';
				}				
			}else{
				$obj[] = '';
			}
            $obj[] = $item->email;
			if ($item->transaction_remark != '') {
				$obj[] = User::transaction_str($item)."<br><small class='text text-muted'>".$item->transaction_remark.'</small>';
			}else{
				$obj[] = User::transaction_str($item);
			}
            $obj[] = $status;
            $obj[] = $item->created_at;

			$priv_edit = TRUE;
			$priv_del = TRUE;
			$other_btns = array();
			if ($item->status == 'normal') {
				$other_btns[] = array(
					"class"  => "btn-success",
					"action" => "location.href='".route('mgr.users.product', ['user_id'=>$item->id])."'",
					"text"   => "產品價格"
				);				
			}else{
				$priv_edit = FALSE;
				$priv_del = TRUE;
				$other_btns[] = array(
					"class"  => "btn-success",
					"action" => "location.href='".route('mgr.users.review', ['id'=>$item->id])."'",
					"text"   => "前往驗證"
				);
			}

			$html .= view('mgr/items/template_item', [
				'item'      => array(
					"id"         => $item->id,
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

	public function add(Request $request){
		if ($request->isMethod('post')) {
			$formdata = $this->process_post_data($this->param, $request);
			if ($formdata['manager'] == 0) {
				$this->js_output_and_back('請指派業務');
				exit();
			}

			if (User::where('email', $formdata['email'])->count() > 0){
				$this->js_output_and_back('此email已被使用');
				exit();
			}

			if ($formdata['password'] == '') {
				$this->js_output_and_back('密碼不可為空');
				exit();
			}
			if ($formdata['password'] != $formdata['password_confirm']) {
				$this->js_output_and_back('兩次輸入密碼不相同');
				exit();
			}
			$formdata['password'] = Hash::make($formdata['password']);
			unset($formdata['password_confirm']);

			if ($formdata['transaction_day'] == '') $formdata['transaction_day'] = 0;
			$formdata['status'] = 'normal';
			
			$res = User::create($formdata);
			if ($res) {
				$res->manager_refresh([$formdata['manager']]);
				$this->js_output_and_redirect('新增成功', 'mgr.users');
			} else {
				$this->js_output_and_back('編輯發生錯誤');
			}
			exit();
		}

		$this->data['title'] = "新建會員";
		$this->data['parent'] = "會員管理";
		$this->data['parent_url'] = route('mgr.users');
		$this->data['action'] = route('mgr.users.add');
		$this->data['submit_txt'] = '確認新建';

		$this->data['params'] = $this->generate_param_to_view($this->param);

		return view('mgr/template_form', $this->data);
	}

	public function edit(Request $request, $id){
		$data = User::with('manage_user')->find($id);
		if ($request->isMethod('post')) {
			$formdata = $this->process_post_data($this->param, $request);
			if ($formdata['manager'] == 0) {
				$this->js_output_and_back('請指派業務');
				exit();
			}

			if ($formdata['password'] != '') {
				if ($formdata['password'] != $formdata['password_confirm']) {
					$this->js_output_and_back('兩次輸入密碼不相同');
					exit();
				}
				$formdata['password'] = Hash::make($formdata['password']);
			}else{
				unset($formdata['password']);	
			}
			unset($formdata['password_confirm']);
			if ($formdata['transaction_day'] == '') $formdata['transaction_day'] = 0;

			$res = User::updateOrCreate(['id'=>$id], $formdata);
			if ($res) {
				if ($data->transaction_type != $formdata['transaction_type'] || $data->transaction_day != $formdata['transaction_day']) {
					//發信通知客戶
					// $type = "";
					// if ($formdata['transaction_type'] == 'normal') {
					// 	$type = 'mail_cash';
					// }else if ($formdata['transaction_type'] == 'month') {
					// 	$type = 'mail_monthly';
					// }else if ($formdata['transaction_type'] == 'day') {
					// 	$type = 'mail_daily';
					// }
					// $mail_content = Page::where('type', $type)->first();
					$title = "[伊士肯化學]您的付款方式已變更";
					$content = $formdata['transaction_remark'];
					Mail::to($data->email)
                        ->send(new NormalMail($title, $content));
				}
				$res->manager_refresh([$formdata['manager']]);
				$this->js_output_and_redirect('編輯成功', 'mgr.users');
			} else {
				$this->js_output_and_back('編輯發生錯誤');
			}
			exit();
		}

		$this->data['title'] = "編輯 ".$data->title;
		$this->data['parent'] = "會員管理";
		$this->data['parent_url'] = route('mgr.users');
		$this->data['action'] = route('mgr.users.edit', ['id'=>$id]);
		$this->data['submit_txt'] = '確認編輯';

		$manager = array();
		$data = $data->toArray();
		if(count($data['manage_user']) > 0) {
			$manager = $data['manage_user'][0]['id'];
		}else{
			array_unshift($this->data['select']['manager'], array('id'=>'0','username'=>'(尚未綁定)'));
		}
        $data['manager'] = $manager;

		$this->data['params'] = $this->generate_param_to_view($this->param, $data);

		return view('mgr/template_form', $this->data);
	}


	public function review(Request $request, $id){
		$data = User::find($id);
		if ($request->isMethod('post')) {
			$formdata = $this->process_post_data($this->review_param, $request);

			$manager_id = $formdata['manager'];
			unset($formdata['manager']);

			$formdata['status'] = 'normal';

			$res = User::updateOrCreate(['id'=>$id], $formdata);
			if ($res) {
				$res->manager_refresh([$manager_id]);
				$title = "伊士肯化學會員身分驗證成功通知";
				$content = $data->username."您好, 您的會員資格已驗證通過, 請<a href='".route('member')."'>點此</a>登入確認";
				Mail::to($data->email)
					->cc('j2612280@gmail.com')
					->send(new NormalMail($title, $content));

				$this->js_output_and_redirect($res->company.' 審核通過', 'mgr.users');
			} else {
				$this->js_output_and_back('編輯發生錯誤');
			}
			exit();
		}

		$this->data['title'] = "審核 ".$data->company;
		$this->data['parent'] = "會員管理";
		$this->data['parent_url'] = route('mgr.users');
		$this->data['action'] = route('mgr.users.review', ['id'=>$id]);
		$this->data['submit_txt'] = '審核通過';

		$data = $data->toArray();
        
		$this->data['params'] = $this->generate_param_to_view($this->review_param, $data);

		return view('mgr/template_form', $this->data);
	}

    public function del(Request $request){
        $id = $request->post('id');
        
        $obj = User::find($id);
        if ($obj->delete()) {
            $this->output(TRUE, "Delete success");
        }else{
            $this->output(FALSE, "Delete fail");
        }
    }

	/*
		產品
	*/

	private function user_product_del($id){
		$obj = UserProduct::find($id);
		
        if (UserProduct::where(['user_id'=>$obj->user_id, 'product_id'=>$obj->product_id])->delete()) {
            $this->output(TRUE, "Delete success");
        }else{
            $this->output(FALSE, "Delete fail");
        }
	}

	public function product(Request $request, $user_id, $product_id = FALSE){
		if ($user_id == 'del') {
			$this->user_product_del($request->post('id'));
			exit();
		}
		$user = User::find($user_id);
		if ($product_id === FALSE) {
			
			$this->data['controller'] = 'users/product';
			$this->data['title'] = $user->username." 產品價格管理";
			$this->data['parent'] = "";
			$this->data['parent_url'] = "";
			$this->data['th_title'] = $this->th_title_field(
				[
					['#', '', ''],
					['會員', '', ''],
					['產品', '', ''],
					['最後購買', '', ''],
					['價格', '250px', ''],
					['最後更新時間', '', ''],
					['動作', '', '']
				]
			);
			$this->data['btns'] = [
				// ['<i class="ri-add-fill"></i>', '新增標籤', route('mgr.user.add'), 'primary']
			];

			$this->data['template_item'] = 'mgr/items/template_item';

			$data = UserProduct::orderBy('id','desc')->where(['user_id'=>$user_id])->with('user')->with('product')->get()->unique('product_id');
			//->groupBy('product_id')

			$this->data['data'] = array();
			foreach ($data as $item) {
				if (!$item->product) continue;
				$price = '<table class="table table-striped" style="width:200px;">';
				foreach (json_decode($item->price, true) as $r) {
					if ($r['range_start'] == 0) continue;
					$price .= '<tr><td style="width:60%;">'.$r['range_start'].'~'.$r['range_end'].'</td><td style="width:40%; text-align:right;">$'.number_format($r['price']).'</td></tr>';
				}
				$price .= '</table>';
				$obj = array();
				$obj[] = $item->id;
				$obj[] = $item->user->username;
				$obj[] = ($item->product)?$item->product->name:"";
				$obj[] = "價格: $".number_format($item->ex_price)."<br>數量: ".$item->ex_quantity;
				$obj[] = $price;
				$obj[] = $item->created_at;

				$priv_edit = FALSE;
				$priv_del = TRUE;
				$other_btns = array();
				$other_btns[] = array(
					"class"  => "btn-info",
					"action" => "location.href='".route('mgr.users.product', ['user_id'=>$user_id, 'product_id'=>$item->product_id])."'",
					"text"   => "更新價格"
				);
				
				$this->data['data'][] = array(
					"id"         => $item->id,
					"data"       => $obj,
					"other_btns" => $other_btns,
					"priv_edit"  => $priv_edit,
					"priv_del"   => $priv_del
				);
			}

			return view('mgr/template_list', $this->data);
		}else{
			$data = UserProduct::orderBy('id','desc')->where(['user_id'=>$user_id, 'product_id'=>$product_id])->with('user')->with('product')->first();

			$param = array();
			for ($i=1; $i <= 10 ; $i++) { 
				$title_style = 'line-height:36px; float:right;';
				if ($i == 1) $title_style = 'line-height:95px; float:right;';
	
				$param[] = ['',						'',						'plain',   FALSE, '級距'.$i, 1, 12, '', $title_style];
				$param[] = [($i==1)?'級距起':'',		'range_start'.$i,		'number',   FALSE, 'KG', 2, 12, ''];
				$param[] = [($i==1)?'級距迄':'',		'range_end'.$i,			'number',   FALSE, 'KG', 2, 12, ''];
				$param[] = [($i==1)?'會員價格':'',		'price'.$i,				'number',   FALSE, '$', 2, 12, 'price price'.$i];
				$param[] = ['',						'',						'plain',   FALSE, '', 5, 12, ''];
			}

			if ($request->isMethod('post')) {
				$formdata = $this->process_post_data($param, $request);
				for ($i=1; $i <= 10 ; $i++) { 
					$price[] = array(
						'range_start' => intval($formdata['range_start'.$i]),
						'range_end'   => intval($formdata['range_end'.$i]),
						'price'       => intval($formdata['price'.$i]),
					);
				}
				$res = UserProduct::create([
					'user_id'      => $user_id,
					'product_id'   => $product_id,
					'product_name' => $data->product_name,
					'ex_price'     => $data->ex_price,
					'ex_quantity'  => $data->ex_quantity,
					'price'        => json_encode($price)
				]);
				
				if ($res) {
					$this->js_output_and_redirect('更新成功', 'mgr.users.product', ['user_id'=>$user_id]);
				} else {
					$this->js_output_and_back('編輯發生錯誤');
				}
				exit();
			}

			$this->data['title'] = "編輯會員".$user->username." 產品價格【".$data->product->name."】";
			$this->data['parent'] = "會員管理";
			$this->data['parent_url'] = route('mgr.users');
			$this->data['action'] = route('mgr.users.product', ['id'=>$user_id, 'product_id'=>$product_id]);
			$this->data['submit_txt'] = '確認更新';

			$fdata = array();
			$price = json_decode($data->price, TRUE);
			for ($i=0; $i < 10 ; $i++) { 
				$fdata['range_start'.($i+1)] = $price[$i]['range_start']??'';
				$fdata['range_end'.($i+1)] = $price[$i]['range_end']??'';
				$fdata['price'.($i+1)] = $price[$i]['price']??'';
			}

			$this->data['params'] = $this->generate_param_to_view($param, $fdata);

			return view('mgr/template_form', $this->data);
		}
	}

	public function product_price(Request $request){
		$user_id = $request->post('user_id');
		$product_id = $request->post('product_id');
		$data = UserProduct::orderBy('id','desc')->where(['user_id'=>$user_id, 'product_id'=>$product_id])->with('user')->with('product')->first();

		$is_new = false;
		if ($data == null) {
			$data = Product::price($product_id);
			$is_new = true;
		}
		if ($request->has('action') && $request->post('action') == 'save') {
			
			$price = array();
			for ($i=1; $i <= 10 ; $i++) { 
				$price[] = array(
					'range_start' => intval($request->post('range_start'.$i)),
					'range_end'   => intval($request->post('range_end'.$i)),
					'price'       => intval($request->post('price'.$i)),
				);
			}
			$res = false;
			if ($is_new) {
				$product = Product::find($product_id);
				$res = UserProduct::create([
					'user_id'      => $user_id,
					'product_id'   => $product_id,
					'product_name' => $product->name,
					'ex_price'     => 0,
					'ex_quantity'  => 0,
					'price'        => json_encode($price)
				]);
			}else{
				$res = UserProduct::create([
					'user_id'      => $user_id,
					'product_id'   => $product_id,
					'product_name' => $data->product_name,
					'ex_price'     => $data->ex_price,
					'ex_quantity'  => $data->ex_quantity,
					'price'        => json_encode($price)
				]);
			}
			
			
			if ($res) {
				$this->output(true, '儲存成功');
			} else {
				$this->output(false, '儲存發生錯誤');
			}
			exit();
		}


		$fdata = array();
		$price = array();
		if ($is_new) {
			$price = $data['range'];
		}else{
			$price = json_decode($data->price, TRUE);
		}
		
		for ($i=0; $i < 10 ; $i++) { 
			$fdata['range_start'.($i+1)] = $price[$i]['range_start']??'';
			$fdata['range_end'.($i+1)] = $price[$i]['range_end']??'';
			$fdata['price'.($i+1)] = $price[$i]['price_new']??$price[$i]['price'];
		}

		$html = view('mgr/price_form', [
			'data'	=>	$fdata,
			'id'	=>	$product_id
		])->render();

		$this->output(true, "success", ['html'=>$html, 'id'=>$product_id]);
	}
}