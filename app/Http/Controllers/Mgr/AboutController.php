<?php

namespace App\Http\Controllers\Mgr;

use App\Models\User;
use App\Models\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class AboutController extends Mgr
{
    public function __construct()
    {
        parent::__construct();
        $this->data['active'] = 'ABOUT';
        $this->data['sub_active'] = 'ABOUT';
    }

    private $param = [
        ['標題(中文)', 'title',   'text',   TRUE, '', 12, 12, ''],
        ['內文(中文)', 'content', 'editor', TRUE, '', 12, 12, '', [200]],
        ['標題(EN)', 'title_en',   'text',   TRUE, '', 12, 12, ''],
        ['內文(EN)', 'content_en', 'editor', TRUE, '', 12, 12, '', [200]],
    ];
    public function index(Request $request)
    {
        $this->data['controller'] = 'about';
        $this->data['title'] = "關於我們";
        $this->data['parent'] = "";
        $this->data['parent_url'] = "";
        $this->data['th_title'] = $this->th_title_field(
            [
                ['#', '', ''],
                ['標題', '', ''],
                ['建立時間', '', ''],
                ['動作', '', '']
            ]
        );
        $this->data['btns'] = [
            ['<i class="ri-add-fill"></i>', '新增文案', route('mgr.about.add'), 'primary']
        ];
        
        $this->data['template_item'] = 'mgr/items/page_item';
        
        $this->data['data'] = Page::where(['type'=>'about', 'lang'=>'tw'])->get();
        
        
        return view('mgr/template_list', $this->data);
    }
    
    public function add(Request $request){
        if ($request->isMethod('post')) {
            $formdata = $this->process_post_data($this->param, $request, ['tw', 'en']);
            
            $formdata['tw']['type'] = 'about';
            $res = Page::updateOrCreate($formdata['tw']);
            
            $formdata['en']['type'] = 'about';
            $formdata['en']['parent_id'] = $res->id;
            $formdata['en']['lang'] = 'en';
            Page::updateOrCreate($formdata['en']);
            if ($res) {
                $this->js_output_and_redirect('新增成功', 'mgr.about');
            } else {
                $this->js_output_and_back('新增發生錯誤');
            }
            exit();
        }

        $this->data['title'] = "新增文案";
        $this->data['parent'] = "關於我們";
        $this->data['parent_url'] = route('mgr.about');
        $this->data['action'] = route('mgr.about.add');
        $this->data['submit_txt'] = '確認新增';

        $this->data['params'] = $this->generate_param_to_view($this->param);
        
        return view('mgr/template_form', $this->data);
    }

    public function edit(Request $request, $id){
        $data = Page::find($id);
        if ($request->isMethod('post')) {
            $formdata = $this->process_post_data($this->param, $request, ['tw', 'en']);
            $res = Page::updateOrCreate(['id'=>$id], $formdata['tw']);

            Page::updateOrCreate(['parent_id'=>$id, 'lang'=>'en'], $formdata['en']);
            if ($res) {
                $this->js_output_and_redirect('編輯成功', 'mgr.about');
            } else {
                $this->js_output_and_back('編輯發生錯誤');
            }
            exit();
        }

        $this->data['title'] = "編輯 ".$data->title;
        $this->data['parent'] = "關於我們";
        $this->data['parent_url'] = route('mgr.about');
        $this->data['action'] = route('mgr.about', ['id'=>$id]);
        $this->data['submit_txt'] = '確認編輯';

        $data = $data->toArray();
        $data_en = Page::where(['parent_id'=>$data['id'], 'lang'=>'en'])->first()->toArray();
        $lang_data = array(
            'tw'    =>  $data,
            'en'    =>  $data_en
        );
        $this->data['params'] = $this->generate_param_to_view($this->param, $lang_data, false, ['tw','en']);
        
        return view('mgr/template_form', $this->data);
    }

    public function del(Request $request){
        $id = $request->post('id');
        
        $page = Page::find($id);
        if ($page->delete()) {
            $this->output(TRUE, "Delete success");
        }else{
            $this->output(FALSE, "Delete fail");
        }
    }
}
