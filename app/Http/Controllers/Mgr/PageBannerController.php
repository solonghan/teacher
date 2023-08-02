<?php

namespace App\Http\Controllers\Mgr;

use Illuminate\Http\Request;
use App\Models\PageBanner;
use Illuminate\Support\Facades\Storage;

class PageBannerController extends Mgr
{
    
	public function __construct()
	{
		parent::__construct();
		$this->data['active'] = 'SETTING';
		$this->data['sub_active'] = 'PAGE_BANNER';
	}

	private $param = [
		['對應位置(程式參數)',       'type',        'text',   TRUE, '', 3, 12, ''],
        ['圖片(中文)',        'img',        'image',   TRUE, '', 12, 12, '', [1366 / 300]],
        ['圖片(EN)',        'img_en',        'image',   FALSE, '', 12, 12, '', [1366 / 300]],
	];
	public function index(Request $request)
	{
		$this->data['controller'] = 'page_banner';
		$this->data['title'] = "Page Banner";
		$this->data['parent'] = "";
		$this->data['parent_url'] = "";
		$this->data['th_title'] = $this->th_title_field(
			[
				['#', '', ''],
				['位置', '', ''],
                ['圖(中)', '', ''],
				['建立時間', '', ''],
				['動作', '', '']
			]
		);
		$this->data['btns'] = [
			['<i class="ri-add-fill"></i>', '新增Banner', route('mgr.page_banner.add'), 'primary']
		];

		$this->data['template_item'] = 'mgr/items/template_item';

		$data = PageBanner::get();

        $this->data['data'] = array();
        foreach ($data as $item) {
            $this->data['data'][] = array(
                "id"    =>  $item->id,
                "data"  =>  array(
                    $item->id,
                    $item->type,
                    "<img src='".env('APP_URL').Storage::url($item->img)."' style='height: 80px;'>",
                    $item->created_at
                )
            );
        }

		return view('mgr/template_list', $this->data);
	}

	public function add(Request $request){
		if ($request->isMethod('post')) {
			$formdata = $this->process_post_data($this->param, $request);

			$res = PageBanner::updateOrCreate($formdata);

			if ($res) {
				$this->js_output_and_redirect('新增成功', 'mgr.page_banner');
			} else {
				$this->js_output_and_back('新增發生錯誤');
			}
			exit();
		}

		$this->data['title'] = "新增Banner";
		$this->data['parent'] = "Page Banner";
		$this->data['parent_url'] = route('mgr.page_banner');
		$this->data['action'] = route('mgr.page_banner.add');
		$this->data['submit_txt'] = '確認新增';

		$this->data['params'] = $this->generate_param_to_view($this->param);

		return view('mgr/template_form', $this->data);
	}

	public function edit(Request $request, $id){
		$data = PageBanner::find($id);
		if ($request->isMethod('post')) {
			$formdata = $this->process_post_data($this->param, $request);

			$res = PageBanner::updateOrCreate(['id'=>$id], $formdata);
			if ($res) {
				$this->js_output_and_redirect('編輯成功', 'mgr.page_banner');
			} else {
				$this->js_output_and_back('編輯發生錯誤');
			}
			exit();
		}

		$this->data['title'] = "編輯 ".$data->title;
		$this->data['parent'] = "熱門Banner";
		$this->data['parent_url'] = route('mgr.page_banner');
		$this->data['action'] = route('mgr.page_banner', ['id'=>$id]);
		$this->data['submit_txt'] = '確認編輯';

		$data = $data->toArray();
        
		$this->data['params'] = $this->generate_param_to_view($this->param, $data);

		return view('mgr/template_form', $this->data);
	}

    public function del(Request $request){
        $id = $request->post('id');
        
        $obj = PageBanner::find($id);
        if ($obj->delete()) {
            $this->output(TRUE, "Delete success");
        }else{
            $this->output(FALSE, "Delete fail");
        }
    }

}
