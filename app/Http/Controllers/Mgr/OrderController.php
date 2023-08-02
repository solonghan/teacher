<?php

namespace App\Http\Controllers\Mgr;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\CartItem;
use App\Models\OrderLog;
use App\Models\MemberDepartment;
use App\Models\Notification;
use App\Models\User;
use App\Models\Cart;
use App\Models\Member;
use App\Models\Product;
use App\Models\ProductLog;
use Auth;
use App\Exports\OrderExport;
use Excel;
use App\Mail\NormalMail;
use App\Mail\UserMail;
use App\Mail\NotificationMail;
use Illuminate\Support\Facades\Mail;

class OrderController extends Mgr
{
    
	public function __construct()
	{
		parent::__construct();
		$this->data['active'] = 'ORDER';
		$this->data['sub_active'] = 'ORDER';
	}

	private $param = [
		['公司名稱',       'company',        'text',   TRUE, '', 4, 12, ''],
        ['統一編號',        'tax_id',        'text',   TRUE, '', 4, 12, ''],
        ['email',        'email',        'text',   FALSE, '', 4, 12, ''],
        ['聯繫人資訊',        '',        'header',   FALSE, '', 12, 12, ''],
        ['聯繫人姓名',        'username',        'text',   FALSE, '', 3, 12, ''],
        ['電話',        'phone',        'text',   FALSE, '', 3, 12, ''],
        ['電話分機',        'ext',        'text',   FALSE, '', 3, 12, ''],

		['其它資訊',        '',        'header',   FALSE, '', 12, 12, ''],
        ['信用額度',        'credits',        'number',   TRUE, '', 4, 12, ''],
        ['指派業務',        'manager',        'select',   TRUE, '', 4, 12, '', ['id', 'username']],
	];
	private $th_title = [
		['#', '', ''],
		['訂單編號<small>/銷貨單號</small>', '155px', ''],
		['下訂會員', '', ''],
		['產品', '', ''],
		['總金額(未稅)', '100px', ''],
		['訂單狀態', '125px', ''],
		['付款狀態', '125px', ''],
		['物流狀態', '125px', ''],
		['下單時間', '130px', ''],
		['動作', '120px', ''],
	];
	private $can_order_fields = [1,5,6,7,8];
	private $order_column = ["", "order_no", "", "", "", "status", "payment_status", "shipping_status", "created_at"];

	public function index(Request $request)
	{
		//test noti
		// Order::notification(11, 'create_order');
		Notification::cron_send_mail();

		$this->data['controller'] = 'order';
		$this->data['title'] = "訂單管理";
		$this->data['parent'] = "";
		$this->data['parent_url'] = "";
		$this->data['can_order_fields'] = $this->can_order_fields;
		$this->data['default_order_column'] = 1;
		$this->data['order_direction'] = 'DESC';

		$this->data['th_title'] = $this->th_title_field($this->th_title);

		$this->data['btns'] = [
			// ['<i class="ri-add-fill"></i>', '新增標籤', route('mgr.user.add'), 'primary']
		];

		$role = $this->data['role'] = Auth::guard('mgr')->user()->role;

		$manage_user = Auth::guard('mgr')->user()->manage_user_array();
		$manage_product = Auth::guard('mgr')->user()->manage_product_array();

		$data = array();
		if (in_array($role, ['super', 'mgr'])) {
			$data = Order::orderBy('id','desc')->with('cart.items')->with('user.manage_user')->get();
			foreach ($data as $item) {
				$item->iam_manager = false;
				foreach ($item->user->manage_user as $mu) {
					if ($mu->department_id == Auth::guard('mgr')->user()->department_id) $item->iam_manager = true;
				}
			}
		}else if ($role == 'saler') {
			$data = Order::orderBy('id', 'desc')->cursor()->filter(function ($order) use ($manage_user, $manage_product) {
				$order = $order->load('user')->load('cart.items');
				if (in_array($order->user->id, $manage_user)) {
					$order->user->iam = true;
				}else{
					$order->user->iam = false;
				}
				$iam_manage_product = false;
				foreach ($order->cart->items as $item) {
					if (in_array($item->product_id, $manage_product)) {
						$iam_manage_product = true;
						$item->iam = true;
					}else{
						$item->iam = false;
					}

					$item->logs = OrderLog::orderBy('id','desc')->where(['type'=>'product','order_id'=>$order->id, 'product_id'=>$item->product_id])->get();
				}
				if (!$iam_manage_product && !in_array($order->user->id, $manage_user)) return false;
				
				return true;
			});
			// dd($data->toArray());
		}else if (in_array($role, ['accounting', 'depot', 'assistant'])) {
			$data = Order::orderBy('id','desc')->whereIn('status', ['success', 'complete'])->with('cart.items')->with('user')->get();
		}

		$this->data['data'] = array();
		foreach ($data as $item) {
			$this->data['data'][] = Order::status_str_mapping($item);
		}
		
		// return view('mgr/order_list', $this->data);
		$this->data['custom_js_on_ready'] = view('mgr/order_js', $this->data)->render();
		return view('mgr/template_list_ajax', $this->data);
	}

	public function data(Request $request, $is_api = true){
		$page      = $request->post('page')??'';
		$search    = $request->post('search')??'';
		$order     = $request->post('order')??0;
		$direction = $request->post('direction')??'DESC';
		
		$role = $this->data['role'] = Auth::guard('mgr')->user()->role;
		$member_id = Auth::guard('mgr')->user()->id;

		$manage_user = Auth::guard('mgr')->user()->manage_user_array();
		$manage_product = Auth::guard('mgr')->user()->manage_product_array();
		$department_id = Auth::guard('mgr')->user()->department_id;

		$order_by = 'id';
		if (array_key_exists($order, $this->order_column) && $this->order_column[$order] != '') {
			$order_by = $this->order_column[$order];
		}

		$data = Order::list($page, $role, $manage_user, $manage_product, $department_id, $search, $order_by, $direction, $member_id);

		$html = "";
		foreach ($data as $item) {
			$item = Order::status_str_mapping($item);
			$priv_view = false;
			if (in_array($role, ['super', 'mgr', 'director', 'depot', 'accounting', 'assistant']) || ($role == 'saler' && $item->user->iam)){
				$priv_view = true;
			}
			$item['priv_view'] = $priv_view;
			
			$credit = User::credit_check($item->user->id);

			$html .= view('mgr/order_item', [
				'credit'      => $credit['status'],
				'role'        => $role,
				'item'        => $item,
				'priv_view'   => $priv_view,
				'th_title'    => $this->th_title_field($this->th_title),
				'saleslip_no' => $item->saleslip_no
			])->render();
		}

		if (!$is_api) return $data;

		$this->output(TRUE, 'success', array(
			'html'	=>	$html
		));
	}

	public function detail($order_no, Request $request){
		$role = Auth::guard('mgr')->user()->role;
        $member_id = Auth::guard('mgr')->user()->id;

		$data = $this->data['data'] = Order::data($order_no, $member_id, $role);
		
		$this->data['credit'] = User::credit_check($data->user->id);
		$this->data['logs'] = OrderLog::data($data->id);
		$this->data['controller'] = 'order';
		$this->data['title'] = "訂單 #".$data->order_no;
		$this->data['parent'] = "訂單管理";
		$this->data['parent_url'] = 'mgr.order';

		$this->data['editable'] = false;
		$role = Auth::guard('mgr')->user()->role;
		
		//有些身分不能進訂單詳情
		if (
			($role == 'saler' && $data->user->manage_user[0]->id != Auth::guard('mgr')->user()->id)
			) {
			return redirect()->route('mgr.order');
		}

		if ($data->status == 'new' || $data->status == 'reject')
			if (
				($role == 'saler' && $data->user->manage_user[0]->id == Auth::guard('mgr')->user()->id) ||
				($role == 'mgr' && $data->department_id = Auth::guard('mgr')->user()->department_id) ||
				$role == 'super'
			) $this->data['editable'] = true;

		$this->data['role'] = $role;

		return view('mgr/order_detail', $this->data);
	}

	public function quantity_change(Request $request){
		$user_id      = $request->post('user_id');
		$product_id = $request->post('product_id');
		$quantity     = $request->post('quantity');
		$order_id     = $request->post('order_id');

		$order = Order::find($order_id);
		
		$cart = Cart::updateItem(FALSE, $user_id, $order->cart_id, [
			'quantity'   => $quantity,
			'product_id' => $product_id,
			'spec'       => '',
		]);

		$order->price = $cart->price;
		$order->save();

		$this->output(true, 'success', [
			'cart'	=>	$cart
		]);
	}

	/*
	public function notification($id, $action, $content = ''){
		$data = Order::where('id', $id)->with("cart.items.product.manager")->with('user.manage_user')->first();

		$manage_member = $data->user->manage_user[0];

		$mgr = Member::where('department_id', $manage_member->department_id)->get();
		
		if ($action == 'commit'){
			//提交審核
			//	最高權限
			Notification::add(0, 'commit_order', $id, "訂單#".$data->order_no."已提交審核", "訂單#".$data->order_no."已提交審核", 1, 0);
			//	會員業務
			Notification::add($manage_member->id, 'commit_order', $id, "訂單#".$data->order_no."已提交審核", "訂單#".$data->order_no."已提交審核", 1, 0);

			//	產品業務
			$saler = array();
			foreach ($data->cart->items as $p) {
				foreach ($p->product->manager as $member) {
					if (array_key_exists($member->id, $saler)) $saler[$member->id] = array();
					$saler[$member->id][] = $p->name." $".number_format($p->price)." × ".number_format($p->quantity).$p->weight."\n小計：$".number_format($p->price*$p->quantity)."\n";
				}
			}
			foreach ($saler as $member_id => $item) {
				$content = "";
				foreach ($item as $str) {
					$content .= $str."\n\n";
				}
				Notification::add($member_id, 'commit_order', $id, "訂單#".$data->order_no."有產品等待審核", $content);
			}
		}else if ($action == 'cancel'){
			//取消訂單
			Notification::add(0, 'cancel_order', $id, "訂單#".$data->order_no."已取消", "訂單#".$data->order_no."已取消", 1, 0);
		}else if ($action == 'del'){
			//刪除訂單
			Notification::add(0, 'del_order', $id, "訂單#".$data->order_no."已刪除", "訂單#".$data->order_no."已刪除", 1, 0);
		}else if ($action == 'product_pass'){
			//審核產品通過
			//	最高權限
			Notification::add(0, 'product_pass', $id, "訂單#".$data->order_no." 產品審核通過通知", $content, 1, 0);
			//	會員業務
			Notification::add($manage_member->id, 'product_pass', $id, "訂單#".$data->order_no." 產品審核通過通知", $content, 1, 0);
		}else if ($action == 'product_not_enough'){
			//審核產品 數量不夠
			//	最高權限
			Notification::add(0, 'product_pass', $id, "訂單#".$data->order_no." 產品庫存不足通知", $content);
			//	會員業務
			Notification::add($manage_member->id, 'product_pass', $id, "訂單#".$data->order_no." 產品庫存不足通知", $content);
		}else if ($action == 'product_invalid'){
			//審核產品不通過
			//	最高權限
			Notification::add(0, 'product_invalid', $id, "訂單#".$data->order_no." 產品審核不通過", $content);
			//	會員業務
			Notification::add($manage_member->id, 'product_invalid', $id, "訂單#".$data->order_no." 產品審核不通過", $content);
		}else if ($action == 'inreview'){
			//所有產品審核通過
			//	最高權限
			Notification::add(0, 'product_pass', $id, "訂單#".$data->order_no." 主管審核通知", $content, 1, 0);
			//	會員業務
			Notification::add($manage_member->id, 'product_pass', $id, "訂單#".$data->order_no." 主管審核通知", $content, 1, 0);

			//	會員業務部門主管
			foreach ($mgr as $member) {
				Notification::add($member->id, 'product_pass', $id, "訂單#".$data->order_no." 主管審核通知", $content);
			}
		}else if ($action == 'confirmed'){
			//主管審核通過
			//	最高權限
			Notification::add(0, 'product_pass', $id, "訂單#".$data->order_no." 審核通過", $content, 1, 0);
			//	會員業務
			Notification::add($manage_member->id, 'product_pass', $id, "訂單#".$data->order_no." 審核通過", $content, 1, 0);

			//	會員業務部門主管
			foreach ($mgr as $member) {
				Notification::add($member->id, 'product_pass', $id, "訂單#".$data->order_no." 審核通過", $content);
			}

			//寄信給客戶
			$title = "訂單#".$data->order_no." 等待您確認";
			$content = "訂單#".$data->order_no." 等待您確認, 請<a href='".route('member')."#order'>點此</a>登入確認";
            $res = Mail::to($data->user->email)
                        ->bcc('j2612280@gmail.com')
                        ->send(new NormalMail($title, $content));
		}else if ($action == 'reject'){
			//訂單拒絕
			//	最高權限
			Notification::add(0, 'reject', $id, "訂單#".$data->order_no." 主管審核退回", $content);
			//	會員業務
			Notification::add($manage_member->id, 'reject', $id, "訂單#".$data->order_no." 主管審核退回", $content);
		}else if ($action == 'pay'){
			//已付款
			//	最高權限
			Notification::add(0, 'pay', $id, "訂單#".$data->order_no." 已付款", $content);
			//	會員業務
			Notification::add($manage_member->id, 'pay', $id, "訂單#".$data->order_no." 已付款", $content);

			//業助
			$assistant = Member::where('role', 'assistant')->get();
			foreach ($assistant as $member) {
				Notification::add($member->id, 'pay', $id, "訂單#".$data->order_no." 已付款", $content);
			}

			//倉儲
			$assistant = Member::where('role', 'depot')->get();
			foreach ($assistant as $member) {
				Notification::add($member->id, 'pay', $id, "訂單#".$data->order_no." 已付款", $content);
			}
		}else if ($action == 'shipping'){
			//已出貨
			//	最高權限
			Notification::add(0, 'shipping', $id, "訂單#".$data->order_no." 已出貨", $content);
			//	會員業務
			Notification::add($manage_member->id, 'shipping', $id, "訂單#".$data->order_no." 已出貨", $content);

			//寄信給客戶
			$title = "訂單#".$data->order_no." 已出貨";
			$content = "訂單#".$data->order_no." 已出貨";
            $res = Mail::to($data->user->email)
                        ->bcc('j2612280@gmail.com')
                        ->send(new NormalMail($title, $content));
		}else if ($action == 'shipping_success'){
			//已送達
			//	最高權限
			Notification::add(0, 'shipping', $id, "訂單#".$data->order_no." 已送達", $content);
			//	會員業務
			Notification::add($manage_member->id, 'shipping', $id, "訂單#".$data->order_no." 已送達", $content);
			
			//寄信給客戶
			$title = "訂單#".$data->order_no." 已送達";
			$content = "訂單#".$data->order_no." 已送達";
            $res = Mail::to($data->user->email)
                        ->bcc('j2612280@gmail.com')
                        ->send(new NormalMail($title, $content));
		}
	}
	*/

    public function action(Request $request){
        $id         = $request->post('id');
        $product_id = $request->post('product_id')??0;
        $action     = $request->post('action');
        $form       = ($request->has('form') && $request->post('form') == 'form')?'form':'ajax';
		
		if ($action == 'check_return') {
			//同意退貨 TODO:
			$data = Order::where('id', $id)->with("cart.items")->first();
			$title = Auth::guard('mgr')->user()->username.'同意退貨申請';
			$remark = $request->post('remark')??'';
			if ($remark != '') $remark .= "\n";
			foreach (CartItem::where(['cart_id'=>$data->cart->id])->get() as $item) {
				if ($item->is_return == 1) {
					$remark .= "【".$item->name."】已退貨\n";
					CartItem::where("id", $item->id)->update(['is_return'=>2]);
				}
			}
			$total_price = Cart::refresh_total_price($data->cart->id);

			OrderLog::create([
				'type'       => 'order',
				'order_id'   => $id,
				'product_id' => 0,
				'member_id'  => Auth::guard('mgr')->user()->id,
				'price'      => 0,
				'title'      => $title,
				'remark'     => $remark,
				'status'     => 'success'
			]);
			$udata = array(
				'is_return' => 2,
				'price'     => $total_price
			);
			$res = Order::find($id)->update($udata);
			
			$this->js_output_and_redirect("變更成功", 'mgr.order.detail', ['id'=>$data->order_no]);
		}else if ($action == 'check_exchange') {
			//同意換貨
			$data = Order::where('id', $id)->with("cart.items")->first();
			$title = Auth::guard('mgr')->user()->username.'同意換貨申請';
			$remark = $request->post('remark')??'';
			
			OrderLog::create([
				'type'       => 'order',
				'order_id'   => $id,
				'product_id' => 0,
				'member_id'  => Auth::guard('mgr')->user()->id,
				'price'      => 0,
				'title'      => $title,
				'remark'     => $remark,
				'status'     => 'success'
			]);
			$udata = [
				'is_exchange' => 2
			];
			$res = Order::find($id)->update($udata);
			
			$this->js_output_and_redirect("變更成功", 'mgr.order.detail', ['id'=>$data->order_no]);
		}else if ($action == 'saleslip') {
			$data = Order::where('id', $id)->with("cart.items.product")->first();

			$saleslip_no = $request->post('saleslip_no');
			$title = "業助".Auth::guard('mgr')->user()->username." 變更銷貨單號: ".$saleslip_no;
			OrderLog::create([
				'type'       => 'order',
				'order_id'   => $id,
				'product_id' => 0,
				'member_id'  => Auth::guard('mgr')->user()->id,
				'price'      => 0,
				'title'      => $title,
				'remark'     => '',
				'status'     => 'normal'
			]);
			$udata = array(
				'saleslip_no'	=>	$saleslip_no
			);
			foreach ($data->cart->items as $p) {
				if ($p->product->assistant == Auth::guard('mgr')->user()->id) {
					CartItem::find($p->id)->update($udata);
				}
			}
			if ($form == 'form') {
				$this->js_output_and_redirect("變更成功", 'mgr.order.detail', ['id'=>$data->order_no]);
			}else{
				$this->output(TRUE, "變更成功");
			}
		}else if ($action == 'update') {
			$data = Order::where('id', $id)->with("cart.items")->first();
			$title = '修改訂單內容';
			$remark = '';
			foreach (CartItem::where(['cart_id'=>$data->cart->id])->get() as $item) {
				if ($request->has("item_".$item->id."_price")) {
					$price = $request->post("item_".$item->id."_price");
					$quantity = $request->post("item_".$item->id."_quantity");
					if ($item->price != $price || $item->quantity != $quantity) {
						$remark .= "【".$item->name."】原單價: $".number_format($item->price)." x ".number_format($item->quantity)." /".$item->weight."\n";	
						$remark .= "【".$item->name."】變更為: $".number_format($price)." x ".number_format($quantity)." /".$item->weight."\n\n";
						$item->update([
							"price"    => $price,
							"quantity" => $quantity
						]);
					}
				}
			}
			$total_price = Cart::refresh_total_price($data->cart->id);
			OrderLog::create([
				'type'       => 'order',
				'order_id'   => $id,
				'product_id' => 0,
				'member_id'  => Auth::guard('mgr')->user()->id,
				'price'      => 0,
				'title'      => $title,
				'remark'     => $remark,
				'status'     => 'normal'
			]);
			$udata = array(
				'price'	=>	$total_price
			);
			if ($request->has('commit') && $request->post('commit') == "1") {
				$udata['status'] = 'pending';

				CartItem::where('cart_id',$data->cart->id)->where('status', '!=', 'confirmed')->update(['status'=>"pending"]);
				OrderLog::log($id, '提交審核');
				Order::notification($id, 'commit');
			}
			$res = Order::find($id)->update($udata);
			
			$this->js_output_and_redirect("變更成功", 'mgr.order.detail', ['id'=>$data->order_no]);
		}else if ($action == 'pending') {
			$res = Order::commit_order($id, Auth::guard('mgr')->user()->id);
			$this->output($res['status'], $res['msg']);
        }else if ($action == 'cancel') {
            $res = Order::find($id)->update(['status'=>'cancel', 'payment_status'=>'cancel', 'shipping_status'=>'cancel']);
			if ($res) {
				OrderLog::create([
					'type'       => 'order',
					'order_id'   => $id,
					'product_id' => 0,
					'member_id'  => Auth::guard('mgr')->user()->id,
					'price'      => 0,
					'title'      => '訂單取消',
					'remark'     => $request->post('remark')??'',
					'status'     => 'invalid'
				]);
				Order::notification($id, 'cancel');
                $this->output(TRUE, '已取消訂單');
            }
        }else if ($action == 'del') {
            $res = Order::find($id);

			Order::notification($id, 'del');
			if ($res->delete()) {
				OrderLog::create([
					'type'       => 'order',
					'order_id'   => $id,
					'product_id' => 0,
					'member_id'  => Auth::guard('mgr')->user()->id,
					'price'      => 0,
					'title'      => '刪除訂單',
					'remark'     => $request->post('remark')??'',
					'status'     => 'invalid'
				]);
                $this->output(TRUE, '已刪除訂單');
            }
        }else if ($action == 'product_pass') {
			$res = Order::product_pass($id, $product_id, Auth::guard('mgr')->user()->id, ($request->post('price')??''), ($request->post('remark')??''));
			if ($res) {
				$this->js_output_and_redirect('審核成功', 'mgr.order');
			}else{
				$this->js_output_and_back('審核發生錯誤');
			}
		}else if ($action == 'product_invalid') {
			$data = Order::where('id', $id)->with("cart.items")->first();
			$cart_item = CartItem::where(['cart_id'=>$data->cart->id, 'product_id'=>$product_id])->first();
			if (is_null($request->post('price')) || $request->post('price') == 0){
				$this->js_output_and_back('建議金額不可為空');
				exit();
			}
			$cart_item->status = 'bargain';
			$cart_item->price_remark = 'bargain';
			$cart_item->bargain_price = $request->post('price');
			$cart_item->save();
			$res = OrderLog::create([
				'order_id'   => $id,
				'product_id' => $product_id,
				'member_id'  => Auth::guard('mgr')->user()->id,
				'price'      => $request->post('price'),
				'title'      => '【'.$cart_item->name.'】退回議價 $'.number_format($request->post('price')),
				'remark'     => $request->post('remark')??'',
				'status'     => 'invalid'
			]);
			if ($res) {
				$return_order = true;
				foreach (CartItem::where(['cart_id'=>$data->cart->id])->get() as $c) {
					if ($c->status == 'pending') $return_order = false;
				}
				if($return_order) Order::find($id)->update(['status'=>'reject']);

				Order::notification($id, 'product_invalid', 
					'【'.$cart_item->name.'】退回議價 $'.number_format($request->post('price')).
					"\n備註:".($request->post('remark')??'')
				);
				$this->js_output_and_redirect('審核退回', 'mgr.order');
			}
		}else if ($action == 'product_not_enough') {
			$data = Order::where('id', $id)->with("cart.items")->first();
			$cart_item = CartItem::where(['cart_id'=>$data->cart->id, 'product_id'=>$product_id])->first();
			
			$cart_item->status = 'not_enough';
			$cart_item->price_remark = 'bargain';
			$cart_item->quantity = 0;
			$cart_item->price = 0;

			$res = OrderLog::create([
				'type'       => 'product',
				'order_id'   => $id,
				'product_id' => $product_id,
				'member_id'  => Auth::guard('mgr')->user()->id,
				'price'      => $request->post('price')??0,
				'title'      => '【'.$cart_item->name.'】庫存不足',
				'remark'     => $request->post('remark')??'',
				'status'     => 'not_enough'
			]);
			$cart_item->save();
			if ($res) {
				Cart::refresh_total_price($data->cart->id);

				$return_order = true;
				foreach (CartItem::where(['cart_id'=>$data->cart->id])->get() as $c) {
					if ($c->status == 'pending') $return_order = false;
				}
				if($return_order) Order::find($id)->update(['status'=>'reject']);
				
				Order::notification($id, 'product_not_enough', 
					'【'.$cart_item->name.'】庫存不足'."\n備註:".($request->post('remark')??'')
				);
				$this->js_output_and_redirect('已標記為庫存不足', 'mgr.order');
			}
		}else if ($action == 'order_pass') {
			$res = Order::order_pass($id, Auth::guard('mgr')->user()->id, ($request->post('remark')??''));
			if ($res) {
				$this->js_output_and_redirect('審核成功', 'mgr.order');
			}else{
				$this->js_output_and_back('審核發生問題');
			}
		}else if ($action == 'order_director_pass') {
			$res = Order::order_director_pass($id, Auth::guard('mgr')->user()->id, ($request->post('remark')??''));
			if ($res) {
				$this->js_output_and_redirect('審核成功', 'mgr.order');
			}else{
				$this->js_output_and_back('審核發生問題');
			}
		}else if ($action == 'order_director_invalid') {
			$data = Order::where('id', $id)->with("cart.items")->first();
			$res = OrderLog::create([
				'type'       => 'order',
				'order_id'   => $id,
				'product_id' => $product_id,
				'member_id'  => Auth::guard('mgr')->user()->id,
				'price'      => 0,
				'title'      => '大主管/總監審核不通過，退回至客戶業務',
				'remark'     => $request->post('remark')??'',
				'status'     => 'invalid'
			]);
			if ($res) {
				$res = Order::find($id)->update(['status'=>'reject', 'price_remark'=>'bargain']);
				Cart::where(['id'=>$data->cart_id])->update(['price_remark'=>'bargain']);
				CartItem::where(['cart_id'=>$data->cart->id])->update(['status'=>"pending"]);

				Order::notification($id, 'reject', "大主管/總監審核不通過，退回至客戶業務\n備註：".($request->post('remark')??''));

				$this->js_output_and_redirect('審核退回', 'mgr.order');
			}
		}else if ($action == 'order_invalid') {
			$data = Order::where('id', $id)->with("cart.items")->first();
			$res = OrderLog::create([
				'type'       => 'order',
				'order_id'   => $id,
				'product_id' => $product_id,
				'member_id'  => Auth::guard('mgr')->user()->id,
				'price'      => 0,
				'title'      => '訂單主管審核不通過，退回至客戶業務',
				'remark'     => $request->post('remark')??'',
				'status'     => 'invalid'
			]);
			if ($res) {
				$res = Order::find($id)->update(['status'=>'reject', 'price_remark'=>'bargain']);
				Cart::where(['id'=>$data->cart_id])->update(['price_remark'=>'bargain']);
				CartItem::where(['cart_id'=>$data->cart->id])->update(['status'=>"pending"]);

				Order::notification($id, 'reject', "訂單主管審核不通過，退回至客戶業務\n備註：".($request->post('remark')??''));

				$this->js_output_and_redirect('審核退回', 'mgr.order');
			}
		}else if ($action == 'payment') {
			$param = $request->post("param")??'';
			$payment_status = Order::payment_status_str($param);
			$status = 'normal';
			if ($param == 'cheque_received' || $param == 'atm_received' || $param == 'cash_received') {
				//cheque_cashed支票已兌現已拿掉
				$status = 'pass';
			}
			$res = OrderLog::create([
				'type'       => 'payment',
				'order_id'   => $id,
				'product_id' => 0,
				'member_id'  => Auth::guard('mgr')->user()->id,
				'price'      => 0,
				'title'      => '付款狀態切換為「'.$payment_status.'」',
				'remark'     => $request->post('remark')??'',
				'status'     => $status
			]);
			if ($res) {
				if ($payment_status != '') $res = Order::find($id)->update(['payment_status'=>$param]);

				if ($param == 'unpaid' || $param == 'cash_received') {
					//通知倉庫
					Order::notification($id, 'shipping_waiting');

					Order::find($id)->update(['shipping_status'=>'shipping_waiting']);
				}

				if ($status == 'pass') {
					Order::notification($id, 'pay');
				}

				$this->output(TRUE, 'success', array(
                    'action'    =>  'reload'
                ));
			}
		}else if ($action == 'shipping') {
			$data = Order::where('id', $id)->with("cart.items")->first();

			$param = $request->post("param")??'';
			$shipping_status = Order::shipping_status_str($param);
			$status = 'normal';
			if ($param == 'complete') {
				$status = 'pass';
			}
			$res = OrderLog::create([
				'type'       => 'shipping',
				'order_id'   => $id,
				'product_id' => 0,
				'member_id'  => Auth::guard('mgr')->user()->id,
				'price'      => 0,
				'title'      => '出貨狀態切換為「'.$shipping_status.'」',
				'remark'     => $request->post('remark')??'',
				'status'     => $status
			]);
			if ($res) {
				$udata = ['shipping_status'=>$param];
				$notification_type = "";
				if ($param == 'complete') {
					$udata = ['shipping_status'=>$param, 'status'=>'complete'];
					$notification_type = 'shipping_success';
				}
				if ($param == 'shipping') {
					$notification_type = 'shipping';

					//出貨時扣庫存
					foreach ($data->cart->items as $item) {
						if ($item->is_stock_calc == 0) {
							CartItem::where('id', $item->id)->update(['is_stock_calc'=>1]);
							
							$product = Product::where('id', $item->product_id)->first();
							$quota = $product->quota - $item->quantity;
							Product::where('id', $item->product_id)->update(['quota'=>$quota]);


							ProductLog::create([
								'order_id'       => $data->id,
								'product_id'     => $item->product_id,
								'member_id'      => Auth::guard('mgr')->user()->id,
								'original_quota' => $product->quota,
								'new_quota'      => $quota
							]);
						}
					}
					
					if ($data->payment_status == 'cheque_received' || $data->payment_status == 'atm_received' || $data->payment_status == 'cash_received') {
						//已收款，訂單即為 complete
						$udata['status'] = 'complete';
						
						$notification_type = 'complete';
					}
			
				}
				if ($shipping_status != '') $res = Order::find($id)->update($udata);

				if($notification_type!='') Order::notification($id, $notification_type);
				$this->output(TRUE, 'success', array(
                    'action'    =>  'reload'
                ));
			}
		}

		if ($form == 'ajax') {
			$this->output(FALSE, '操作發生錯誤');
		}else{
			$this->js_output_and_back('操作發生錯誤');
		}		
    }

    public function del(Request $request){
        $id = $request->post('id');
        
        $obj = Order::find($id);
        if ($obj->delete()) {
            $this->output(TRUE, "Delete success");
        }else{
            $this->output(FALSE, "Delete fail");
        }
    }

	public function export(Request $request){
		
		$data = array();

		foreach ($this->data($request, false) as $item) {
			$products = '';

			foreach ($item->cart->items as $p) {
				if ($products != '') $products .= "\n";
				$products .= $p->name." × ".$p->quantity;
			}

			$data[] = array(
				'訂單編號'    => ' '.strval($item->order_no),
				'下訂會員'    => $item->user->username,
				'產品'       => $products,
				'總金額(未稅)' => number_format($item->price),
				'訂單狀態'    => $item->status_show(),
				'付款狀態'    => $item->payment_status_show(),
				'物流狀態'    => $item->shipping_status_show(),
				'下單時間'    => $item->created_at
			);
		}
		print_r($data);exit;
		$collect = collect([
			'data'=>$data
		]);
		$filename = "訂單報表_".date('mdHis');
		return Excel::download(new OrderExport($collect, $filename), $filename.'.xlsx');
	}
}