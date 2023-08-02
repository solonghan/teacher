<?php

namespace App\Http\Controllers\Mgr;

use Illuminate\Http\Request;
use App\Models\AgencyBrand;
use Illuminate\Support\Facades\Storage;
use App\Models\Media;

class AgencyBrandController extends Mgr
{
	public function __construct()
	{
		parent::__construct();
		$this->data['active'] = 'AGENCY_BRAND';
		$this->data['sub_active'] = 'AGENCY_BRAND';

		$this->data['page_banner'] = '';
	}

	private $param = [
		['LOGO',		'logo',       'image',     TRUE, '比例 1:1', 12, 12, '', [0, false]],
		['公司官網', 	'website',     	'text',   	 FALSE, '', 12, 12, ''],
		['多圖上傳',		'pics',			'img_multi',   FALSE, '建議比例 3:2', 12, 12, '', [3/2]],
		['公司名(中文)', 'name',     	'text',   	 TRUE, '', 12, 12, ''],
		['摘要(中文)',	'summary',     'editor',   FALSE, '', 12, 12, '', [200]],
		['內文(中文)',	'content',     'editor', 	 FALSE, '', 12, 12, '', [200]],
		['公司名(EN)',	'name_en',     'text',   	TRUE, '', 12, 12, ''],
		['摘要(EN)',	'summary_en',  'editor',  FALSE, '', 12, 12, '', [200]],
		['內文(EN)',	'content_en',   'editor',  FALSE, '', 12, 12, '', [200]],
	];
	public function index(Request $request)
	{
		$this->data['controller'] = 'agency_brand';
		$this->data['title'] = "代理品牌";
		$this->data['parent'] = "";
		$this->data['parent_url'] = "";
		$this->data['th_title'] = $this->th_title_field(
			[
				['#', '', ''],
				['品牌名', '', ''],
				['LOGO', '', ''],
				['摘要', '', ''],
				['建立時間', '', ''],
				['動作', '', '']
			]
		);
		$this->data['btns'] = [
			['<i class="ri-add-fill"></i>', '新增品牌', route('mgr.agency_brand.add'), 'primary']
		];

		$this->data['template_item'] = 'mgr/items/brand_item';

		$this->data['data'] = AgencyBrand::list('en', true);

		return view('mgr/template_list', $this->data);
	}

	public function add(Request $request){
		if ($request->isMethod('post')) {
			$formdata = $this->process_post_data($this->param, $request, ['tw', 'en']);

			$res = AgencyBrand::updateOrCreate($formdata['tw']);

			if ($res) {
				$formdata['en']['parent_id'] = $res->id;
				$formdata['en']['lang'] = 'en';
				AgencyBrand::updateOrCreate($formdata['en']);

				Media::save_media('brand_pics', $res->id, $request->post('pics'), $request->post('picdeleted_pics'));

				$this->js_output_and_redirect('新增成功', 'mgr.agency_brand');
			} else {
				$this->js_output_and_back('新增發生錯誤');
			}
			exit();
		}

		$this->data['title'] = "新增品牌";
		$this->data['parent'] = "代理品牌";
		$this->data['parent_url'] = route('mgr.agency_brand');
		$this->data['action'] = route('mgr.agency_brand.add');
		$this->data['submit_txt'] = '確認新增';

		$this->data['params'] = $this->generate_param_to_view($this->param);

		return view('mgr/template_form', $this->data);
	}

	public function edit(Request $request, $id){
		$data = AgencyBrand::find($id);
		if ($request->isMethod('post')) {
			$formdata = $this->process_post_data($this->param, $request, ['tw', 'en']);

			$res = AgencyBrand::updateOrCreate(['id'=>$id], $formdata['tw']);
			if ($res) {
				AgencyBrand::updateOrCreate(['parent_id'=>$id, 'lang'=>'en'], $formdata['en']);

				Media::save_media('brand_pics', $id, $request->post('pics'), $request->post('picdeleted_pics'));

				$this->js_output_and_redirect('編輯成功', 'mgr.agency_brand');
			} else {
				$this->js_output_and_back('編輯發生錯誤');
			}
			exit();
		}

		$this->data['title'] = "編輯 ".$data->title;
		$this->data['parent'] = "代理品牌";
		$this->data['parent_url'] = route('mgr.agency_brand');
		$this->data['action'] = route('mgr.agency_brand', ['id'=>$id]);
		$this->data['submit_txt'] = '確認編輯';

		$data = $data->toArray();
		$pics = Media::fetch_to_generate_template('pics', 'brand_pics', $id);
		$data['pics'] = $pics['value'];
		$this->data['html']['pics'] = $pics['html'];

        $data_en = AgencyBrand::where(['parent_id'=>$data['id'], 'lang'=>'en'])->first()->toArray();
        $lang_data = array(
            'tw'    =>  $data,
            'en'    =>  $data_en
        );
		$this->data['params'] = $this->generate_param_to_view($this->param, $lang_data, false, ['tw','en']);

		return view('mgr/template_form', $this->data);
	}

    public function del(Request $request){
        $id = $request->post('id');
        
        $obj = AgencyBrand::find($id);
        if ($obj->delete()) {
            $this->output(TRUE, "Delete success");
        }else{
            $this->output(FALSE, "Delete fail");
        }
    }
}
