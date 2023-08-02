<?php

namespace App\Http\Controllers\Mgr;

use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\User;
use App\Models\Order;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use App\Exports\CompanyExport;
use Excel;
class ReportController extends Mgr
{
    
	public function __construct()
	{
		parent::__construct();
		$this->data['active'] = 'REPORT';
		$this->data['sub_active'] = 'REPORT';
		
	}

	public function index(){
		die();
	}

	public function stock(Request $request, $action = false)
	{
		$can_order_fields = [3];
		$order_column = ["", "", "", "remaining"];
		$th_title = [
			['#', '', ''],
			['產品', '', ''],
			['庫存', '', ''],
			['庫存狀態', '', ''],
			['', '', '']
		];
		if ($action == 'data') {
			$order     = $request->post('order')??3;
			$direction = $request->post('direction')??'DESC';
			
			$order_by = 'remaining';
			if (array_key_exists($order, $order_column) && $order_column[$order] != '') {
				$order_by = $order_column[$order];
			}
			$products = Product::list('tw', false, false, false, false, false, 1, $order_by, $direction);

			$html = '';
			foreach ($products as $product) {
				$obj = array();
				$obj[] = $product->id;
				$obj[] = '<a href="'.route('mgr.product.edit', ['id'=>$product->id]).'">'.$product->name.'</a>';
				$obj[] = $product->quota;

				$status = '<span class="text text-success">正常</span>';
				if ($product->quota <= 0) {
					$status = '<span class="text text-danger">缺貨</span>';
				}else if ($product->quota <= $product->min_stock) {
					$status = '<span class="text text-info">低庫存</span>';
				}
				$obj[] = $status;

				$priv_edit = FALSE;
				$priv_del = FALSE;
				$other_btns = array();
				$html .= view('mgr/items/template_item', [
					'item'      => array(
						"id"         => $product->id,
						"data"       => $obj,
						"other_btns" => $other_btns,
						"priv_edit"  => $priv_edit,
						"priv_del"   => $priv_del
					),
					'th_title'  => $this->th_title_field($th_title)
				])->render();
			}

			$this->output(TRUE, 'success', array(
				'html'	=>	$html
			));
			exit();
		}
		$this->data['can_order_fields'] = $can_order_fields;
		$this->data['default_order_column'] = 3;
		$this->data['order_direction'] = 'DESC';

		$this->data['sub_active'] = 'REPORT_STOCK';
		$this->data['controller'] = 'report/stock';
		$this->data['title'] = "產品庫存";
		$this->data['parent'] = "";
		$this->data['parent_url'] = "";
		$this->data['th_title'] = $this->th_title_field($th_title);
		$this->data['btns'] = [
			// ['<i class="ri-add-fill"></i>', '新增會員', route('mgr.users.add'), 'primary']
		];
		$this->data['is_search'] = false;

		return view('mgr/template_list_ajax', $this->data);
	}

	public function company(Request $request, $action = false)
	{
		$th_title = [
			['統編', '', ''],
			['廠商公司名', '', ''],
			['帳號數量', '', ''],
			['訂單數量', '', ''],
			['', '', ''],
		];
		if ($action == 'data' || $action == 'export') {
			$page      = $request->post('page')??'';
			$search    = $request->post('search')??'';
			$users = User::companyList($search);

			$html = '';
			foreach ($users as $user) {
				$obj = array();
				$obj[] = $user['tax_id'];
				$obj[] = $user['company'];
				
				$users_acc = $user['cnt']."筆帳號";
				foreach ($user['users'] as $u) {
					$users_acc .= "<br><a href='".route('mgr.users.edit', ['id'=>$u->id])."'>".$u->email."</a> &nbsp;&nbsp;".$u->username;
				}
				$obj[] = $users_acc;
				$obj[] = $user['bill_cnt'];

				$priv_edit = FALSE;
				$priv_del = FALSE;
				$other_btns = array();
				$html .= view('mgr/items/template_item', [
					'item'      => array(
						"id"         => $user['tax_id'],
						"data"       => $obj,
						"other_btns" => $other_btns,
						"priv_edit"  => $priv_edit,
						"priv_del"   => $priv_del
					),
					'th_title'  => $this->th_title_field($th_title)
				])->render();
			}

			if ($action == 'data') {
				$this->output(TRUE, 'success', array(
					'html'	=>	$html
				));
			}else if ($action == 'export') {
				$data = array();

				foreach ($users as $user) {
					$users_acc = $user['cnt']."筆帳號";
					foreach ($user['users'] as $u) {
						$users_acc .= "\n".$u->email."  (".$u->username.")";
					}
					$bill_cnt = '0';
					if(!is_null($user['bill_cnt']) && is_numeric($user['bill_cnt']) && intval($user['bill_cnt']) > 0) $bill_cnt = $user['bill_cnt'];
					$data[] = array(
						'統編'      => $user['tax_id'],
						'廠商公司名' => $user['company'],
						'帳號數量'   => $users_acc,
						'訂單數量'	 => $bill_cnt
					);
				}

				$collect = collect([
					'data'=>$data
				]);
				$filename = "廠商報表_".date('mdHis');
				return Excel::download(new CompanyExport($collect, $filename), $filename.'.xlsx');
			}
			
			exit();
		}
		$this->data['sub_active'] = 'REPORT_COMPANY';
		$this->data['controller'] = 'report/company';
		$this->data['title'] = "廠商列表";
		$this->data['parent'] = "";
		$this->data['parent_url'] = "";
		$this->data['th_title'] = $this->th_title_field($th_title);
		$this->data['btns'] = [
			// ['<i class="ri-add-fill"></i>', '新增會員', route('mgr.users.add'), 'primary']
		];
		$this->data['is_search'] = true;
		
		$this->data['bar_btns'] = [
			['下載廠商報表', 'window.open(\''.route('mgr.report.company', ['action'=>'export']).'\');', 'primary', '2']
		];

		return view('mgr/template_list_ajax', $this->data);
	}

	public function collection(Request $request, $action = false)
	{
		$charts = array(
			[
				'title'      => "產品",
				'id'         => "product",
				'layout'     => 6,
				'type'       => "bar",
				'horizontal' => true,
				'label'      => "數量",
				'height'     => 350
			],
			[
				'title'      => "產業主類別",
				'id'         => "category",
				'layout'     => 6,
				'type'       => "bar",
				'horizontal' => false,
				'label'      => "數量",
				'height'     => 350
			],
			[
				'title'      => "產業子類別",
				'id'         => "classify",
				'layout'     => 6,
				'type'       => "bar",
				'horizontal' => false,
				'label'      => "數量",
				'height'     => 350
			],
			[
				'title'      => "功能類別",
				'id'         => "functional",
				'layout'     => 6,
				'type'       => "bar",
				'horizontal' => false,
				'label'      => "數量",
				'height'     => 350
			]
		);
		if ($action == 'data') {
			foreach ($charts as $index => $chart) {
				if ($chart['id'] == 'product') {
					$items = CartItem::selectRaw(DB::raw('product_id, MAX(name) as name, COUNT(*) AS cnt'))
									->groupBy('product_id')
									->get();
					$data = array();
					$title = array();
					foreach ($items as $item) {
						$data[] = array(
							"x"	=>	$item['name'],
							"y"	=>	$item['cnt']
						);
						$title[] = $item['name'];
					}					
					$charts[$index]['data'] = $data;
					$charts[$index]['labels'] = $title;
				}else if ($chart['id'] == 'category') {
					$items = CartItem::selectRaw(DB::raw('MAX(product_categories.title) as name, COUNT(*) AS cnt'))
									->join('products', 'products.id', '=', 'product_id')
									->join('product_classify_related', 'product_classify_related.product_id', '=', 'products.id')
									->join('product_classifies', 'product_classifies.id', '=', 'product_classify_related.classify_id')		
									->join('product_categories', 'product_categories.id', '=', 'product_classifies.category_id')
									->groupBy('product_categories.id')
									->get();
					$data = array();
					$title = array();
					foreach ($items as $item) {
						$data[] = $item['cnt'];
						$title[] = $item['name'];
					}					
					$charts[$index]['data'] = $data;
					$charts[$index]['labels'] = $title;
				}else if ($chart['id'] == 'classify') {
					$items = CartItem::selectRaw(DB::raw('MAX(product_classifies.title) as name, COUNT(*) AS cnt'))
									->join('products', 'products.id', '=', 'product_id')
									->join('product_classify_related', 'product_classify_related.product_id', '=', 'products.id')
									->join('product_classifies', 'product_classifies.id', '=', 'product_classify_related.classify_id')
									->groupBy('product_classifies.id')
									->get();
					$data = array();
					$title = array();
					foreach ($items as $item) {
						$data[] = $item['cnt'];
						$title[] = $item['name'];
					}					
					$charts[$index]['data'] = $data;
					$charts[$index]['labels'] = $title;
				}else if ($chart['id'] == 'functional') {
					$items = CartItem::selectRaw(DB::raw('MAX(product_functions.title) as name, COUNT(*) AS cnt'))
									->join('products', 'products.id', '=', 'product_id')
									->join('product_function_related', 'product_function_related.product_id', '=', 'products.id')
									->join('product_functions', 'product_functions.id', '=', 'product_function_related.function_id')
									->groupBy('product_functions.id')
									->get();
					$data = array();
					$title = array();
					foreach ($items as $item) {
						$data[] = $item['cnt'];
						$title[] = $item['name'];
					}					
					$charts[$index]['data'] = $data;
					$charts[$index]['labels'] = $title;
				}
			}

			$this->output(TRUE, 'success', array(
				'data'	=>	$charts
			));
			exit();
		}
		$this->data['sub_active'] = 'REPORT_COLLECTION';
		$this->data['controller'] = 'report/collection';
		$this->data['title'] = "訂單參數統計";
		$this->data['parent'] = "";
		$this->data['parent_url'] = "";
		$this->data['btns'] = [
			// ['<i class="ri-add-fill"></i>', '新增會員', route('mgr.users.add'), 'primary']
		];
		$this->data['is_search'] = false;
		
		$this->data['charts'] = $charts;

		return view('mgr/chart', $this->data);
	}


	public function bill(Request $request, $action = false)
	{
		$y_label = array();
		$start_date = date('Y')."-01-01";
		$end_date = date('Y')."-12-31";
		for ($i=1; $i <= 12 ; $i++) { 
			$ym = date('Y').'-'.str_pad($i, 2, '0', STR_PAD_LEFT);
			$y_label[$ym] = date('M', strtotime($ym.'-01'));
		}
		$charts = array(
			[
				'title'      => "訂單",
				'id'         => "bill",
				'layout'     => 12,
				'type'       => "mix",
				'horizontal' => false,
				'label'      => "數量",
				'height'     => 480,
				'x_labels'   => [
					[
						'name'  => '訂單數量',
						'color' => '#038edc',
						'type'  => 'column'
					],
					[
						'name'  => '總營收(已收款)',
						'color' => '#00bd9d',
						'type'  => 'line'
					],
					[
						'name'  => '應收款',
						'color' => 'rgba(240, 101, 72, 0.6)',
						'type'  => 'line'
					]
				],
				'labels' 	 => array_values($y_label)

			]
		);
		if ($action == 'data') {
			foreach ($charts as $index => $chart) {
				if ($chart['id'] == 'bill') {
					$orders = Order::whereBetween('created_at', [$start_date." 00:00:00", $end_date." 23:59:50"])->get();
					
					$order_data = $y_label;
					foreach ($order_data as $key => $value) $order_data[$key] = array(
						'cnt'        => 0,
						'income'     => 0,
						'receivable' => 0
					);

					foreach ($orders as $order) {
						$ym = substr($order['created_at'],0,7);
						
						if (array_key_exists($ym, $order_data)) {
							$order_data[$ym]['cnt']++;

							if ($order['status'] == 'complete') {
								$order_data[$ym]['income'] += $order['price'];
							}else{
								$order_data[$ym]['receivable'] += $order['price'];
							}
						}	
					}

					$data = array();
					foreach ($chart['x_labels'] as $xindex => $x_label) {
						$x_label['data'] = [];

						if ($xindex == 0) {
							foreach ($order_data as $ym => $val) {
								$x_label['data'][] = $val['cnt'];
							}
						}else if($xindex == 1){
							foreach ($order_data as $ym => $val) {
								$x_label['data'][] = $val['income'];
							}
						}else if($xindex == 2){
							foreach ($order_data as $ym => $val) {
								$x_label['data'][] = $val['receivable'];
							}
						}

						$data[] = $x_label;
					}		

					$charts[$index]['data'] = $data;
				}
			}

			$this->output(TRUE, 'success', array(
				'data'	=>	$charts
			));
			exit();
		}
		$this->data['sub_active'] = 'REPORT_BILL';
		$this->data['controller'] = 'report/bill';
		$this->data['title'] = "訂單統計";
		$this->data['parent'] = "";
		$this->data['parent_url'] = "";
		$this->data['btns'] = [
			// ['<i class="ri-add-fill"></i>', '新增會員', route('mgr.users.add'), 'primary']
		];
		$this->data['is_search'] = false;
		
		$this->data['charts'] = $charts;


		$this->data['bar_btns'] = [
			['下載訂單報表', 'window.open(\''.route('mgr.order.export').'\');', 'primary', '2']
		];

		return view('mgr/chart', $this->data);
	}
}