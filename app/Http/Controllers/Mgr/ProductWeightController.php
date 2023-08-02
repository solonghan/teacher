<?php

namespace App\Http\Controllers\Mgr;

use Illuminate\Http\Request;
use App\Models\ProductWeight;

class ProductWeightController extends Mgr
{
    
	public function __construct()
	{
		parent::__construct();
		$this->data['active'] = 'PRODUCT';
		$this->data['sub_active'] = 'PRODUCT_WEIGHT';
	}

	private $param = [
		['重量單位',       'title',        'text',   TRUE, '', 12, 12, ''],
        ['備註',        'remark',        'text',   FALSE, '', 12, 12, ''],
	];
	public function index(Request $request)
	{
		$this->data['controller'] = 'product_weight';
		$this->data['title'] = "重量單位";
		$this->data['parent'] = "";
		$this->data['parent_url'] = "";
		$this->data['th_title'] = $this->th_title_field(
			[
				['#', '', ''],
				['重量單位名稱', '', ''],
                ['備註', '', ''],
				['動作', '', '']
			]
		);
		$this->data['btns'] = [
			['<i class="ri-add-fill"></i>', '新增重量單位', route('mgr.product_weight.add'), 'primary']
		];

		$this->data['template_item'] = 'mgr/items/template_item';

		$data = ProductWeight::get();

        $this->data['data'] = array();
        foreach ($data as $item) {
            $this->data['data'][] = array(
                "id"    =>  $item->id,
                "data"  =>  array(
                    $item->id,
                    $item->title,
                    $item->remark
                )
            );
        }

		return view('mgr/template_list', $this->data);
	}

	public function add(Request $request){
		if ($request->isMethod('post')) {
			$formdata = $this->process_post_data($this->param, $request);

			$res = ProductWeight::updateOrCreate($formdata);

			if ($res) {
				$this->js_output_and_redirect('新增成功', 'mgr.product_weight');
			} else {
				$this->js_output_and_back('新增發生錯誤');
			}
			exit();
		}

		$this->data['title'] = "新增重量單位";
		$this->data['parent'] = "重量單位";
		$this->data['parent_url'] = route('mgr.product_weight');
		$this->data['action'] = route('mgr.product_weight.add');
		$this->data['submit_txt'] = '確認新增';

		$this->data['params'] = $this->generate_param_to_view($this->param);

		return view('mgr/template_form', $this->data);
	}

	public function edit(Request $request, $id){
		$data = ProductWeight::find($id);
		if ($request->isMethod('post')) {
			$formdata = $this->process_post_data($this->param, $request);

			$res = ProductWeight::updateOrCreate(['id'=>$id], $formdata);
			if ($res) {
				$this->js_output_and_redirect('編輯成功', 'mgr.product_weight');
			} else {
				$this->js_output_and_back('編輯發生錯誤');
			}
			exit();
		}

		$this->data['title'] = "編輯 ".$data->title;
		$this->data['parent'] = "重量單位";
		$this->data['parent_url'] = route('mgr.product_weight');
		$this->data['action'] = route('mgr.product_weight', ['id'=>$id]);
		$this->data['submit_txt'] = '確認編輯';

		$data = $data->toArray();
        
		$this->data['params'] = $this->generate_param_to_view($this->param, $data);

		return view('mgr/template_form', $this->data);
	}

    public function del(Request $request){
        $id = $request->post('id');
        
        $obj = ProductWeight::find($id);
        if ($obj->delete()) {
            $this->output(TRUE, "Delete success");
        }else{
            $this->output(FALSE, "Delete fail");
        }
    }
}
