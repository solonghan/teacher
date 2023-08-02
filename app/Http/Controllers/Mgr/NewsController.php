<?php

namespace App\Http\Controllers\Mgr;

use Illuminate\Http\Request;
use App\Models\News;
use Illuminate\Support\Facades\Storage;
use App\Models\Tags;
use App\Models\Media;

class NewsController extends Mgr
{
    
	public function __construct()
	{
		parent::__construct();
		$this->data['active'] = 'NEWS';
		$this->data['sub_active'] = 'NEWS';

        $this->data['select']['category'] = News::category();
		$this->data['select']['tags'] = Tags::where('lang','tw')->get()->toArray();
	}

	private $param = [
		['封面圖',	    'cover',       'image',   TRUE, '比例 360:230', 6, 12, '', [360/230]],
        ['小圖',	    'thumb',       'image',   TRUE, '比例 3:2', 6, 12, '', [3/2]],
		['內頁多圖',	'pics',			'img_multi',   TRUE, '建議比例 12:5', 12, 12, '', [12/5]],
        ['上線時間',	'online_date',	     'day', TRUE, '', 4, 12, ''],
		['下線時間',	'offline_date',	     'day', TRUE, '', 4, 12, ''],
        ['公開顯示日期',  'date',        'day',   TRUE, '', 4, 12, ''],

		['標題',        'title',        'text',   TRUE, '', 6, 12, ''],
        ['類型',        'category',      'select',   TRUE, '', 6, 12, '', ['id','text']],
        ['標籤',		'tags',			'select_multi',		FALSE, '', 6, 12, '', ['id', 'title']],
		['摘要',	    'summary',     'textarea',   TRUE, '', 12, 12, '', [200]],
		['內文',	    'content',     'editor', TRUE, '', 12, 12, '', [200]],
		
	];
	public function index(Request $request, $lang = 'tw')
	{
        $this->data['sub_active'] = 'NEWS_'.strtoupper($lang);
		$this->data['controller'] = 'news';
		$this->data['title'] = "最新消息";
        if ($lang == 'tw') {
            $this->data['title'] .= "(中文)";
        }else if ($lang == 'en') {
            $this->data['title'] .= "(英文)";
        }
		$this->data['parent'] = "";
		$this->data['parent_url'] = "";
		$this->data['th_title'] = $this->th_title_field(
			[
				['#', '', ''],
				['封面圖', '', ''],
				['小圖', '', ''],
				['公開顯示日期/標題/摘要', '350px', ''],
                ['狀態/發佈時間', '', ''],
				['動作', '', '']
			]
		);
        $this->data['btns'] = [
			['<i class="ri-add-fill"></i>', '新增最新消息', route('mgr.news.add').'/'.$lang, 'primary']
		];

		$this->data['template_item'] = 'mgr/items/news_item';

		$this->data['data'] = News::where("lang", $lang)->get();

		return view('mgr/template_list', $this->data);
	}

	public function add(Request $request, $lang = 'tw'){
        $this->data['sub_active'] = 'NEWS_'.strtoupper($lang);
		if ($request->isMethod('post')) {
			$formdata = $this->process_post_data($this->param, $request);

            $formdata['lang'] = $lang;
			$res = News::updateOrCreate($formdata);

			if ($res) {
				Media::save_media('news_cover', $res->id, $formdata['cover'].";");
				Media::save_media('news_thumb', $res->id, $formdata['thumb'].";");
				Media::save_media('news_pics', $res->id, $request->post('pics'), $request->post('picdeleted_pics'));

				$res->tags_refresh($request->post('tags'));

				$this->js_output_and_redirect('新增成功', 'mgr.news.'.$lang);
			} else {
				$this->js_output_and_back('新增發生錯誤');
			}
			exit();
		}

		$this->data['title'] = "新增最新消息";
        if ($lang == 'tw') {
            $this->data['title'] .= "(中文)";
        }else if ($lang == 'en') {
            $this->data['title'] .= "(英文)";
        }
		$this->data['parent'] = "最新消息";
		$this->data['parent_url'] = route('mgr.news.'.$lang);
		$this->data['action'] = route('mgr.news.add');
		$this->data['submit_txt'] = '確認新增';

		$this->data['params'] = $this->generate_param_to_view($this->param);

		return view('mgr/template_form', $this->data);
	}

	public function edit(Request $request, $id){
		$data = News::find($id);
        $lang = $data->lang;
        $this->data['sub_active'] = 'NEWS_'.strtoupper($lang);

		if ($request->isMethod('post')) {
			$formdata = $this->process_post_data($this->param, $request);

			$res = News::updateOrCreate(['id'=>$id], $formdata);
			if ($res) {
				Media::save_media('news_cover', $id, $formdata['cover'].";");
				Media::save_media('news_thumb', $id, $formdata['thumb'].";");
				Media::save_media('news_pics', $id, $request->post('pics'), $request->post('picdeleted_pics'));

				$res->tags_refresh($request->post('tags'));

				$this->js_output_and_redirect('編輯成功', 'mgr.news.'.$lang);
			} else {
				$this->js_output_and_back('編輯發生錯誤');
			}
			exit();
		}

		$this->data['title'] = "編輯 ".$data->title;
        if ($lang == 'tw') {
            $this->data['title'] .= "(中文)";
        }else if ($lang == 'en') {
            $this->data['title'] .= "(英文)";
        }

		$this->data['parent'] = "最新消息";
		$this->data['parent_url'] = route('mgr.news.'.$lang);
		$this->data['action'] = route('mgr.news.edit', ['id'=>$id]);
		$this->data['submit_txt'] = '確認編輯';
		
		$tags = $data->tags_array();
		
		$data = $data->toArray();

		$pics = Media::fetch_to_generate_template('pics', 'news_pics', $id);
		$data['pics'] = $pics['value'];
		$this->data['html']['pics'] = $pics['html'];
		$data['tags'] = $tags;
		$this->data['params'] = $this->generate_param_to_view($this->param, $data);

		return view('mgr/template_form', $this->data);
	}

    public function del(Request $request){
        $id = $request->post('id');
        
        $obj = News::find($id);
        if ($obj->delete()) {
            $this->output(TRUE, "Delete success");
        }else{
            $this->output(FALSE, "Delete fail");
        }
    }
}
