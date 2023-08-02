<?php

namespace App\Http\Controllers\Mgr;

use Illuminate\Http\Request;
use App\Models\MemberDepartment;

class MemberDepartmentController extends Mgr
{
    
	public function __construct()
	{
		parent::__construct();
		$this->data['active'] = 'MEMBER';
		$this->data['sub_active'] = 'DEPARTMENT';

		$this->data['select']['is_review'] = array(
			array("id"=>1, "text"=>"需審核"),
			array("id"=>0, "text"=>"略過審核"),
		);
	}

	private $param = [
		['部門名稱',       'title',        'text',   TRUE, '', 6, 12, ''],
		['訂單是否需審核',   'is_review',    'select',   TRUE, '', 6, 12, '', ['id','text']],
        ['備註',           'remark',        'text',   FALSE, '', 12, 12, ''],
	];
	public function index(Request $request)
	{
		$this->data['controller'] = 'member_department';
		$this->data['title'] = "部門管理";
		$this->data['parent'] = "";
		$this->data['parent_url'] = "";
		$this->data['th_title'] = $this->th_title_field(
			[
				['#', '', ''],
				['部門名稱', '', ''],
				['備註', '', ''],
				['動作', '', '']
			]
		);
		$this->data['btns'] = [
			['<i class="ri-add-fill"></i>', '新增部門', route('mgr.member_department.add'), 'primary']
		];

		$this->data['template_item'] = 'mgr/items/template_item';

		// $data = MemberDepartment::get();

        // $this->data['data'] = array();
        // foreach ($data as $item) {
        //     $this->data['data'][] = array(
        //         "id"    =>  $item->id,
        //         "data"  =>  array(
        //             $item->id,
        //             $item->title,
        //             $item->remark
        //         )
        //     );
        // }

		return view('mgr/template_list', $this->data);
	}

	public function add(Request $request){
		if ($request->isMethod('post')) {
			$formdata = $this->process_post_data($this->param, $request);

			$res = MemberDepartment::updateOrCreate($formdata);

			if ($res) {
				$this->js_output_and_redirect('新增成功', 'mgr.member_department');
			} else {
				$this->js_output_and_back('新增發生錯誤');
			}
			exit();
		}

		$this->data['title'] = "新增部門";
		$this->data['parent'] = "部門管理";
		$this->data['parent_url'] = route('mgr.member_department');
		$this->data['action'] = route('mgr.member_department.add');
		$this->data['submit_txt'] = '確認新增';

		$this->data['params'] = $this->generate_param_to_view($this->param);

		return view('mgr/template_form', $this->data);
	}

	public function edit(Request $request, $id){
		$data = MemberDepartment::find($id);
		if ($request->isMethod('post')) {
			$formdata = $this->process_post_data($this->param, $request);

			$res = MemberDepartment::updateOrCreate(['id'=>$id], $formdata);

			if ($res) {
				$this->js_output_and_redirect('編輯成功', 'mgr.member_department');
			} else {
				$this->js_output_and_back('編輯發生錯誤');
			}
			exit();
		}

		$this->data['title'] = "編輯 ".$data->title;
		$this->data['parent'] = "部門管理";
		$this->data['parent_url'] = route('mgr.member_department');
		$this->data['action'] = route('mgr.member_department', ['id'=>$id]);
		$this->data['submit_txt'] = '確認編輯';

		$data = $data->toArray();
        
		$this->data['params'] = $this->generate_param_to_view($this->param, $data);

		return view('mgr/template_form', $this->data);
	}

    public function del(Request $request){
        $id = $request->post('id');
        
        $obj = MemberDepartment::find($id);
        if ($obj->delete()) {
            $this->output(TRUE, "Delete success");
        }else{
            $this->output(FALSE, "Delete fail");
        }
    }
}
