<?php

namespace App\Http\Controllers\Mgr;

use Illuminate\Http\Request;
use App\Models\Carousel;
use Illuminate\Support\Facades\Storage;

class CarouselController extends Mgr
{
	public function __construct()
	{
		parent::__construct();
		$this->data['active'] = 'HOME';
		$this->data['sub_active'] = 'CAROUSEL';
	}

	private $param = [
		['上線時間',	'online_date',	     'day', TRUE, '', 6, 12, ''],
		['下線時間',	'offline_date',	     'day', TRUE, '', 6, 12, ''],

		['中文介面',	'',	     'header', FALSE, '', 12, 12, ''],
		['輪播圖(中文)',		'path',       'image',   TRUE, '比例 1350:700', 12, 12, '', [1350 / 700]],
		['連結文字(中文)',	'link_txt',  'text',   FALSE, '若為空，前台文字隱藏', 6, 12, ''],
		['連結網址(中文)',	'link',	     'text', FALSE, '', 6, 12, ''],
		['子標文字(中文)',	'sub_text',  'text',   FALSE, '若為空，前台文字隱藏', 6, 12, ''],
		
		['英文介面',	'',	     'header', FALSE, '', 12, 12, ''],
		['輪播圖(EN)',		'path_en',       'image',   TRUE, '比例 1350:700', 12, 12, '', [1350 / 700]],
		['連結文字(EN)',	'link_txt_en',  'text',   FALSE, '若為空，前台文字隱藏', 6, 12, ''],
		['連結網址(EN)',	'link_en',	     'text', FALSE, '', 6, 12, ''],
		['子標文字(EN)',	'sub_text_en',  'text',   FALSE, '若為空，前台文字隱藏', 6, 12, ''],
	];
	public function index(Request $request)
	{
		$this->data['controller'] = 'carousel';
		$this->data['title'] = "首頁-輪播圖";
		$this->data['parent'] = "";
		$this->data['parent_url'] = "";
		$this->data['th_title'] = $this->th_title_field(
			[
				['#', '', ''],
				['圖片', '', ''],
				['連結', '', ''],
				['狀態/發佈時間', '', ''],
				['建立時間', '', ''],
				['動作', '', '']
			]
		);
		$this->data['btns'] = [
			['<i class="ri-add-fill"></i>', '新增輪播圖', route('mgr.carousel.add'), 'primary']
		];

		$this->data['template_item'] = 'mgr/items/carousel_item';

		$this->data['data'] = Carousel::where('lang', 'tw')->get();


		return view('mgr/template_list', $this->data);
	}

	public function add(Request $request){
		if ($request->isMethod('post')) {
			$formdata = $this->process_post_data($this->param, $request, ['tw','en']);

			// input file upload
			// $file = $request->file('logo');
			// $name = $file->getClientOriginalName();
			// $extension = $file->getClientOriginalExtension();
			// $path = Storage::putFileAs(
			// 	'agency', $file, time().".".$extension
			// );
			// $formdata['logo'] = $path;

			$res = Carousel::updateOrCreate($formdata['tw']);

			$formdata['en']['parent_id'] = $res->id;
			$formdata['en']['lang'] = 'en';
			Carousel::updateOrCreate($formdata['en']);
			if ($res) {
				$this->js_output_and_redirect('新增成功', 'mgr.carousel');
			} else {
				$this->js_output_and_back('新增發生錯誤');
			}
			exit();
		}

		$this->data['title'] = "新增輪播圖";
		$this->data['parent'] = "輪播圖";
		$this->data['parent_url'] = route('mgr.carousel');
		$this->data['action'] = route('mgr.carousel.add');
		$this->data['submit_txt'] = '確認新增';

		$this->data['params'] = $this->generate_param_to_view($this->param);

		return view('mgr/template_form', $this->data);
	}

	public function edit(Request $request, $id){
		$data = Carousel::find($id);
		if ($request->isMethod('post')) {
			$formdata = $this->process_post_data($this->param, $request, ['tw','en']);

			$res = Carousel::updateOrCreate(['id'=>$id], $formdata['tw']);
			
			$res = Carousel::updateOrCreate(['parent_id'=>$id, 'lang'=>'en'], $formdata['en']);
			if ($res) {
				$this->js_output_and_redirect('編輯成功', 'mgr.carousel');
			} else {
				$this->js_output_and_back('編輯發生錯誤');
			}
			exit();
		}

		$this->data['title'] = "編輯 ".$data->title;
		$this->data['parent'] = "輪播圖";
		$this->data['parent_url'] = route('mgr.carousel');
		$this->data['action'] = route('mgr.carousel', ['id'=>$id]);
		$this->data['submit_txt'] = '確認編輯';

		$data = $data->toArray();
        $data_en = Carousel::where(['parent_id'=>$data['id'], 'lang'=>'en'])->first()->toArray();
        $lang_data = array(
            'tw'    =>  $data,
            'en'    =>  $data_en
        );
		$this->data['params'] = $this->generate_param_to_view($this->param, $lang_data, false, ['tw', 'en']);

		return view('mgr/template_form', $this->data);
	}

    public function del(Request $request){
        $id = $request->post('id');
        
        $obj = Carousel::find($id);
        if ($obj->delete()) {
            $this->output(TRUE, "Delete success");
        }else{
            $this->output(FALSE, "Delete fail");
        }
    }
}
