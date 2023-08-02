<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Http;
// use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\Media;
use App\Models\Company;
use App\Models\Page;
use App\Models\Order;
use App\Models\Cart;
use App\Models\Notification;
use App\Models\Product;
use App\Models\JobTitle;
use Auth;
class BaseController extends Controller
{
    protected $debug_mode = true;
    
	protected $data = array();
	protected $locale = '';
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

	public function __construct(){
		$this->data['title'] = env('APP_NAME');
		$this->data['description'] = env('APP_DESCRIPTION');
		//麵包屑
		$this->data['nav_index'] = false;
		$this->data['breadcrumb'] = true;
		$this->data['show_title'] = '';
		$this->data['parent'] = '';
		$this->data['parent_link'] = '';

		$this->locale = App::currentLocale();
		$this->data['locale'] = $this->locale;

		// $this->data['company'] = Company::list($this->locale);
		// $this->data['footer_intro'] = Page::list('footer_intro',$this->locale, false);

		//middleware 執行後，才會抓到 Auth, Session ..etc
		$this->middleware(function ($request, $next) {
			// $this->data['cart'] = Cart::cart();
			
			return $next($request);
		});
	}

	//Param
	//Column 	Description
	// 0		欄位中文名稱
	// 1		DB資料庫欄位
	// 2		類型
	// 3 		預設值(若為select則是 [value, text])
	// 4		是否必填 TRUE/FALSE
	// 5		提示文字
	// 6		其它條件設定 (Optional)
	//				select 			=>	[value代的欄位值, text代的欄位值]
	//				img,img_multi 	=>	data ratio
	public function process_post_data($param, $request, $langs = false, $required_enabled = true, $required_alarm_type = 'js'){
		//若要自動分多國語系 $langs 則為 ['tw', 'en']		required_alarm_type:js/alert
		$data = array();
		if($langs !== false){
			foreach ($langs as $l) {
				$data[$l] = array();
			}
		}
		$should_continue_field = ["", "button", "array", "header", "img_multi", "plain", "empty", "file"];
		if (!$required_enabled) {
			$should_continue_field[] = 'select_multi';
		}
		foreach ($param as $item) {
			$name = $field = $item[0];
			$field = $item[1];
			$type = $item[2];
			$required = $item[3];
			if (in_array($type, $should_continue_field)) continue;
			
			$val = (!is_null($request->post($field)))?$request->post($field):'';
			if ($required_enabled && $required) {
				if ($type == 'select_multi' && (is_null($val) || !is_array($val) || count($val) <= 0)) {
					if ($required_alarm_type == 'js') {
						$this->js_output_and_back($name."必須至少選擇1個項目");
					}else{
						if (!array_key_exists('alert', $data)) $data['alert'] = '';
						$data['alert'] .= $name."必須至少選擇1個項目"."\n";
					}
				}else if($val == ''){
					if ($required_alarm_type == 'js') {
						$this->js_output_and_back($name."不可為空");
					}else{
						if (!array_key_exists('alert', $data)) $data['alert'] = '';
						$data['alert'] .= $name."不可為空"."\n";
					}
				}
			}

			if ($type == "checkbox_multi") {
				$val = (is_array($val) && count($val) > 0)?serialize($val):serialize(array());
			}else if ($type == "checkbox") {
				$val = ($val == "on")?1:0;
			}
			// else if ($type == "text_disabled"){
			// 	$val = 
			// }

			if ($langs !== false) {
				$assign_to_arr = false;
				foreach ($langs as $l) {
					// if (strpos($field, '_'.$l) !== false) {
					if (substr($field , strlen($field) - 3, 3) == '_'.$l) {
						$data[$l][str_replace('_'.$l, '', $field)] = $val;
						$assign_to_arr = true;
					}
				}
				if (!$assign_to_arr) {
					$data['tw'][$field] = $val;
				}
			}else{
				$data[$item[1]] = $val;
			}
		}
		return $data;
	}

	public function generate_param_to_view($param, $data = false, $is_field_to_key = false, $langs = false){
		// if ($data !== false) {
		// 	for ($i=0; $i < count($param); $i++) { 
		// 		if ($param[$i][1] != "" && array_key_exists($param[$i][1], $data)) {
		// 			if ($param[$i][2] == "button") continue;
		// 			if (substr($param[$i][2], 0, 4) == "img_" || substr($param[$i][2], 0, 4) == "file" || substr($param[$i][2], 0, 7) == "select_") continue;
					
		// 			if ($param[$i][2] == 'city') {
		// 				$param[$i][3] = array($data['city'], $data['dist']);
		// 			}else{
		// 				$param[$i][3] = $data[$param[$i][1]];	
		// 			}
		// 		}
		// 	}
		// }

		$params = array();
		foreach ($param as $item) {
			$obj = array(
				"title"    => $item[0],
				"field"    => $item[1],
				"type"     => $item[2],
				"value"    => ($item[2]=='day')?date('Y-m-d'):'',
				"required" => $item[3],
				"hint"     => $item[4],
				"layout_l" => $item[5],
				"layout_s" => $item[6],
				"class"    => $item[7],
				"option"   => (array_key_exists(8, $item))?$item[8]:''
			);

			//針對不同類型，option定義field
			if (array_key_exists(8, $item)) {
				if ($obj['type'] == 'editor'){
					$obj['option'] = array(
						'height'	=>	$item[8][0]
					);
				}
			}

			if ($obj['type'] == 'image' || $obj['type'] == 'img_multi') {
				$obj['option'] = array(
					'ratio'	=>	0,
					'crop'	=>	'crop'
				);
				if (array_key_exists(8, $item)) {
					$obj['option']['ratio'] = $item[8][0];
					if (array_key_exists(1, $item[8])) $obj['option']['crop'] = ($item[8][1])?'crop':'without';
				}
			}

			if ($obj['type'] == 'select_multi') {
				if ($obj['value'] == '') $obj['value'] = array();
			}

			if ($data !== false && $obj['field'] != 'password') {
				$field = $item[1];

				$obj['value'] = $data[$field];
			// 	if ($langs !== false) {
			// 		$lang = 'tw';
			// 		foreach ($langs as $l) {
			// 			// if (strpos($obj['field'], '_'.$l) !== false) {
			// 			if (substr($obj['field'], strlen($obj['field']) - 3, 3) == '_'.$l) {
			// 				$lang = $l;
			// 			}
			// 		}
			// 		$field = $item[1];
			// 		if ($lang != 'tw') $field = str_replace('_'.$lang, '', $field);
			// 		if (array_key_exists($field, $data[$lang])) {
			// 			$obj['value'] = $data[$lang][$field];
			// 		}
			// 	}else{
			// 		if (array_key_exists($item[1], $data)) {
			// 			$obj['value'] = $data[$item[1]];
			// 		}	
			// 	}
			}

			if ($is_field_to_key) {
				$params[$item[1]] = $obj;
			}else{
				$params[] = $obj;
			}
		}
		return $params;
	}

	public function js_output_and_next($msg, $url, $param = array(), $hash = ''){
		if ($hash != '') $hash = '#'.$hash;
		echo "<script> location.href = '".route($url, $param).$hash."'; </script>";
		exit();
	}

	public function js_output_and_redirect($msg, $url, $param = array(), $hash = ''){
		if ($hash != '') $hash = '#'.$hash;
		echo "<script> alert('".$msg."'); location.href = '".route($url, $param).$hash."'; </script>";
		exit();
	}
    
	public function js_output_and_back($msg){
		echo '<script> alert("'.$msg.'"); history.back(); </script>';
		exit();
	}

	protected function output($code, $msg, $data = FALSE){
		header('Content-type: application/json');
		if ($data === FALSE) {
			echo json_encode(array("status"=>$code, "msg"=>$msg));
		}else{
			$data['status'] = $code;
			$data['msg'] = $msg;
			echo json_encode($data);
		}
		exit();
	}

	protected function select_array_to_key_array($data, $identity = "id", $value = "text"){
		$output = array();
		foreach ($data as $item) {
			$output[$item[$identity]] = $item[$value];
		}
		return $output;
	}

    protected function generate_code($length = 6, $only_degital = FALSE){
        $alphabet_upper = range('A', 'Z');
        $alphabet_lower = range('a', 'z');
        $s = "";
        for ($i=0; $i <=9 ; $i++) $s.= strval($i);
        if (!$only_degital) {
            foreach ($alphabet_upper as $a) $s .= $a;
            // $s .= '_';
            for ($i=0; $i <=9 ; $i++) $s.= strval($i);
            foreach ($alphabet_lower as $a) $s .= $a;
            for ($i=0; $i <=9 ; $i++) $s.= strval($i);
            // $s .= '@';
        }
        
        $cnt = strlen($s);

        $code = "";
        for ($i=0; $i < $length; $i++) { 
            $code .= substr($s, rand(0, $cnt - 1), 1);
        }   
        return $code;
    }


	protected function get_client_ip() {
	    $ipaddress = '';
	    if (isset($_SERVER['HTTP_CLIENT_IP']))
	        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
	    else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
	        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
	    else if(isset($_SERVER['HTTP_X_FORWARDED']))
	        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
	    else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
	        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
	    else if(isset($_SERVER['HTTP_FORWARDED']))
	        $ipaddress = $_SERVER['HTTP_FORWARDED'];
	    else if(isset($_SERVER['REMOTE_ADDR']))
	        $ipaddress = $_SERVER['REMOTE_ADDR'];
	    else
	        $ipaddress = 'UNKNOWN';
	    return $ipaddress;
	}


	//util
	public function util($action = false){
		switch ($action) {
			case 'cron':{
				file_put_contents('log.txt', "Cron: " . date('Y-m-d H:i:s') . PHP_EOL, FILE_APPEND);
				Notification::cron_send_mail();
				Notification::cron_send_line();

				$hour = date('H');
				$minute = date('i');
				if($hour == '0' && $minute == '30'){
					Product::check_quota();
					Order::check_order_agree();
				}
					

			}
			break;
			case 'img_upload':{
				$this->img_upload();
			}
				break;
			case 'file_upload':{
				$this->file_upload();
			}
				break;
			case 'notification_read':{
				$this->notification_read();
			}
				break;
		}
	}

	public function notification_read(){
		$id = Request::post('id');

		$role = Auth::guard('mgr')->user()->role;

		if ($id == 'all') {
			Notification::where(['member_id'=>Auth::guard('mgr')->user()->id])->update(['is_read'=>1]);
			$unread = 0;
			$this->output(true, "save", ['url'=>'', 'unread'=>$unread]);
			exit();
		}
		$n = Notification::find($id);
		if ($n != null) {
			$url = $n->url;
			$n->is_read = 1;
			$n->save();

			if ($url == '') {
				if ($n->type == 'new_user') {
					$url = route('mgr.users.new');
				}else{
					//目前幾乎都是訂單相關的通知
					$order = Order::find($n->relation_id);
					if ($order != null) $url = route('mgr.order.detail', ['id'=>$order->order_no]);
				}
			}

			$unread = 
			Notification::where('member_id', Auth::guard('mgr')->user()->id)
                        ->where('is_notification', 1)
                        ->where('is_read', 0)
                        ->count();
			$this->output(true, "save", ['url'=>$url, 'unread'=>$unread]);
		}else{
			$this->output(false, "not found");
		}
	}

	public function file_upload(){
		$multi = Request::post('multi');
		$field = Request::post('field');
		$files = Request::file("files");

		$html = '';
		$data = array();
		foreach($files as $file) {
			$extension = $file->getClientOriginalExtension();
            $path = uniqid().'.'.$extension;
        
			if(Storage::put($path, file_get_contents($file->getRealPath()))){
				$filename = $file->getClientOriginalName();
				$m = Media::create([
					'media_type' => 'file',
					'path'       => $path,
					'realname'   => $filename
				]);
				
				if ($multi) {
					$html .= view('mgr/items/template_multi_file_item', [
						'field'    => $field,
						'path'     => $path,
						'file_id'  => $m->id,
						'filename' => $filename
					])->render();
				}
				$data[] = array(
					"path"     => $path,
					"realpath" => env('APP_URL').Storage::url($path),
					"file_id"  => $m->id
				);
			}
		}
		if ($html != '') {
			$this->output(TRUE, 'Upload Success', array(
				"multi" => $multi,
				"html"  => $html,
				"data"  => $data
			));
		}else{
			$this->output(FALSE, "Upload fail");
		}
	}

	public function img_upload(){
		$multi = Request::post('multi');
		$field = Request::post('field');
		$crop = Request::post('crop');
		
		if ($multi == null || $multi == "false" || $multi === false) {
			$multi = false;
		}else{
			$multi = true;
		}

		$res = FALSE;
		$path = '';
		if ($crop == 'crop') {
			$base64_str = Request::post('imageData');
			$extension = explode('/', explode(':', substr($base64_str, 0, strpos($base64_str, ';')))[1])[1];   // .jpg .png .pdf
			$replace = substr($base64_str, 0, strpos($base64_str, ',')+1); 
			$image = str_replace($replace, '', $base64_str); 
			$image = str_replace(' ', '+', $image); 
			$image = base64_decode($image);
			
			$path = uniqid().'.'.$extension;
			$res = Storage::put($path, $image);
		}else{
			$image = Request::file("image");
			$extension = $image->getClientOriginalExtension();
            $path = uniqid().'.'.$extension;

			$res = Storage::put($path, file_get_contents($image->getRealPath()));
		}
		
		if($res && $path != ''){
			Media::create([
				'path'	=>	$path
			]);
			$html = '';
			if ($multi) {
				$html .= view('mgr/items/template_multi_img_item', [
					'field'	=>	$field,
					'pic'	=>	$path
				])->render();
			}
			$this->output(TRUE, 'Upload Success', array(
				"path"     => $path,
				"realpath" => env('APP_URL').Storage::url($path),
				"multi"    => $multi,
				"html"     => $html
			));
		}else{
			$this->output(FALSE, "Upload fail");
		}
	}

	public function send_mail($email, $content, $title){

            // 建立CURL連線
            $ch = curl_init();

            // 設定擷取的URL網址
            curl_setopt($ch, CURLOPT_URL, "https://app.wundoo.com.tw/api/send_mail");
            curl_setopt($ch, CURLOPT_HEADER, false);
            //將curl_exec()獲取的訊息以文件流的形式返回，而不是直接輸出。
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
            //設定CURLOPT_POST 為 1或true，表示要用POST方式傳遞
            curl_setopt($ch, CURLOPT_POST, 1); 
            //CURLOPT_POSTFIELDS 後面則是要傳接的POST資料。
            curl_setopt($ch, CURLOPT_POSTFIELDS, array(
                "title"   => $title,
                "content" => $content,
                "email"   => $email
            ));

            // 執行
            $result=curl_exec($ch);

            // 關閉CURL連線
            curl_close($ch);

	}
	public function title_data(){
		print 123;exit;
		$id = Request::post('id');
		$data=JobTitle::find($id);

		print_r($data);exit;
	}
}
