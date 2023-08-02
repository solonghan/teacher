<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductClassify;
use App\Models\AgencyBrand;
use App\Models\ProductFunction;
use App\Models\Tags;
use App\Models\Media;
use App\Models\PageBanner;

class ProductController extends BaseController
{
	public function __construct(){
		parent::__construct();
	}

	public function data(Request $request){
		$classify = ($request->post('classify'))?explode(',', $request->post('classify')):FALSE;
		$brand = ($request->post('brand'))?explode(',', $request->post('brand')):FALSE;
		$func = ($request->post('function'))?explode(',', $request->post('function')):FALSE;
		$search = ($request->post('search'))?$request->post('search'):FALSE;
		
		$data = Product::list($this->locale, $classify, $brand, $func, $search);
		$list_html = '';
		$grid_html = '';
		foreach ($data as $item) {
			$arr = [
				'id'      => $item->id,
				'cover'   => $item->cover,
				'name'   => $item->name,
				'summary' => $item->summary
			];
			$list_html .= view('items.products_list_item', $arr)->render();
			$grid_html .= view('items.products_grid_item', $arr)->render();
		}
		$this->output(TRUE, "Success", [
			'list'	=>	$list_html,
			'grid'	=>	$grid_html
		]);
	}
	
    public function index(Request $request){
        $this->data['title'] = __('page.products');
		$this->data['show_title'] = __('page.products');

		$this->data['category_list'] = ProductCategory::where('lang', $this->locale)->with('classify')->get();
		$this->data['brand_list'] = AgencyBrand::list($this->locale);
		$this->data['function_list'] = ProductFunction::list($this->locale);
		$this->data['page_banner'] = PageBanner::data('product', $this->locale);
        return view('products', $this->data);
    }

    public function search(Request $request, $keyword){
        $this->data['title'] = __('page.search').": ".$keyword;
		$this->data['show_title'] = __('page.search').": ".$keyword;

		$this->data['data'] = $data = Product::search($keyword, $this->locale);

        return view('search', $this->data);
    }

	public function detail(Request $request, $id){
		$data = Product::data($id, $this->locale);
		$this->data['title'] = $data->name;
		$this->data['show_title'] = $data->name;
		$this->data['parent'] = __('page.products');
		$this->data['parent_link'] = route('products');
		$this->data['data'] = $data;

		$this->data['category_list'] = ProductCategory::where('lang', $this->locale)->with('classify')->get();
		foreach ($this->data['category_list'] as $category) {
			$category->selected = false;
			foreach ($category->classify as $classify) {
				$classify->selected = false;
				foreach ($data->classify as $c) {
					if ($c->id == $classify->id) {
						$category->selected = true;
						$classify->selected = true;
					}
				}
			}
		}
		$this->data['brand_list'] = AgencyBrand::list($this->locale);
		foreach ($this->data['brand_list'] as $item) {
			$item->selected = false;
			foreach ($data->brand as $b) {
				if ($b->id == $item->id) $item->selected = true;
			}
		}
		$this->data['function_list'] = ProductFunction::list($this->locale);
		foreach ($this->data['function_list'] as $item) {
			$item->selected = false;
			foreach ($data->functional as $f) {
				if ($f->id == $item->id) $item->selected = true;
			}
		}
		$range = array(
			'range'	=>	array(),
			'price'	=>	array(),
			'max'	=>	0
		);
		$product_range = array();
		$default_price = 0;
		if (Auth::check()) {
			// $product_range = $data->price['data'];
			$priceR = Product::price($id);
			$product_range = $priceR['range'];
			$range['max'] = $priceR['max'];
			foreach ($product_range as $index => $price) {
				if ($price['range_start'] == 0) continue;
				$show_price = (isset($price['price_new']))?$price['price_new']:$price['price'];
				if (Auth::user()->role != 'new') $show_price = $price['price_old'];

				if ($index == 0) $default_price = $show_price;
				$range['price'][] = $show_price;
				$range['range'][] = $price['range_start'];
			}		
		}
		$this->data['range'] = $range;
		$this->data['product_range'] = $product_range;
		$this->data['default_price'] = $default_price;
		return view('product-detail', $this->data);
	}
}
