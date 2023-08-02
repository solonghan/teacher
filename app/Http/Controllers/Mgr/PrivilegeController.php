<?php

namespace App\Http\Controllers\Mgr;

use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\Privilege;
use App\Models\PrivilegeMenu;
use App\Models\PrivilegeMenuRelated;

class PrivilegeController extends Mgr
{
    
	public function __construct()
	{
		parent::__construct();
		$this->data['active'] = 'MEMBER';
		$this->data['sub_active'] = 'PRIVILEGE';

		$this->data['select']['op_user'] = array(
			array("id"=>"all", "text"=>"可瀏覽/操作所有會員"),
			array("id"=>"specific", "text"=>"僅可瀏覽/操作特定會員(含訂單)"),
		);

		$this->data['select']['op_product'] = array(
			array("id"=>"all", "text"=>"可瀏覽/操作所有產品"),
			array("id"=>"specific", "text"=>"僅可瀏覽/操作特定產品"),
		);
	}

	private $param = [
		['群組名稱',       'title',        'text',   TRUE, '', 12, 12, ''],
		['會員操作權限',  'op_user',        'select',   FALSE, '', 6, 12, '', ['id', 'text']],
		['產品操作權限',    'op_product',   'select',   FALSE, '', 6, 12, '', ['id', 'text']],
        ['權限',           'privilege',    'privilege',   FALSE, '', 12, 12, ''],
	];
	public function index(Request $request, $status = 'normal')
	{
		$this->data['controller'] = 'privilege';
		$this->data['title'] = "權限管理";
		$this->data['parent'] = "";
		$this->data['parent_url'] = "";
		$this->data['th_title'] = $this->th_title_field(
			[
				['#', '', ''],
				['群限名稱', '', ''],
				['異動時間', '', ''],
				['動作', '', '']
			]
		);
		$this->data['btns'] = [
			['<i class="ri-add-fill"></i>', '新增權限', route('mgr.privilege.add'), 'primary']
		];

		$this->data['template_item'] = 'mgr/items/template_item';

        $data = array();
        $data = Privilege::get();

        $this->data['data'] = array();
        foreach ($data as $item) {
            $obj = array();
            $obj[] = $item->id;
            $obj[] = $item->title;
            $obj[] = $item->updated_at;

            $this->data['data'][] = array(
                "id"    =>  $item->id,
                "data"  =>  $obj
            );
        }

		return view('mgr/template_list', $this->data);
	}

	public function add(Request $request){
		
		if ($request->isMethod('post')) {
			$formdata = $this->process_post_data($this->param, $request);
			unset($formdata['privilege']);

			$res = Privilege::create($formdata);
			if ($res) {
				PrivilegeMenuRelated::updateRelated($res->id, $request->all());
				$this->js_output_and_redirect('新增成功', 'mgr.privilege');
			} else {
				$this->js_output_and_back('新增發生錯誤');
			}
			exit();
		}

		$this->data['title'] = "新增權限群組";
		$this->data['parent'] = "權限管理";
		$this->data['parent_url'] = route('mgr.privilege');
		$this->data['action'] = route('mgr.privilege.add');
		$this->data['submit_txt'] = '確認新增';

		$this->data['params'] = $this->generate_param_to_view($this->param);

		$this->data['privilege_action'] = PrivilegeMenu::menuAction();
		$this->data['privilege_menu'] = PrivilegeMenu::allMenu();

		return view('mgr/template_form', $this->data);
	}

	public function edit(Request $request, $id){
		$data = Privilege::find($id);
		if ($request->isMethod('post')) {
			$formdata = $this->process_post_data($this->param, $request);
			unset($formdata['privilege']);

			$res = Privilege::updateOrCreate(['id'=>$id], $formdata);
			if ($res) {
				PrivilegeMenuRelated::updateRelated($id, $request->all());
				$this->js_output_and_redirect('編輯成功', 'mgr.privilege');
			} else {
				$this->js_output_and_back('編輯發生錯誤');
			}
			exit();
		}

		$this->data['title'] = "編輯 ".$data->title;
		$this->data['parent'] = "權限管理";
		$this->data['parent_url'] = route('mgr.privilege');
		$this->data['action'] = route('mgr.privilege.edit', ['id'=>$id]);
		$this->data['submit_txt'] = '確認編輯';

		$data = $data->toArray();
        
		$this->data['params'] = $this->generate_param_to_view($this->param, $data);

		$this->data['privilege_action'] = PrivilegeMenu::menuAction();
		$this->data['privilege_menu'] = PrivilegeMenu::allMenu($id);

		return view('mgr/template_form', $this->data);
	}

    public function del(Request $request){
        $id = $request->post('id');
        
        $obj = Privilege::find($id);
        if ($obj->delete()) {
            $this->output(TRUE, "Delete success");
        }else{
            $this->output(FALSE, "Delete fail");
        }
    }
}
