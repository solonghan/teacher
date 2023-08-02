<?php

namespace App\Http\Controllers\Mgr;

use App\Models\User;
use App\Models\Member;
use App\Models\Page;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class MailController extends Mgr
{
    public function __construct()
    {
        parent::__construct();
        $this->data['active'] = 'MAIL';
        $this->data['sub_active'] = 'MAIL';
    }

    private $param = [
        ['款到發貨文案', 'cash',   'editor',   TRUE, '', 12, 12, '',[800]],
        ['月結文案', 'monthly', 'editor',     TRUE, '', 12, 12, '', [800]],
        ['日結文案', 'daily',   'editor',   TRUE, '', 12, 12, '', [800]],
    ];

	private $th_title = [
		['#', '', ''],
		['接收人', '', ''],
		['標題', '', ''],
        ['發送狀態', '', ''],
		['建立時間', '', ''],
		['', '', '']//動作
	];
    
    public function log(Request $request){
        $this->data['controller'] = 'mail';
		$this->data['title'] = "信件寄送紀錄";
		$this->data['parent'] = "";
		$this->data['parent_url'] = "";
		$this->data['th_title'] = $this->th_title_field($this->th_title);
		$this->data['btns'] = [	];

		return view('mgr/template_list_ajax', $this->data);
    }

    public function data(Request $request){
		$page   = $request->post('page')??'1';
		$search = $request->post('search')??'';

        $data = Notification::where('is_mail', 1)
                            ->where(function($query) use ($search) {
                                if ($search != ''){
                                    $query->orWhere('title', 'like', '%'.$search.'%');
                                    $query->orWhere('longText', 'like', '%'.$search.'%');
                                }
                            })
                            ->with('member')
                            ->orderBy('id', 'desc')
                            ->paginate(25,['*'],'page',$page);
                       
        $html = "";
        
        foreach ($data as $item) {
            $status = '未寄送';
            if ($item->is_mail_sent == 1) {
                $status = '<span class="badge badge-soft-primary">已寄送</span>';
            }
			
            $obj = array();
            $obj[] = $item->id;
			$obj[] = "【".Member::role_str($item->member->role)."】<br>".$item->member->username;
            $obj[] = $item->title;
            $obj[] = $status;
            $obj[] = $item->created_at;

			$priv_edit = FALSE;
			$priv_del = FALSE;
			$other_btns = array();
			// $other_btns[] = array(
            //     "class"  => "btn-success",
            //     "action" => "location.href='".route('mgr.users.product', ['user_id'=>$item->id])."'",
            //     "text"   => "產品價格"
            // );

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
			'html'       => $html,
			'page'       => $data->currentPage(),
			'total_page' => $data->lastPage()
		));
	}

    public function word(Request $request){
        $this->data['sub_active'] = 'MAIL_WORD';
        
        if ($request->isMethod('post')) {
            
            $res = true;
            if ($request->has('cash')) {
                $res = $res && Page::updateOrCreate(['type'=>'mail_cash'], [
                    'content'   =>  $request->post('cash')
                ]);
            }
            if ($request->has('monthly')) {
                $res = $res && Page::updateOrCreate(['type'=>'mail_monthly'], [
                    'content'   =>  $request->post('monthly')
                ]);
            }
            if ($request->has('daily')) {
                $res = $res && Page::updateOrCreate(['type'=>'mail_daily'], [
                    'content'   =>  $request->post('daily')
                ]);
            }
            
            if ($res) {
                $this->js_output_and_redirect('更新成功', 'mgr.mail.word');
            } else {
                $this->js_output_and_back('更新發生錯誤');
            }
            exit();
        }

        $this->data['title'] = "會員信件收款方式文案";
        $this->data['parent'] = "信件管理";
        $this->data['parent_url'] = "";
        $this->data['action'] = route('mgr.mail.word');
        $this->data['submit_txt'] = '確認更新';

        $data = array();
        $cash = Page::where('type', 'mail_cash')->first();
        $data['cash'] = $cash->content;

        $monthly = Page::where('type', 'mail_monthly')->first();
        $data['monthly'] = $monthly->content;

        $daily = Page::where('type', 'mail_daily')->first();
        $data['daily'] = $daily->content;
        
        $this->data['params'] = $this->generate_param_to_view($this->param, $data);
        
        return view('mgr/template_form', $this->data);
    }

}
