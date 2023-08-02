<?php

namespace App\Http\Controllers\Mgr;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Request;
use App\Models\Product;
use App\Models\ProductClassify;
use App\Models\AgencyBrand;
use App\Models\ProductFunction;
use App\Models\ProductPackage;
use App\Models\ProductManager;
use App\Models\ProductWeight;
use App\Models\Tags;
use App\Models\Media;
use App\Models\Member;

class ProductController extends Mgr
{
	private $param = [
		
		['產品編號',		'no',			'text',   TRUE, '', 2, 12, ''],
		['名稱(中文)',		'name',			'text',   TRUE, '', 5, 12, ''],
        ['名稱(EN)',		'name_en',		'text',   TRUE, '', 5, 12, ''],
        ['產業類別',		'classify',	'select_multi',   TRUE, '', 4, 12, '', ['id','title']],
        ['廠牌類別',		'brand',		'select_multi',   FALSE, '', 4, 12, '', ['id','name']],
		['功能類別',		'function',	'select_multi',   TRUE, '', 4, 12, '', ['id','title']],
		['摘要(中文)',		'summary',		'textarea',   FALSE, '', 6, 12, ''],
		['摘要(EN)',		'summary_en',	'textarea',   FALSE, '', 6, 12, ''],
		['描述(中文)',		'des',			'editor',   FALSE, '', 6, 12, '', [200]],
		['描述(EN)',		'des_en',		'editor',   FALSE, '', 6, 12, '', [200]],

		['預估庫存',		'quota',		'number',   TRUE, '', 2, 12, ''],
		['庫存最低安全值',	 'min_stock',		'number',   FALSE, '', 2, 12, ''],
		['包裝單位',		'package',		'select',		TRUE, '', 2, 12, '', ['id', 'title']],
		['重量單位',		'weight',		'select',		TRUE, '', 2, 12, '', ['id', 'title']],
		['每單位重量',		'unit',			'number',		TRUE, '', 2, 12, ''],
		
		['下單方式',		'order_type',		'select',		FALSE, '', 3, 12, '', ['id', 'text']],
        ['專人詢價自定義提示','custom_hint',	'text',   FALSE, '', 9, 12, ''],
		
		['標籤',			'tags',			'select_multi',		FALSE, '', 6, 12, '', ['id', 'title']],
		
        ['指派業務',        'manager',        'select',   TRUE, '', 3, 12, '', ['id', 'username']],
		['指派業助',        'assistant',        'select',   FALSE, '', 3, 12, '', ['id', 'username']],

		['產品主圖',		'cover',		'image',   TRUE, '建議比例 3:2', 12, 12, '', [3/2]],
		['其它產品圖',		'pics',			'img_multi',   FALSE, '建議比例 3:2', 12, 12, '', [3/2]],
		['商品規格表',		'files',		'file',   FALSE, '', 12, 12, ''],
	];
	public function __construct()
	{
		parent::__construct();
		$this->data['active'] = 'PRODUCT';
		$this->data['sub_active'] = 'PRODUCT';

		$this->data['select']['manager'] = Member::where('id', '!=', 1)->get()->toArray();
		array_unshift($this->data['select']['manager'], array(
			"id"       => 0,
			"username" => "尚未指派"
		));

		$this->data['select']['assistant'] = Member::where('id', '!=', 1)->where('role', 'assistant')->get()->toArray();
		array_unshift($this->data['select']['assistant'], array(
			"id"       => 0,
			"username" => "尚未指派"
		));

		$this->data['precheck_url'] = route('mgr.product.check');

		$this->data['select']['order_type'] = array(
			array("id"=>"normal", "text"=>"一般模式, 以預估庫存為依據"),
			array("id"=>"custom", "text"=>"專人詢價"),
		);

        $this->data['select']['classify'] = array();
        foreach (ProductClassify::where('lang','tw')->with('category')->get() as $item) {
            $this->data['select']['classify'][] = array(
                "id"    =>  $item->id,
                "title" =>  $item->category->title.">".$item->title
            );
        }

        $this->data['select']['brand'] = AgencyBrand::where('lang','tw')->get()->toArray();
        $this->data['select']['function'] = ProductFunction::where('lang','tw')->get()->toArray();
		$this->data['select']['tags'] = Tags::where('lang','tw')->get()->toArray();
		$this->data['select']['package'] = ProductPackage::where('lang','tw')->get()->toArray();
		$this->data['select']['weight'] = ProductWeight::get()->toArray();

		$this->param[] = ['價格設定',		'',				'header',   FALSE, '', 12, 12, ''];
		$this->param[] = ['',	'',	'plain',   FALSE, '折數未輸入，則為手動輸入絕對價格', 7, 12, '', 'line-height:65px;color:red; float:right;'];
		$this->param[] = ['新會員價折數',	'price_new_percent',				'number',   FALSE, '0~1', 2, 12, 'new_discount'];
		$this->param[] = ['舊會員價折數',	'price_old_percent',				'number',   FALSE, '0~1', 2, 12, 'old_discount'];
		$this->param[] = ['',						'',						'plain',   FALSE, '', 1, 12, ''];
		for ($i=1; $i <= 10 ; $i++) { 
			$title_style = 'line-height:36px; float:right;';
			if ($i == 1) $title_style = 'line-height:65px; float:right;';

			$this->param[] = ['',						'',						'plain',   FALSE, '級距'.$i, 1, 12, '', $title_style];
			$this->param[] = [($i==1)?'級距起':'',		'range_start'.$i,		'number',   FALSE, 'KG', 2, 12, ''];
			$this->param[] = [($i==1)?'級距迄':'',		'range_end'.$i,			'number',   FALSE, 'KG', 2, 12, ''];
			$this->param[] = [($i==1)?'市價':'',		'price'.$i,				'number',   FALSE, '$', 2, 12, 'price price'.$i];
			$this->param[] = [($i==1)?'新會員價':'',	 'price_new'.$i,		'number',   FALSE, '$', 2, 12, 'price_new price_new'.$i];
			$this->param[] = [($i==1)?'舊會員價':'',	 'price_old'.$i,		'number',   FALSE, '$', 2, 12, 'price_old price_old'.$i];
			$this->param[] = ['',						'',						'plain',   FALSE, '', 1, 12, ''];
		}
	}
        
	public function index(Request $request)
	{
		$this->data['controller'] = 'product';
		$this->data['title'] = "產品管理";
		$this->data['parent'] = "";
		$this->data['parent_url'] = "";
		$this->data['th_title'] = $this->th_title_field(
			[
				['#', '', ''],
				['產品主圖', '', ''],
				['產品編號/名稱(中文)', '', ''],
				['目前庫存/安全值', '', ''],
                // ['主類別名稱(EN)', '', ''],
				['建立時間', '', ''],
				['動作', '', '']
			]
		);
		$this->data['btns'] = [
			['<i class="ri-add-fill"></i>', '新增產品', route('mgr.product.add'), 'primary']
		];

		$this->data['template_item'] = 'mgr/items/template_item';

		$data = Product::where("lang", 'tw')->get();
		
        $this->data['data'] = array();
        foreach ($data as $item) {
			$inventory = $item->quota;
			if ($item->min_stock > 0) {
				if ($item->quota < $item->min_stock) {
					$inventory = '<span class="text text-danger">'.$item->quota.'</span><br><small class="text text-muted">'.$item->min_stock.'</small>';
				}else{
					$inventory = $item->quota.'<br><small class="text text-muted">'.$item->min_stock.'</small>';
				}
			}
            $this->data['data'][] = array(
                "id"    =>  $item->id,
                "data"  =>  array(
                    $item->id,
					'<img src="'.env('APP_URL').Storage::url($item->cover).'" class="img-thumbnail" style="width:120px;">',
                    $item->no."<br>".$item->name,
                    $inventory,
                    $item->created_at
                )
            );
        }

		return view('mgr/template_list', $this->data);
	}

	public function check(Request $request, $id = false){
		if ($request->isMethod('post')) {
			$formdata = $this->process_post_data($this->param, $request, ['tw', 'en'], true, 'alert');
			$alert = (array_key_exists('alert', $formdata))?$formdata['alert']:'';

			if ($id === false) {
				$exist = Product::where('lang','tw')->where('name', '=', $formdata['tw']['name'])->count();
				
				if ($exist > 0) $alert .= "產品名稱已重覆\n";
			}else{
				$exist = Product::where('lang','tw')->where('name', $formdata['tw']['name'])->where("id", "!=", $id)->count();
				
				if ($exist > 0) $alert .= "產品名稱已重覆\n";
			}
			

			$pre_range = 0;
			$pre_index = 0;
			for ($i=1; $i <= 10 ; $i++) { 
				$range_start = intval($formdata['tw']['range_start'.$i]);
				$range_end   = intval($formdata['tw']['range_end'.$i]);
				$price       = intval($formdata['tw']['price'.$i]);
				$price_new   = intval($formdata['tw']['price_new'.$i]);
				$price_old   = intval($formdata['tw']['price_old'.$i]);
				if ($i == 1) {
					if ($range_start <= 0) $alert .= "【價格級距1】 須大於0"."\n";
					if ($range_end <= 0 || $range_end <= $range_start) "【價格級距1】 級距迄須大於級距起"."\n";
					if ($price <= 0) $alert .= "【價格級距1】 市價須大於0"."\n";
					if ($price_new <= 0) $alert .= "【價格級距1】 新會員價須大於0"."\n";
					if ($price_old <= 0) $alert .= "【價格級距1】 舊會員價須大於0"."\n";
					$pre_index = 1;
				}else{
					if ($range_start > 0) {
						if ($pre_index != $i - 1) $alert .= "【價格級距】不可跳順序"."\n";
						if ($range_start < $pre_range) $alert .= "【價格級距".$i."】 起始KG 不可小於前一級距"."\n";
						if ($range_start != $pre_range + 1) $alert .= "【價格級距".$i."】 起始KG 須為上一級距迄+1 "."\n";
						if ($range_start > $range_end) $alert .= "【價格級距".$i."】 級距迄須大於級距起"."\n";
						if ($price <= 0) $alert .= "【價格級距".$i."】 市價須大於0"."\n";
						if ($price_new <= 0) $alert .= "【價格級距".$i."】 新會員價須大於0"."\n";
						if ($price_old <= 0) $alert .= "【價格級距".$i."】 舊會員價須大於0"."\n";
						$pre_index = $i;
					}
				}
				$pre_range = $range_end;
			}

			if ($alert != '') {
				$this->output(FALSE, $alert);
			}else{
				$this->output(TRUE, "Check Success");
			}
		}
	}

	public function add(Request $request){
		if ($request->isMethod('post')) {
			$formdata = $this->process_post_data($this->param, $request, ['tw', 'en'], false);

			$unset_field = ['price_new_percent', 'price_old_percent', 'manager'];
			$price = array(
				"price_new_percent" => $formdata['tw']['price_new_percent'],
				"price_old_percent" => $formdata['tw']['price_old_percent'],
				"data"              => array()
			);
			for ($i=1; $i <= 10 ; $i++) { 
				$price['data'][] = array(
					'range_start' => intval($formdata['tw']['range_start'.$i]),
					'range_end'   => intval($formdata['tw']['range_end'.$i]),
					'price'       => intval($formdata['tw']['price'.$i]),
					'price_new'   => intval($formdata['tw']['price_new'.$i]),
					'price_old'   => intval($formdata['tw']['price_old'.$i])
				);
				
				$unset_field[] = 'range_start'.$i;
				$unset_field[] = 'range_end'.$i;
				$unset_field[] = 'price'.$i;
				$unset_field[] = 'price_new'.$i;
				$unset_field[] = 'price_old'.$i;
			}
			$formdata['tw']['price'] = json_encode($price);
			$formdata['en']['price'] = json_encode($price);
			foreach ($unset_field as $u) {
				unset($formdata['tw'][$u]);
				unset($formdata['en'][$u]);
			}
			
			$res = Product::updateOrCreate($formdata['tw']);

			if ($res) {
				if ($request->post("manager") > 0) $res->manager_refresh([$request->post("manager")]);
				
				$product_id = $res->id;
				Media::save_media('product_cover', $product_id, $formdata['tw']['cover'].";");
				Media::save_media('product_pics', $product_id, $request->post('pics'), $request->post('picdeleted_pics'));
				Media::save_media('product_files', $product_id, $request->post('files'), $request->post('filesdeleted_files'));

				//更新多對多的表
				$res->tags_refresh($request->post('tags'));
				$res->classify_refresh($request->post('classify'));
				$res->brand_refresh($request->post('brand'));
				$res->function_refresh($request->post('function'));

				$formdata['en']['parent_id'] = $product_id;
				$formdata['en']['lang'] = 'en';
				Product::updateOrCreate($formdata['en']);

				$this->js_output_and_redirect('新增成功', 'mgr.product');
			} else {
				$this->js_output_and_back('新增發生錯誤');
			}
			exit();
		}

		$this->data['title'] = "新增產品";
		$this->data['parent'] = "產品管理";
		$this->data['parent_url'] = route('mgr.product');
		$this->data['action'] = route('mgr.product.add');
		$this->data['submit_txt'] = '確認新增';

		$this->data['params'] = $this->generate_param_to_view($this->param);
		$this->data['params'][array_search('unit', array_column($this->data['params'], 'field'))]['value'] = 10;
		$this->data['params'][array_search('price_new_percent', array_column($this->data['params'], 'field'))]['value'] = 1;
		$this->data['params'][array_search('price_old_percent', array_column($this->data['params'], 'field'))]['value'] = 1;

		$this->data['custom_js'] = view('mgr/custom_js/product_calc', [])->render();
		
		return view('mgr/template_form', $this->data);
	}

	public function edit(Request $request, $id){
		// $data = Product::where('id', $id)->with('tags')->first();
		$data = Product::find($id);
		
		if ($request->isMethod('post')) {
			$formdata = $this->process_post_data($this->param, $request, ['tw', 'en']);

			$unset_field = ['price_new_percent', 'price_old_percent'];
			$price = array(
				"price_new_percent" => $formdata['tw']['price_new_percent'],
				"price_old_percent" => $formdata['tw']['price_old_percent'],
				"data"              => array()
			);
			for ($i=1; $i <= 10 ; $i++) { 
				$price['data'][] = array(
					'range_start' => intval($formdata['tw']['range_start'.$i]),
					'range_end'   => intval($formdata['tw']['range_end'.$i]),
					'price'       => intval($formdata['tw']['price'.$i]),
					'price_new'   => intval($formdata['tw']['price_new'.$i]),
					'price_old'   => intval($formdata['tw']['price_old'.$i])
				);
				
				$unset_field[] = 'range_start'.$i;
				$unset_field[] = 'range_end'.$i;
				$unset_field[] = 'price'.$i;
				$unset_field[] = 'price_new'.$i;
				$unset_field[] = 'price_old'.$i;
			}
			$formdata['tw']['price'] = json_encode($price);
			$formdata['en']['price'] = json_encode($price);
			foreach ($unset_field as $u) unset($formdata['tw'][$u]);
			unset($formdata['tw']['manager']);
			unset($formdata['en']['manager']);
			$res = Product::updateOrCreate(['id'=>$id], $formdata['tw']);
			Product::updateOrCreate(['parent_id'=>$id, 'lang'=>'en'], $formdata['en']);
			if ($res) {
				//更新圖、檔
				Media::save_media('product_cover', $id, $formdata['tw']['cover'].";");
				Media::save_media('product_pics', $id, $request->post('pics'), $request->post('picdeleted_pics'));
				Media::save_media('product_files', $id, $request->post('files'), $request->post('filesdeleted_files'));

				//更新多對多的表
				$data->tags_refresh($request->post('tags'));
				$data->classify_refresh($request->post('classify'));
				$data->brand_refresh($request->post('brand'));
				$data->function_refresh($request->post('function'));

				if ($request->post("manager") > 0) $data->manager_refresh([$request->post("manager")]);
				
				$this->js_output_and_redirect('編輯成功', 'mgr.product');
			} else {
				$this->js_output_and_back('編輯發生錯誤');
			}
			exit();
		}

		$this->data['title'] = "編輯 ".$data->title;
		$this->data['parent'] = "產品管理";
		$this->data['parent_url'] = route('mgr.product');
		$this->data['action'] = route('mgr.product', ['id'=>$id]);
		$this->data['submit_txt'] = '確認編輯';
		$this->data['id'] = $id;

		$tags = $data->tags_array();
		$classify = $data->classify_array();
		$brand = $data->brand_array();
		$function = $data->function_array();

		$data = $data->toArray();
		$pics = Media::fetch_to_generate_template('pics', 'product_pics', $id);
		$data['pics'] = $pics['value'];
		$files = Media::fetch_to_generate_template('files', 'product_files', $id, 'file');
		$data['files'] = $files['value'];
		//Media: multi img, files
		$this->data['html']['pics'] = $pics['html'];
		$this->data['html']['files'] = $files['html'];

		//tags
		$data['tags'] = $tags;
		$data['classify'] = $classify;
		$data['brand'] = $brand;
		$data['function'] = $function;

		$data['manager'] = 0;
		$m = ProductManager::where(['product_id'=>$id])->get()->toArray();
		if(count($m) > 0) $data['manager'] = $m[0]['member_id'];
        

		//price
		$price = json_decode($data['price'], true);
		$data['price_new_percent'] = $price['price_new_percent']??'1';
		$data['price_old_percent'] = $price['price_old_percent']??'1';

		for ($i=0; $i < 10 ; $i++) { 
			$data['range_start'.($i+1)] = $price['data'][$i]['range_start']??'';
			$data['range_end'.($i+1)] = $price['data'][$i]['range_end']??'';
			$data['price'.($i+1)] = $price['data'][$i]['price']??'';
			$data['price_new'.($i+1)] = $price['data'][$i]['price_new']??'';
			$data['price_old'.($i+1)] = $price['data'][$i]['price_old']??'';
		}

        $data_en = Product::where(['parent_id'=>$data['id'], 'lang'=>'en'])->first()->toArray();
        $lang_data = array(
            'tw'    =>  $data,
            'en'    =>  $data_en
        );
		$this->data['params'] = $this->generate_param_to_view($this->param, $lang_data, false, ['tw','en']);
		$this->data['custom_js'] = view('mgr/custom_js/product_calc', [])->render();

		return view('mgr/template_form', $this->data);
	}

    public function del(Request $request){
        $id = $request->post('id');
        
        $obj = Product::find($id);
        if ($obj->delete()) {
            $this->output(TRUE, "Delete success");
        }else{
            $this->output(FALSE, "Delete fail");
        }
    }


	/*
		前台頁面
	*/
	public function list(Request $request){

	}
}
