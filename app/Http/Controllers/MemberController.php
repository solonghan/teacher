<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use App\Models\userInvoice;
use App\Models\userRecipient;
use App\Models\User;
use App\Models\Order;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\OrderLog;
use Auth;
use App\Models\PageBanner;
use App\Models\UserProduct;
use App\Models\Notification;
use App\Models\Member;

class MemberController extends BaseController{

    public function __construct(){
        parent::__construct();
        $this->middleware('auth');
    }

    public function index(Request $request){
        $this->data['title'] = __('page.member.center');
        $this->data['show_title'] = __('page.member.center');
        $this->data['page_banner'] = PageBanner::data('member', $this->locale);

        $this->data['orders'] = Order::userOrders(Auth::user()->id);

        return view('member', $this->data);
    }

    public function bill(Request $request) {
        $order_no = $request->post("order_no");

        $data = Order::data($order_no);
        if ($data == null) $this->output(FALASE, "查無訂單");
        if ($data->user_id != Auth::user()->id) $this->output(FALASE, "查無訂單");

        $html = view('member-bill', ['data'=>$data])->render();

        $this->output(TRUE, "success", ['html'=>$html]);
    }

    public function bill_confirm(Request $request){
        $order_no = $request->post("order_no");
        $data = Order::where(['order_no'=>$order_no])->with("cart.items.product.manager")->with('user.manage_user')->first();
        
        if (!is_null($data)) {
            $data->update(['status'=>'success', 'payment_status'=>'waiting']);

            OrderLog::log($data->id, "客戶已同意訂單", '', -1);
            
            //建立客戶專屬產品價格表
            UserProduct::item_join_after_order_success($data->id);

            //發通知
            Order::notification($data->id, 'user_order_agree');

            $this->output(TRUE, "success");
        }else{
            $this->output(TRUE, "Change status fail");
        }
    }

    public function bill_exchange(Request $request){
        $order_no = $request->post("order_no");
        $data = Order::where(['order_no'=>$order_no])->with("cart.items.product.manager")->with('user.manage_user')->first();
        
        if (!is_null($data)) {
            $data->update(['is_exchange'=>1]);

            OrderLog::log($data->id, "客戶申請換貨", '', -1);
            
            //發通知
            Order::notification($data->id, 'user_exchange');

            $this->output(TRUE, "success");
        }else{
            $this->output(TRUE, "Change status fail");
        }
    }

    public function bill_return(Request $request){
        $order_no = $request->post("order_no");
        $return_data = $request->post("data");
        
        $data = Order::where(['order_no'=>$order_no])->with("cart.items.product.manager")->with('user.manage_user')->first();
        
        if (!is_null($data)) {
            $data->update(['is_return'=>1]);
            foreach ($return_data as $item) {
                CartItem::where('id', $item)->update(['is_return'=>1]);
            }

            OrderLog::log($data->id, "客戶申請退貨", '', -1);
            
            //發通知
            Order::notification($data->id, 'user_return');

            $this->output(TRUE, "success");
        }else{
            $this->output(TRUE, "Change status fail");
        }
    }

    public function bill_again(Request $request){
        $order_no = $request->post("order_no");

        $order = Order::where(['order_no'=>$order_no])->first();
        if (Cart::copy($order->user_id, $order_no)) {
            $this->output(TRUE, "success");
        }else{
            $this->output(TRUE, "fail");
        }
    }

    public function edit(Request $request){
        $data = array();
        $fields = ['company', 'tax_id', 'username', 'address', 'phone', 'ext', 'email', 'fax'];
        foreach ($fields as $field) {
            if ($request->has($field) && $request->post($field) != null) {
                $data[$field] = $request->post($field);
            }else{
                $data[$field] = '';
            }
        }

        if ($request->has('password') && $request->post('password') != '') {
            if (!$request->has('old_password') || $request->post('old_password') == '') {
                $this->js_output_and_back('請輸入原有密碼');
            }
            if (!Auth::attempt(['email'=>Auth::user()->email, 'password'=>$request->post('old_password')])) {
                $this->js_output_and_back('原有密碼輸入錯誤');
            }
            if ($request->post('password') == $request->post('password_confirm')) {
                $data['password'] = Hash::make($request->post('password'));
            }else{
                $this->js_output_and_back('兩次輸入密碼不相同');    
            }
        }
        if(User::updateOrCreate(['id'=>Auth::user()->id], $data)){
            $this->js_output_and_redirect('更新資料成功', 'member');
        }else{
            $this->js_output_and_back('更新發生錯誤');
        }
    }

    public function add_invoice(Request $request){
        $data = array(
            'user_id'   =>  Auth::user()->id
        );
        $fields = ['company', 'tax_id', 'username', 'address', 'phone', 'ext', 'email'];
        foreach ($fields as $field) {
            if ($request->has($field) && $request->post($field) != null) {
                $data[$field] = $request->post($field);
            }else{
                $data[$field] = '';
            }
        }
        if(userInvoice::updateOrCreate($data, $data)){
            $this->js_output_and_redirect('新增成功', 'member', null, 'invoice');
        }else{
            $this->js_output_and_back('新增發生錯誤');
        }
    }

    public function edit_invoice(Request $request){
        $data = array();
        $fields = ['company', 'tax_id', 'username', 'address', 'phone', 'ext', 'email'];
        foreach ($fields as $field) {
            if ($request->has($field) && $request->post($field) != null) {
                $data[$field] = $request->post($field);
            }else{
                $data[$field] = '';
            }
        }
        if(userInvoice::updateOrCreate(['id'=>$request->post('id')], $data)){
            $this->js_output_and_redirect('編輯成功', 'member', null, 'invoice');
        }else{
            $this->js_output_and_back('編輯發生錯誤');
        }
    }

    public function del_invoice(Request $request, $id){
        $data = userInvoice::find($id);
        if ($data->user_id == Auth::user()->id) {
            $data->delete();
            $this->js_output_and_redirect('發票刪除成功', 'member', null, 'invoice');
        }else{
            $this->js_output_and_back('刪除發生錯誤');
        }
    }

    public function add_recipient(Request $request){
        $data = array(
            'user_id'   =>  Auth::user()->id
        );
        $fields = ['username', 'address', 'phone', 'ext', 'email'];
        foreach ($fields as $field) {
            if ($request->has($field) && $request->post($field) != null) {
                $data[$field] = $request->post($field);
            }else{
                $data[$field] = '';
            }
        }
        if(userRecipient::updateOrCreate($data, $data)){
            $this->js_output_and_redirect('新增成功', 'member', null, 'recipient');
        }else{
            $this->js_output_and_back('新增發生錯誤');
        }
    }

    public function edit_recipient(Request $request){
        $data = array();
        $fields = ['username', 'address', 'phone', 'ext', 'email'];
        foreach ($fields as $field) {
            if ($request->has($field) && $request->post($field) != null) {
                $data[$field] = $request->post($field);
            }else{
                $data[$field] = '';
            }
        }
        if(userRecipient::updateOrCreate(['id'=>$request->post('id')], $data)){
            $this->js_output_and_redirect('編輯成功', 'member', null, 'recipient');
        }else{
            $this->js_output_and_back('編輯發生錯誤');
        }
    }

    public function del_recipient(Request $request, $id){
        $data = userRecipient::find($id);
        
        if ($data->user_id == Auth::user()->id) {
            $data->delete();
            $this->js_output_and_redirect('收貨資料刪除成功', 'member', null, 'recipient');
        }else{
            $this->js_output_and_back('刪除發生錯誤');
        }
    }

    public function logout(){
        Session::flush();
        Auth::logout();

        return redirect()->route('home');
    }
}
