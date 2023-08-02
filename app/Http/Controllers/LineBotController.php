<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LineBot;
use App\Models\Member;
use App\Models\Order;
use App\Models\Product;
use Auth;
use Illuminate\Contracts\Encryption\DecryptException;
class LineBotController extends BaseController
{
    public function send(){
        $msg = "訂單#4567890-\n\n產品A  $1,235,358\n\n產品B  $135,351";
        LineBot::send("complex", 2, $msg, "有一筆訂單要審核", "前往審核", "https://anbon.tw/");
    }

    public function callback(){
        $content = file_get_contents('php://input');
        file_put_contents('log.txt', "LineBot: ".$content . PHP_EOL, FILE_APPEND);
        $line_id = 0;
        $msg = '';
        $data = json_decode($content, true);

        if (array_key_exists('events', $data)) {
            $line_id = $data['events'][0]['source']['userId'];
            $reply_token = $data['events'][0]['replyToken'];

            if ($data['events'][0]['type'] == 'message') {
                $msg = trim($data['events'][0]['message']['text']);

                if ($msg != '') {
                    $last_one = LineBot::orderBy('id','desc')->where('line_id', $line_id)->first();
                    if ($last_one != null) {
                        $login = Auth::guard('mgr')->attempt([
                            'email'    => $last_one->msg,
                            'password' => $msg
                        ]);
                        
                        if ($login) {
                            
                            // Member::where('line_id', $line_id)->first();
                            Auth::guard('mgr')->user()->update(['line_id'=>$line_id]);
    
                            // LineBot::send('simple',Auth::guard('mgr')->user()->id, "已登入帳號: ".Auth::guard('mgr')->user()->username);
                            LineBot::send('simple',Auth::guard('mgr')->user()->id, "已登入帳號: ".Auth::guard('mgr')->user()->username, '', '', '', true, $reply_token);
                        }
                    }
                }
            }else if ($data['events'][0]['type'] == 'postback'){
                $encrypt_data = $data['events'][0]['postback']['data'];
                try{
                    $decrypt = decrypt($encrypt_data);
                    file_put_contents('log.txt', "decrypt: ".json_encode($decrypt) . PHP_EOL, FILE_APPEND);
                    $id = $decrypt[1];

                    if ($decrypt[0] == 'commit_order') {
                        $order_data = Order::find($id);
                        if ($order_data['status'] != 'new') return;
                        $res = Order::commit_order($id, $decrypt[2]);
                        
                        LineBot::send('simple', $decrypt[2], $res['msg'], '', '', '', true, $reply_token);
                    }else if ($decrypt[0] == 'product_pass') {
                        $msg = "";
                        if (strpos($id, "_") === false) return;
                        $ids = explode("_", $id);
                        $order_data = Order::where('id', $ids[0])->with("cart.items.product.manager")->with('user.manage_user')->first();
                        if ($order_data['status'] != 'pending') return;

                        Order::product_pass($ids[0], $ids[1], $decrypt[2]);
                        // foreach ($order_data->cart->items as $p) {
                        //     foreach ($p->product->manager as $member) {
                        //         if ($member->id == $decrypt[2]) {
                        //             $res = Order::product_pass($id, $p->product_id, $decrypt[2]);
                        //             if ($res) {
                        //                 $msg .= $p->name."\n";
                        //             }
                        //         }
                        //     }
                        // }
                        $p = Product::find($ids[1]);
                        LineBot::send('simple', $decrypt[2], $p->name."已審核通過", '', '', '', true, $reply_token);
                    }else if ($decrypt[0] == 'order_pass') {
                        $msg = "";
                        $order_data = Order::where('id', $id)->with("cart.items.product.manager")->with('user.manage_user')->first();
                        if ($order_data['status'] != 'inreview') return;
                        
                        $res = Order::order_pass($id, $decrypt[2]);
                        
                        if ($res) {
                            LineBot::send('simple', $decrypt[2], "訂單#".$order_data->order_no."審核通過", '', '', '', true, $reply_token);
                        }
                    }else if ($decrypt[0] == 'director_pass') {
                        $msg = "";
                        $order_data = Order::where('id', $id)->with("cart.items.product.manager")->with('user.manage_user')->first();
                        if ($order_data['status'] != 'director_review') return;
                        
                        $res = Order::order_director_pass($id, $decrypt[2]);
                        
                        if ($res) {
                            LineBot::send('simple', $decrypt[2], "訂單#".$order_data->order_no."審核通過", '', '', '', true, $reply_token);
                        }
                    }
                }catch(DecryptException $e){
                    file_put_contents('log.txt', "decrypt fail: ".$encrypt_data . PHP_EOL, FILE_APPEND);
                }
            }

            
        }
        $res = LineBot::create([
            'line_id' => $line_id,
            'msg'     => $msg,
            'content' => $content
        ]);
        
    }
}
