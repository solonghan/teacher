<?php

namespace App\Http\Controllers\Mgr;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class ContactController extends Mgr
{
    public function __construct()
    {
        parent::__construct();
        $this->data['active'] = 'CONTACT';
        $this->data['sub_active'] = 'CONTACT';
    }

    private $param = [
        ['標題(中文)', 'title',   'text',   TRUE, '', 12, 12, ''],
        ['內文(中文)', 'content', 'editor', TRUE, '', 12, 12, '', [200]],
        ['標題(EN)', 'title_en',   'text',   TRUE, '', 12, 12, ''],
        ['內文(EN)', 'content_en', 'editor', TRUE, '', 12, 12, '', [200]],
    ];
    public function index(Request $request)
    {
        $this->data['controller'] = 'contact';
        $this->data['title'] = "關於我們";
        $this->data['parent'] = "";
        $this->data['parent_url'] = "";
        $this->data['th_title'] = $this->th_title_field(
            [
                ['#', '', ''],
                ['使用者/資訊', '', ''],
                ['詢問內容', '', ''],
                ['提交時間', '', ''],
                ['狀態', '', ''],
                ['動作', '', '']
            ]
        );
        $this->data['btns'] = [
            
        ];
        
        $this->data['template_item'] = 'mgr/items/template_item';
        
        $data = Contact::get();

        $this->data['data'] = array();
        foreach ($data as $item) {
            $info = "";
            if ($item->username != '') $info .= "姓名: ".$item->username."<br>";
            if ($item->company != '') $info .= "公司: ".$item->company."<br>";
            if ($item->department != '') $info .= "部門: ".$item->department."<br>";
            if ($item->phone != '') $info .= "電話: ".$item->area_code." ".$item->phone."<br>";
            if ($item->email != '') $info .= "Email: ".$item->email."<br>";
            if ($item->address != '') $info .= "地址: ".$item->address;
            $status = '<span class="badge rounded-pill bg-secondary">未處理</span>';
            if ($item->status == 'success') $status = '<span class="badge rounded-pill bg-success">已處理</span>';

            $priv_edit = FALSE;
            $priv_del = TRUE;
            $other_btns = array();
            if ($item->status == 'pending') {                
                $other_btns[] = array(
                    "class"  => "btn-success",
                    "action" => "location.href='".route('mgr.contact.success', ['id'=>$item->id])."'",
                    "text"   => "標記為已處理"
                );
            }
            $this->data['data'][] = array(
                "id"    =>  $item->id,
                "data"  =>  array(
                    $item->id,
                    $info,
                    $item->content,
                    $status,
                    $item->created_at
                ),
                "other_btns" => $other_btns,
                "priv_edit"  => $priv_edit,
                "priv_del"   => $priv_del
            );
        }
        
        return view('mgr/template_list', $this->data);
    }

    public function success(Request $request, $id){
        $data = Contact::find($id);
        $res = Contact::updateOrCreate(['id'=>$id], ['status'=>'success']);

        if ($res) {
            $this->js_output_and_redirect('已標記為已處理', 'mgr.contact');
        } else {
            $this->js_output_and_back('發生錯誤');
        }
    }

    public function del(Request $request){
        $id = $request->post('id');
        
        $Contact = Contact::find($id);
        if ($Contact->delete()) {
            $this->output(TRUE, "Delete success");
        }else{
            $this->output(FALSE, "Delete fail");
        }
    }
}
