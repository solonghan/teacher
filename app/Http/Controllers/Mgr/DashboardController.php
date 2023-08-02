<?php

namespace App\Http\Controllers\Mgr;

use App\Models\Member;
use App\Models\User;
use App\Models\Contact;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

use App\Mail\UserMail;
use App\Mail\NotificationMail;
use App\Mail\NormalMail;
use Illuminate\Support\Facades\Mail;
class DashboardController extends Mgr
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
       
        parent::__construct();
        $this->data['active'] = 'dashboard';
        $this->data['parent'] = "";
        $this->data['parent_url'] = "";
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {

        // $res = Mail::to("maxchen3571@hotmail.com")->cc('j2612280@gmail.com')
        // ->send(new UserMail(1, 1, "尹士肯測試信", ''));

        $this->data['role'] = Auth::guard('mgr')->user()->role;

        // $this->data['user_cnt'] = User::where('status', 'normal')->whereNull('deleted_at')->count();
        // $this->data['user_inreview_cnt'] = User::where('status', 'inreview')->whereNull('deleted_at')->count();
        
        
        // $this->data['order_cnt'] = Order::count();
        // $this->data['total_income'] = Order::whereIn('status', ['complete','success'])->sum('price');
        // $this->data['total_receivable'] = Order::whereIn('status', ['pending','new','inreview','confirmed'])->sum('price');

        $summary = [
            'inreview'     => 0,
            'watting_pay'  => 0,
            'watting_ship' => 0,
            'shipping'     => 0,
            'complete'     => 0,
            'cancel'       => 0,
            'low_stock'    => 0,
            'not_enough'   => 0
        ];
        // $summary['low_stock'] = Product::whereRaw('quota < min_stock')->where('min_stock', '>', '0')->whereNull('deleted_at')->count();
        // $summary['not_enough'] = Product::where('quota','<=','0')->where('lang','tw')->whereNull('deleted_at')->count();
        $chart = array();
        $date = date('Y-m').'-01';
        for ($i=11; $i >= 0; $i--) { 
            $ym = date('Y-m', strtotime('- '.$i.' month', strtotime($date)));
            $chart[$ym] = array(
                "income"     => 0,
                "receivable" => 0,
                "cancel"     => 0,
            );
        }
        // foreach (Order::with('cart.items')->get() as $order) {
        //     if ($order->status == 'new' || $order->status == 'pending' || $order->status == 'inreview') $summary['inreview']++;
        //     if ($order->payment_status == 'unpaid' || $order->payment_status == 'waiting') $summary['watting_pay']++;
        //     if ($order->shipping_status == 'shipping_waiting') $summary['watting_ship']++;
        //     if ($order->shipping_status == 'shipping') $summary['shipping']++;
        //     if ($order->status == 'cancel') $summary['cancel']++;
        //     if ($order->status == 'complete') $summary['complete']++;

        //     $ym = substr($order->created_at, 0, 7);
        //     if (array_key_exists($ym, $chart)) {
        //         if ($order->status == 'complete' || $order->status == 'success') {
        //             $chart[$ym]['income']+=$order->price;
        //         }else if ($order->status == 'cancel') {
        //             $chart[$ym]['cancel']++;
        //         }else{
        //             $chart[$ym]['receivable']+=$order->price;
        //         }
        //     }

        //     foreach ($order->cart->items as $item) {
        //         if ($item->status == 'not_enough') $summary['not_enough']++;

        //     }
        // }
        $this->data['summary'] = $summary;

        $this->data['chart_x'] = array();
        $this->data['chart_income'] = array();
        $this->data['chart_receivable'] = array();
        $this->data['chart_cancel'] = array();
        foreach ($chart as $ym => $c) {
            $this->data['chart_x'][] = strval($ym);
            
            $this->data['chart_income'][] = $c['income'];
            $this->data['chart_receivable'][] = $c['receivable'];
            $this->data['chart_cancel'][] = $c['cancel'];
        }
        $this->data['chart_x'] = json_encode($this->data['chart_x']);

        return view('mgr/index', $this->data);
    }

    public function simulate_login(Request $request, $id){
        
        if (Auth::guard('mgr')->user()->role == 'super') {
            $user = Member::find($id);
            Auth::guard('mgr')->login($user);
        }
        return redirect()->route('mgr.home');
    }
}
