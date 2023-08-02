<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use App\Models\Page;
use App\Models\userInvoice;
use App\Models\userRecipient;

class OrderController extends BaseController
{
    public function __construct(){
        parent::__construct();
        
    }

    public function index(Request $request, $order_no){
        $this->middleware('auth');
        $this->data['title'] = __('page.cart.title');
        $this->data['show_title'] = __('page.cart.title');

        $this->data['data'] = Order::data($order_no);
        
        return view('order', $this->data);
    }

    public function quotation(Request $request, $order_no){
        $this->data['data'] = $this->data['order'] = $data = Order::data($order_no);
        $this->data['user'] = $data->user;
        $this->data['cart'] = $data->cart;
        $this->data['msg'] = '';
        $this->data['title'] = '報價單';

        $type = "";
        if ($data->user->transaction_type == 'normal') {
            $type = 'mail_cash';
        }else if ($data->user->transaction_type == 'month') {
            $type = 'mail_monthly';
        }else if ($data->user->transaction_type == 'day') {
            $type = 'mail_daily';
        }
        $mail_content = Page::where('type', $type)->first();

        $this->data['payment_des'] = $mail_content->content;

        $this->data['is_print'] = true;
        return view('mail/user_order', $this->data);
    }
}
