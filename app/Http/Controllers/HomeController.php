<?php

namespace App\Http\Controllers;

use App\Models\Carousel;
use App\Models\AgencyBrand;
use App\Models\Page;
use App\Models\News;
use App\Models\Media;
use App\Models\User;
use App\Models\userEmailVerified;
use App\Models\userInvoice;
use App\Models\userRecipient;
use App\Models\PageBanner;
use App\Models\Contact;
use App\Models\Member;
use App\Models\Notification;
use App\Models\LineBot;
use App\Models\ProductCategory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

use App\Mail\NormalMail;
use Illuminate\Support\Facades\Mail;
class HomeController extends BaseController
{
    
    public function __construct(){
        parent::__construct();
    }

    // //切換語系
    // public function swtich_locale(Request $request, $locale){
    //     $prev = url()->previous();
    //     if (Str::contains($prev, config('locales'))) {
    //         dd("contains");
    //     }

    // }

    //首頁
    public function index(Request $request){
        //首頁不需顯示麵包屑
        $this->data['nav_index'] = true;
        $this->data['breadcrumb'] = false;

        $this->data['carousel'] = Carousel::list($this->locale);
        $default_carousel = Page::where(['type'=>'default_carousel'])->first();
        $this->data['default_carousel'] = env('APP_URL').Storage::url($default_carousel->img);
        $this->data['brand'] = AgencyBrand::list($this->locale);
        $this->data['news'] = News::list($this->locale);
        $this->data['news_category'] = News::category(false);

        $this->data['product_category'] = ProductCategory::list($this->locale);

        $this->data['intro'] = Page::list('home_intro', $this->locale, false);
        return view('index', $this->data);
    }

    public function privacy(Request $request){
        $this->data['breadcrumb'] = false;
        $this->data['data'] = Page::where(['type'=>'privacy', 'lang'=>$this->locale])->first();

        return view('page', $this->data);
    }

    public function shopping_flow(Request $request){
        $this->data['breadcrumb'] = false;
        $this->data['data'] = Page::where(['type'=>'shopping_flow', 'lang'=>$this->locale])->first();

        return view('page', $this->data);
    }

    public function payment_method(Request $request){
        $this->data['breadcrumb'] = false;
        $this->data['data'] = Page::where(['type'=>'payment', 'lang'=>$this->locale])->first();

        return view('page', $this->data);
    }

    public function about(Request $request){
        $this->data['title'] = __('page.about');
        $this->data['show_title'] = __('page.about');
        $this->data['data'] = Page::list('about', $this->locale);
        $this->data['page_banner'] = PageBanner::data('about', $this->locale);
        return view('about', $this->data);
    }

    public function brand(Request $request){
        $this->data['title'] = __('page.agency_brand');
        $this->data['show_title'] = __('page.agency_brand');
        $this->data['data'] = AgencyBrand::list($this->locale);
        $this->data['page_banner'] = PageBanner::data('agency_brand', $this->locale);

        return view('brand', $this->data);
    }

    public function brand_detail(Request $request, $id){
        $data = AgencyBrand::data($id, $this->locale);
        if ($data == null) return redirect()->route('home');

        $this->data['parent'] = __('page.agency_brand');
        $this->data['parent_link'] = route('brand');
        $this->data['title'] = $data->name;
        $this->data['show_title'] = $data->name;
        $this->data['data'] = $data;
        return view('brand-detail', $this->data);
    }

    public function contact(Request $request){    
        if ($request->isMethod('post')) {
            if (!$request->has('g-recaptcha-response') || $request->post('g-recaptcha-response') == '') {
                $this->js_output_and_back('recaptcha verify failed');
                exit();
            }
                
            // 建立CURL連線
            $ch = curl_init();

            // 設定擷取的URL網址
            curl_setopt($ch, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
            curl_setopt($ch, CURLOPT_HEADER, false);
            //將curl_exec()獲取的訊息以文件流的形式返回，而不是直接輸出。
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
            //設定CURLOPT_POST 為 1或true，表示要用POST方式傳遞
            curl_setopt($ch, CURLOPT_POST, 1); 
            //CURLOPT_POSTFIELDS 後面則是要傳接的POST資料。
            curl_setopt($ch, CURLOPT_POSTFIELDS, array(
                "secret"	=>	"6LdYrssiAAAAAKlzLpr2JXY2wzXAvI_kc7GtRAvI",
                "response"	=>	$request->post("g-recaptcha-response"),
                "remoteip"	=>	$this->get_client_ip()
            ));

            // 執行
            $result=curl_exec($ch);

            // 關閉CURL連線
            curl_close($ch);

            $r = json_decode($result, true);

            if (!$r['success']) {
                $this->js_output_and_back('recaptcha verify failed');
                exit();
            }else{
                //success
                $fields = ['username','email','phone','company','department','address','content','area_code', 'mobile'];
                $data = array();
                foreach ($fields as $field) {
                    if ($request->post($field) == '') {
                        $this->js_output_and_back('請填寫完整表單再送出');
                    }
                    $data[$field] = $request->post($field);
                }
                $res = Contact::create($data);

                $title = "有一則新聯絡訊息 (".$data['email']."/".$data['mobile'].")";
                $content = "新聯絡訊息\n";

                if ($data['email'] != '') $content .= "Email: ".$data['email']."\n";
                if ($data['mobile'] != '') $content .= "手機: ".$data['mobile']."\n";
                if ($data['username'] != '') $content .= "姓名: ".$data['username']."\n";
                if ($data['company'] != '') $content .= "公司: ".$data['company']."\n";
                if ($data['department'] != '') $content .= "部門: ".$data['department']."\n";
                if ($data['phone'] != '') $content .= "電話: ".$data['area_code']." ".$data['phone']."\n";
                if ($data['address'] != '') $content .= "地址: ".$data['address']."\n";
                if ($data['content'] != '') $content .= "詢問內容: ".$data['content'];  

                Notification::add(0, 'contact', 0, $title, $content);
                if ($res) {
                    $this->js_output_and_redirect(__('page.contact_form.success'), 'contact');
                }else{
                    $this->js_output_and_back(__('page.contact_form.fail'));
                }
            }
        }    
        $this->data['title'] = __('page.contact');
        $this->data['show_title'] = __('page.contact');

        $this->data['page_banner'] = PageBanner::data('contact', $this->locale);
        return view('contact', $this->data);
    }

	public function news(Request $request){
        $this->data['title'] = __('page.news');
        $this->data['show_title'] = __('page.news');
        $this->data['data'] = News::list($this->locale);
        $this->data['news_category'] = News::category(false);
        array_unshift($this->data['news_category'], array(
            'id'    =>  'all',
            'text'  =>  __('page.all')
        ));
        $this->data['page_banner'] = PageBanner::data('news', $this->locale);
        $this->data['tab'] = 'all';
        if ($request->has('tab') && $request->get('tab') != '') $this->data['tab'] = $request->get('tab');
        return view('news', $this->data);
	}

	public function news_detail(Request $request, $id){
        $this->data['breadcrumb'] = false;
        $data = News::where(['id'=>$id, 'lang'=>$this->locale])->with('tags')->with('member')->first();
        if ($data == null) return redirect()->route('news');
        $this->data['data'] = $data;
        $this->data['pics'] = Media::where(['media_type'=>'img', 'position'=>'news_pics', 'relation_id'=>$id])->get();
        $this->data['title'] = $data->title;
        
        $this->data['side_news'] = News::list($this->locale, 3);
        $this->data['news_category'] = News::category(false, true, $this->locale);
        
        $this->data['related'] = News::related($id);

        $this->data['prev'] = News::prev($id);
        $this->data['next'] = News::next($id);

        return view('news-detail', $this->data);
	}

	public function news_list(Request $request){
        $this->data['title'] = __('page.news');
        $this->data['breadcrumb'] = false;
        
        $this->data['news'] = News::list($this->locale);
        $this->data['news_category'] = News::category(false, true, $this->locale);
        
        return view('news-detail', $this->data);
	}



    public function forgetpwd(Request $request){
        if ($request->isMethod('post')) {
            $user = User::where(['email'=>$request->post('email')])->first();

            if ($user != null) {
                $new_password = $this->generate_code(6);

                if($user->update(['password'=>Hash::make($new_password)])){
                    $title = "伊士肯化學會員忘記密碼信";
                    $content = "您好, 您的新密碼為： ".$new_password."<br>請<a href='".route('member')."'>點此</a>登入變更密碼";
                    Mail::to($request->post('email'))
                        ->send(new NormalMail($title, $content));
                    $this->js_output_and_redirect("已寄送新密碼至您的信箱", 'login');
                    exit();
                }                
            }
            $this->data['error'] = __('page.account_not_exist');
        }
        $this->data['title'] = __('page.forgetpwd');
        $this->data['page_banner'] = PageBanner::data('member', $this->locale);
        return view('forgetpwd', $this->data);
    }

    public function login(Request $request){
        if ($request->isMethod('post')) {
            $validated = $request->validate([
                'email' => 'required',
                'password' => 'required',
            ]);

           
            
            $credentials = $request->only('email', 'password');
            if (Auth::attempt($credentials)) {
                $status = Auth::user()->status;
                $user_id = Auth::user()->id;
                $email = Auth::user()->email;
                if ($status == 'normal') {
                    return redirect()->route('member', ['#order']);
                }else{
                    Auth::logout();
                    if ($status == 'inreview') {
                        $this->data['error'] = __('page.inreview');
                    // }else if ($status == 'verified'){
                    //     Session::put('register_user', $email);
                        // return redirect()->route('register', ['step'=>3]);
                    }else if ($status == 'block'){
                        $this->data['error'] = __('page.account_or_password_error');
                    }else {
                        User::where(['email'=>$email])->delete();
                        $this->data['error'] = __('page.account_not_exist');
                    }
                }
            }else{
                $exist = User::where(['email'=>$request->post('email')])->first();
                if ($exist == null) {
                    $this->data['error'] = __('page.account_not_exist');
                }else{
                    $this->data['error'] = __('page.account_or_password_error');
                }
            }
        }
        $this->data['title'] = __('page.login_btn');
        $this->data['page_banner'] = PageBanner::data('member', $this->locale);
        return view('login', $this->data);
    }

    public function register(Request $request, $step = 1){
        $this->data['title'] = __('page.register_btn');
        $this->data['page_banner'] = PageBanner::data('member', $this->locale);
        $user = User::newOne();
        
        if (Session::has('register_user')) {
            $exist = User::where(['email'=>Session::get('register_user')])->with('invoice')->with('recipient')->first();
            if ($exist != null) $user = $exist;
        }
        
        if ($step == 1) {
            $this->data['user'] = $user;
            
            return view('register', $this->data);
        }else if ($step == 2) {
            $syntax = [];
            if ($user->id != '' && $user->email == $request->post('email')) {
                $syntax = ['id'=>$user->id];
            }else{
                if (User::exist($request->post('email'))) $this->js_output_and_back(__('page.register.email_exist'));
            }
            $data = array();

            if ($request->post('password') == $request->post('password_confirm')) {
                $data['password'] = Hash::make($request->post('password'));
            }else{
                $this->js_output_and_back(__('page.register.password_not_match'));
            }

            $fields = ['email', 'username', 'company', 'tax_id', 'phone', 'ext'];
            foreach ($fields as $field) {
                if ($request->has($field) && $request->post($field) != null) {
                    $data[$field] = $request->post($field);
                }else{
                    $data[$field] = '';
                }
            }
            if (empty($syntax)) {
                $user = User::create($data);
            }else{
                $user = User::updateOrCreate($syntax, $data);
            }
            Session::put('register_user', $user->email);

            //寄信
            $code = $this->generate_code(6, TRUE);
            userEmailVerified::updateOrCreate(['email'=>$request->post('email')],[
                'user_id' => $user->id,
                'email'   => $request->post('email'),
                'code'    => $code,
                'status'  => 'pending'
            ]);
            $content = "伊士肯信箱確認信<br>驗證碼： ".$code;
            // $this->send_mail($request->post('email'), $content, '伊士肯信箱確認信');
            Mail::to($request->post('email'))->send(new NormalMail('伊士肯信箱確認信', $content));

            $this->data['user'] = $user;
            return view('register-two', $this->data);
        }else if($step == 3){
            if ($user == null || $user->id == null) return redirect()->route('register');
            
            if ($user->status == 'verified') {
                $this->data['user'] = $user;
                return view('register-three', $this->data);
            }
            $code = $request->post('code');
            
            if($code == null || $code == '' || strlen($code) != 6) $this->js_output_and_back(__('page.register.fillin_code'));

            $c = userEmailVerified::where(['user_id'=>$user->id,'status'=>'pending'])->first();
            if ($c == null) return redirect()->route('register');
            if ($code == $c->code && $c->email == $user->email) {
                $c->status = 'success';
                $c->save();

                $user->status = 'inreview';
                $user->save();

                $this->data['user'] = $user;
                return view('register-three', $this->data);
            }else{
                $this->js_output_and_back(__('page.register.verify_code_error'));
            }
        }else if($step == 4){
            if ($user == null || $user->id == null) return redirect()->route('register');

            userInvoice::where(['user_id'=>$user->id])->delete();

            $fields = ['company', 'tax_id', 'username', 'address', 'phone', 'ext', 'email'];
            $index = $request->post('index');
            for ($i=1; $i <= $index ; $i++) { 
                if ($request->has('company'.$i)) {   
                    $data = array(
                        'user_id'   =>  $user->id
                    );
                    foreach ($fields as $field) {
                        if ($request->has($field.$i) && $request->post($field.$i) != null) {
                            $data[$field] = $request->post($field.$i);
                        }else{
                            $data[$field] = '';
                        }
                    }
                    userInvoice::updateOrCreate($data, $data);
                }
            }

            $this->data['user'] = $user;
            return view('register-four', $this->data);
        }else if($step == 'complete'){
            if ($user == null || $user->id == null) return redirect()->route('register');
            userRecipient::where(['user_id'=>$user->id])->delete();

            $fields = ['username', 'address', 'phone', 'ext', 'email'];
            $index = $request->post('index');
            for ($i=1; $i <= $index ; $i++) { 
                if ($request->has('username'.$i)) {   
                    $data = array(
                        'user_id'   =>  $user->id
                    );
                    foreach ($fields as $field) {
                        if ($request->has($field.$i) && $request->post($field.$i) != null) {
                            $data[$field] = $request->post($field.$i);
                        }else{
                            $data[$field] = '';
                        }
                    }
                    userRecipient::updateOrCreate($data, $data);
                }
            }

            $user->status = 'inreview';
            // $user->status = 'normal';
            $user->save();

            Notification::add(0, 'new_user', $user->id, "新會員註冊: ".$user->username);
            //業助
			$mgr = Member::where('role', 'mgr')->get();
			foreach ($mgr as $member) {
				Notification::add($member->id, 'new_user', $user->id, "新會員註冊: ".$user->username);
			}

            Session::forget('register_user');
            return view('register-success', $this->data);
        }
    }

    public function register_invoice(Request $request){
        $index = $request->post('index');

        $html = view('register-three-invoice', ['index'=>$index])->render();

        $this->output(TRUE, 'success', array('html'=>$html, 'index'=>$index));
    }

    public function register_recpient(Request $request){
        $index = $request->post('index');

        $html = view('register-four-recipient', ['index'=>$index])->render();

        $this->output(TRUE, 'success', array('html'=>$html, 'index'=>$index));
    }
}
