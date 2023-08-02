<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use App\Models\userInvoice;
use App\Models\userRecipient;
use Auth;
use App\Models\PageBanner;
use App\Models\Notification;
use App\Models\Member;

class CartController extends BaseController
{   
    public function __construct(){
        parent::__construct();
        $this->middleware('auth');
        $this->data['page_banner'] = PageBanner::data('cart', $this->locale);
    }

    public function index(Request $request){
        $this->data['title'] = __('page.cart.title');
        $this->data['show_title'] = __('page.cart.title');
        // $this->data['cart'] = Cart::cart();
        // dd($this->data['cart']->toArray());
        return view('cart', $this->data);
    }

    public function step2(Request $request){
        $this->data['title'] = __('page.cart.title');
        $this->data['show_title'] = __('page.cart.title');
        $this->data['cart'] = Cart::cart();

        return view('cart-step2', $this->data);
    }

    public function confirm(Request $request){
        $recipient = array();
        $fields = ['recipient_username','recipient_address','recipient_phone','recipient_ext','recipient_email','recipient_date'];
        foreach ($fields as $field) {
            if ($request->has($field) && $request->post($field) != null) {
                $recipient[$field] = $request->post($field);
            }else{
                $recipient[$field] = '';
            }
        }
        $sync_recipient = $request->post('sync_recipient');
        if ($sync_recipient == 'on') {
            $rdata = array(
                "user_id"   =>  Auth::user()->id
            );
            foreach ($recipient as $key => $item) {
                $key = str_replace('recipient_', '', $key);
                $rdata[$key] = $item;
            }
            userRecipient::create($rdata);
        }

        $invoice = array();
        $fields = ['invoice_company','invoice_tax_id','invoice_username','invoice_address','invoice_phone','invoice_ext','invoice_email'];
        foreach ($fields as $field) {
            if ($request->has($field) && $request->post($field) != null) {
                $invoice[$field] = $request->post($field);
            }else{
                $invoice[$field] = '';
            }
        }
        $sync_invoice = $request->post('sync_invoice');
        if ($sync_invoice == 'on') {
            $idata = array(
                "user_id"   =>  Auth::user()->id
            );
            foreach ($invoice as $key => $item) {
                $key = str_replace('invoice_', '', $key);
                $idata[$key] = $item;
            }
            userInvoice::create($idata);
        }

        $data = array_merge($recipient, $invoice);
        $data['remark'] = $request->post('remark')??'';
        $data['status'] = 'success';

        $cart = Cart::cart();
        Cart::find($cart->id)->update($data);

        $user = Auth::user()->load('manage_user');
        $order_no = date('ymdHis').rand(100,999);
        
        $order = Order::create([
            "order_no"        => $order_no,
            "user_id"         => $user->id,
            "cart_id"         => $cart->id,
            "department_id"   => $user->manage_user[0]->department_id,
            "price"           => $cart->price,
            "price_remark"    => $cart->price_remark,
            "status"          => 'new',
            "payment_status"  => 'pending',
            "shipping_status" => 'pending'
        ]);

        Order::notification($order->id, 'create_order');

        return redirect()->route('order', ['order_no'=>$order_no]);
        exit();
    }

    /*
        API型式 Stateless
    */
    public function add(Request $request){
        $cart = Cart::addItem($request, $request->post('uid'));

        if ($cart !== FALSE) {
            $this->output(TRUE, '已加入購物車', array('cart'=>$cart, 'count'=>$cart->count));
        }else{
            $this->output(FALSE, '加入購物車發生錯誤');
        }        
    }

    public function update(Request $request){
        $cart = Cart::updateItem($request, $request->post('uid'));

        if ($cart !== FALSE) {
            $price = Product::price($request->post('product_id'), $request->post('quantity'));

            $this->output(TRUE, '已更新購物車', array('cart'=>$cart, 'price'=>$price, 'count'=>$cart->count));
        }else{
            $this->output(FALSE, '更新購物車發生錯誤');
        }        
    }

    public function del(Request $request){
        $cart = Cart::delItem($request, $request->post('uid'));

        if ($cart !== FALSE) {
            $this->output(TRUE, '已將商品移除購物車', array('cart'=>$cart, 'count'=>$cart->count));
        }else{
            $this->output(FALSE, '加入購物車發生錯誤');
        }        
    }

    public function copy(Request $request){
        if (Cart::copy($request->post('uid'))) {
            $this->output(TRUE, __('page.cart.copy_cart_success'));
        }else{
            $this->output(TRUE, __('page.cart.copy_cart_faiil'));
        }        
    }
}
