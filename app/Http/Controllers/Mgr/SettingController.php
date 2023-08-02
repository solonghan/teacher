<?php

namespace App\Http\Controllers\Mgr;

use App\Models\Setting;
use App\Models\User;
use App\Models\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SettingController extends Mgr
{
    public function __construct()
    {
        parent::__construct();
        $this->data['active'] = 'SETTING';
        $this->data['sub_active'] = 'SETTING';
    }

    private $param = [
        ['標題(中文)', 'title',   'text',   TRUE, '', 12, 12, ''],
        ['內文(中文)', 'content', 'editor', TRUE, '', 12, 12, '', [800]],
        ['標題(EN)', 'title_en',   'text',   TRUE, '', 12, 12, ''],
        ['內文(EN)', 'content_en', 'editor', TRUE, '', 12, 12, '', [800]],
    ];
    
    public function index(Request $request){
        $this->data['active'] = 'MEMBER';
        $this->data['sub_active'] = 'SETTINGS';
        $param = array();
        $data = array();
        foreach (Setting::get() as $item) {
            $type = $item->type;
            if ($type == 'switch') $type = 'select';
            $param[] = [$item->name, 'col_'.$item->id,  $type ,   TRUE, '', 12, 12, '', ['id', 'text']];
            $data['col_'.$item->id] = $item->value;
        }
        if ($request->isMethod('post')) {
            $formdata = $this->process_post_data($param, $request);

            foreach ($formdata as $key => $value) {
                $id = str_replace("col_", "", $key);
                Setting::updateOrCreate(['id'=>$id], ['value'=>$value]);
            }
            
            $this->js_output_and_redirect('更新成功', 'mgr.settings');
            exit();
        }

        $this->data['title'] = "系統參數";
        $this->data['parent'] = "權限管理";
        $this->data['parent_url'] = "";
        $this->data['action'] = route('mgr.settings');
        $this->data['submit_txt'] = '確認更新';

        $this->data['params'] = $this->generate_param_to_view($param, $data);
        
        $this->data['select']['col_1'] = array(
            array("id"=>"on", "text"=>"開啟"),
            array("id"=>"off", "text"=>"關閉"),
        );
        return view('mgr/template_form', $this->data);
    }

    public function page(Request $request, $type){
        $this->data['sub_active'] = 'SETTING_'.strtoupper($type);
        $data = Page::where('type', $type)->first();
        if ($data == null){
            Page::create([
                "type"    => $type,
                "title"   => "",
                "content" => ""
            ]);
            $data = Page::where('type', $type)->first();

            Page::create([
                "lang"      => "en",
                "parent_id" => $data->id,
                "type"      => $type,
                "title"     => "",
                "content"   => ""
            ]);
        }
        if ($type == 'default_carousel') {
            $this->param = array(); //1366 * 700
            $this->data['active'] = 'HOME';
        }else if ($type == 'footer_intro') {
            $this->param = [
                ['內文(中文)', 'content', 'textarea', TRUE, '', 6, 12, '', [800]],
                ['內文(EN)', 'content_en', 'textarea', TRUE, '', 6, 12, '', [800]],
            ];
        }
        if ($data->with_img == 1) {
            $ratio = 0;
            $ratio_str = '';
            $crop = true;
            if ($data->img_w > 0 && $data->img_h > 0) {
                $ratio = intval($data->img_w) / intval($data->img_h);
                $ratio_str = '('.$data->img_w.":".$data->img_h.")";
            }else{
                $crop = false;
            }
            $this->param[] = ['圖片'.$ratio_str, 'img', 'image', FALSE, '', 12, 12, '', [$ratio, $crop]];
        }

        if ($request->isMethod('post')) {
            $formdata = $this->process_post_data($this->param, $request, ['tw', 'en']);

            if ($type == 'default_carousel' && $formdata['tw']['img'] == '') {
                $this->js_output_and_back('預設輪播圖不可清空');
            }

            $res = Page::updateOrCreate(['id'=>$data->id], $formdata['tw']);

            
            Page::updateOrCreate(['parent_id'=>$data->id, 'lang'=>'en'], $formdata['en']);
            if ($res) {
                $this->js_output_and_redirect('更新成功', 'mgr.setting', ['type'=>$type]);
            } else {
                $this->js_output_and_back('更新發生錯誤');
            }
            exit();
        }

        $this->data['title'] = $data->title;
        $this->data['parent'] = "全站文案";
        $this->data['parent_url'] = "";
        $this->data['action'] = route('mgr.setting', ['type'=>$type]);
        $this->data['submit_txt'] = '確認更新';

        $data = $data->toArray();
        $data_en = Page::where(['parent_id'=>$data['id'], 'lang'=>'en'])->first()->toArray();
        $lang_data = array(
            'tw'    =>  $data,
            'en'    =>  $data_en
        );
        $this->data['params'] = $this->generate_param_to_view($this->param, $lang_data, false, ['tw', 'en']);
        
        return view('mgr/template_form', $this->data);
    }

}
