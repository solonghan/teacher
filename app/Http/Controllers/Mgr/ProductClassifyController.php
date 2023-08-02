<?php

namespace App\Http\Controllers\Mgr;

use Illuminate\Http\Request;
use App\Models\ProductClassify;
use App\Models\ProductCategory;

class ProductClassifyController extends  Mgr
{
    
	public function __construct()
	{
		parent::__construct();
		$this->data['active'] = 'PRODUCT';
		$this->data['sub_active'] = 'PRODUCT_CLASSIFY';

        $this->data['select']['category_id'] = ProductCategory::where('lang', 'tw')->get()->toArray();
	}

	private $param = [
		['主分類',       'category_id',        'select',   TRUE, '', 12, 12, '', ['id','title']],
		['標題(中文)',       'title',        'text',   TRUE, '', 12, 12, ''],
        ['標題(EN)',        'title_en',        'text',   TRUE, '', 12, 12, ''],
	];
	public function index(Request $request)
	{
		$this->data['controller'] = 'product_classify';
		$this->data['title'] = "產業子類別";
		$this->data['parent'] = "";
		$this->data['parent_url'] = "";
		$this->data['th_title'] = $this->th_title_field(
			[
				['#', '', ''],
                ['主類別', '', ''],
				['子類別名稱(中文)', '', ''],
                // ['子類別名稱(EN)', '', ''],
				['建立時間', '', ''],
				['動作', '', '']
			]
		);
		$this->data['btns'] = [
			['<i class="ri-add-fill"></i>', '新增產業子類別', route('mgr.product_classify.add'), 'primary']
		];

		$this->data['template_item'] = 'mgr/items/template_item';

		$data = ProductClassify::where("lang", 'tw')->with('category')->get();

        $this->data['data'] = array();
        foreach ($data as $item) {
            $this->data['data'][] = array(
                "id"    =>  $item->id,
                "data"  =>  array(
                    $item->id,
                    $item->category->title,
                    $item->title,
                    $item->created_at
                )
            );
        }

		return view('mgr/template_list', $this->data);
	}

	public function add(Request $request){
		if ($request->isMethod('post')) {
			$formdata = $this->process_post_data($this->param, $request, ['tw', 'en']);

			$res = ProductClassify::updateOrCreate($formdata['tw']);

            $category_en = ProductCategory::where(['lang'=>'en', 'parent_id'=>$formdata['tw']['category_id']])->first();
            if ($category_en !== null) {
                $formdata['en']['category_id'] = $category_en->id;
            }
			$formdata['en']['parent_id'] = $res->id;
			$formdata['en']['lang'] = 'en';
			ProductClassify::updateOrCreate($formdata['en']);
			if ($res) {
				$this->js_output_and_redirect('新增成功', 'mgr.product_classify');
			} else {
				$this->js_output_and_back('新增發生錯誤');
			}
			exit();
		}

		$this->data['title'] = "新增產業子類別";
		$this->data['parent'] = "產業子類別";
		$this->data['parent_url'] = route('mgr.product_classify');
		$this->data['action'] = route('mgr.product_classify.add');
		$this->data['submit_txt'] = '確認新增';

		$this->data['params'] = $this->generate_param_to_view($this->param);

		return view('mgr/template_form', $this->data);
	}

	public function edit(Request $request, $id){
		$data = ProductClassify::find($id);
		if ($request->isMethod('post')) {
			$formdata = $this->process_post_data($this->param, $request, ['tw', 'en']);

			$res = ProductClassify::updateOrCreate(['id'=>$id], $formdata['tw']);

            $category_en = ProductCategory::where(['lang'=>'en', 'parent_id'=>$formdata['tw']['category_id']])->first();
            if ($category_en !== null) {
                $formdata['en']['category_id'] = $category_en->id;
            }
			ProductClassify::updateOrCreate(['parent_id'=>$id, 'lang'=>'en'], $formdata['en']);
			if ($res) {
				$this->js_output_and_redirect('編輯成功', 'mgr.product_classify');
			} else {
				$this->js_output_and_back('編輯發生錯誤');
			}
			exit();
		}

		$this->data['title'] = "編輯 ".$data->title;
		$this->data['parent'] = "產業子類別";
		$this->data['parent_url'] = route('mgr.product_classify');
		$this->data['action'] = route('mgr.product_classify', ['id'=>$id]);
		$this->data['submit_txt'] = '確認編輯';

		$data = $data->toArray();
        $data_en = ProductClassify::where(['parent_id'=>$data['id'], 'lang'=>'en'])->first()->toArray();
        $lang_data = array(
            'tw'    =>  $data,
            'en'    =>  $data_en
        );
		$this->data['params'] = $this->generate_param_to_view($this->param, $lang_data, false, ['tw','en']);

		return view('mgr/template_form', $this->data);
	}

    public function del(Request $request){
        $id = $request->post('id');
        
        $obj = ProductClassify::find($id);
        if ($obj->delete()) {
            $this->output(TRUE, "Delete success");
        }else{
            $this->output(FALSE, "Delete fail");
        }
    }

}
