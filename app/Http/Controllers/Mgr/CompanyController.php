<?php

namespace App\Http\Controllers\Mgr;

use App\Models\User;
use App\Models\Company;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class CompanyController extends Mgr
{
    public function __construct()
    {
        parent::__construct();
        $this->data['active'] = 'SETTING';
        $this->data['sub_active'] = 'COMPANY';
    }

    private $param = [
        ['分公司名稱(中文)', 'company',   'text',   TRUE, '', 6, 12, ''],
        ['分公司名稱(EN)', 'company_en', 'text', TRUE, '', 6, 12, ''],
        ['電話號碼', 'mobile', 'text', FALSE, '', 4, 12, ''],
        ['傳真號碼', 'tel', 'text', FALSE, '', 4, 12, ''],
        ['Email', 'email', 'text', FALSE, '', 4, 12, ''],
        ['地址(中文)', 'address', 'text', FALSE, '', 6, 12, ''],
        ['地址(EN)', 'address_en', 'text', FALSE, '', 6, 12, ''],
        ['嵌入地圖HTML', 'embed_map', 'textarea', FALSE, '', 12, 12, '', [300]],
    ];
    public function index(Request $request)
    {
        $this->data['controller'] = 'company';
        $this->data['title'] = "頁尾分公司";
        $this->data['parent'] = "";
        $this->data['parent_url'] = "";
        $this->data['th_title'] = $this->th_title_field(
            [
                ['#', '', ''],
                ['分公司', '', ''],
                ['資訊', '', ''],
                ['動作', '', '']
            ]
        );
        $this->data['btns'] = [
            ['<i class="ri-add-fill"></i>', '新增分公司', route('mgr.company.add'), 'primary']
        ];
        
        $this->data['template_item'] = 'mgr/items/template_item';
        
        $data = Company::where(['lang'=>'tw'])->get();
        
        $this->data['data'] = array();
        foreach ($data as $item) {
            $info = "";
            if ($item->mobile != '') $info .= "電話: ".$item->mobile."<br>";
            if ($item->tel != '') $info .= "傳真: ".$item->tel."<br>";
            if ($item->email != '') $info .= "Email: ".$item->email."<br>";
            if ($item->address != '') $info .= "地址: ".$item->address;
            $this->data['data'][] = array(
                "id"    =>  $item->id,
                "data"  =>  array(
                    $item->id,
                    $item->company,
                    $info
                )
            );
        }
        
        return view('mgr/template_list', $this->data);
    }
    
    public function add(Request $request){
        if ($request->isMethod('post')) {
            $formdata = $this->process_post_data($this->param, $request, ['tw', 'en']);
            
            $res = Company::updateOrCreate($formdata['tw']);
            
            $formdata['en']['parent_id'] = $res->id;
            $formdata['en']['lang'] = 'en';
            Company::updateOrCreate($formdata['en']);
            if ($res) {
                $this->js_output_and_redirect('新增成功', 'mgr.company');
            } else {
                $this->js_output_and_back('新增發生錯誤');
            }
            exit();
        }

        $this->data['title'] = "新增分公司";
        $this->data['parent'] = "全站設定";
        $this->data['parent_url'] = route('mgr.company');
        $this->data['action'] = route('mgr.company.add');
        $this->data['submit_txt'] = '確認新增';

        $this->data['params'] = $this->generate_param_to_view($this->param);
        
        return view('mgr/template_form', $this->data);
    }

    public function edit(Request $request, $id){
        $data = Company::find($id);
        if ($request->isMethod('post')) {
            $formdata = $this->process_post_data($this->param, $request, ['tw', 'en']);
            $res = Company::updateOrCreate(['id'=>$id], $formdata['tw']);

            Company::updateOrCreate(['parent_id'=>$id, 'lang'=>'en'], $formdata['en']);
            if ($res) {
                $this->js_output_and_redirect('編輯成功', 'mgr.company');
            } else {
                $this->js_output_and_back('編輯發生錯誤');
            }
            exit();
        }

        $this->data['title'] = "編輯 ".$data->title;
        $this->data['parent'] = "全站設定";
        $this->data['parent_url'] = route('mgr.company');
        $this->data['action'] = route('mgr.company', ['id'=>$id]);
        $this->data['submit_txt'] = '確認編輯';

        $data = $data->toArray();
        $data_en = Company::where(['parent_id'=>$data['id'], 'lang'=>'en'])->first()->toArray();
        $lang_data = array(
            'tw'    =>  $data,
            'en'    =>  $data_en
        );
        $this->data['params'] = $this->generate_param_to_view($this->param, $lang_data, false, ['tw','en']);
        
        return view('mgr/template_form', $this->data);
    }

    public function del(Request $request){
        $id = $request->post('id');
        
        $page = Company::find($id);
        if ($page->delete()) {
            $this->output(TRUE, "Delete success");
        }else{
            $this->output(FALSE, "Delete fail");
        }
    }
}
