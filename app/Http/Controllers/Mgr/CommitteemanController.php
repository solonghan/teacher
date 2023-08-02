<?php

namespace App\Http\Controllers\Mgr;

// use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\Committeeman;
use App\Models\Member;
use App\Models\Privilege;
use App\Models\MemberDepartment;
use App\Models\Product;
use App\Models\User;
use App\Models\ServiceUnit;
use App\Models\Specialty;
use App\Models\SpecialtyList;
use App\Models\JobTitle;
use App\Models\Source;
use App\Models\Academic;
use App\Models\ChangeRecord;
use App\Models\OtherTitle;
use App\Models\Department;
use Illuminate\Support\Facades\DB;
use  Carbon\Carbon;
use Illuminate\Support\Facades\Log;
// use App\Models\SpecialtyList;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
// use Auth;
use Illuminate\Support\Facades\Auth;
use App\Imports\ProductImport;

// use App\Extend\tcpdf\tcpdf;
use PDF;

use Illuminate\Support\Facades\Input;
use PhpOffice\PhpSpreadsheet\Calculation\Web;
use App\Exports\OrderExport;
use Excel;
use OCILob;
use SebastianBergmann\CodeCoverage\Report\Xml\Unit;

class CommitteemanController extends Mgr
{

	public function __construct()
	{
		parent::__construct();
		
		$this->data['active'] = 'COMMITTEEMAN';
		$this->data['sub_active'] = 'COMMITTEEMAN';
		// $this->data['select']['manager'] = Member::where('id', '!=', 1)->get()->toArray();
		$this->data['select']['now_unit_id'] = ServiceUnit::where('id', '!=', 0)->get()->toArray();
		$this->data['select']['now_unit_id_2'] = ServiceUnit::where('id', '!=', 0)->where('id', '!=', 3)->get()->toArray();

		$this->data['select']['old_unit_id'] = ServiceUnit::where('id', '!=', 0)->get()->toArray();

		$this->data['select']['now_title_id'] = JobTitle::where('id', '!=', 0)->get()->toArray();
		$this->data['select']['old_title_id'] = JobTitle::where('id', '!=', 0)->get()->toArray();


		// $this->data['select']['search_title_id'] = JobTitle::where('title', '!=', '其他')->get()->toArray();

		$this->data['select']['specialty_source'] = Source::where('id', '!=', 0)->get()->toArray();
		$this->data['select']['academic_source'] = Source::where('id', '!=', 0)->get()->toArray();

		$this->data['select']['specialty_id'] = SpecialtyList::where('id', '!=', 0)->get()->toArray();
		$this->data['select']['title_id'] = SpecialtyList::where('id', '!=', 0)->get()->toArray();
		// $this->data['select']['old_unit_id'] = JobTitle::where('id', '!=', 0)->get()->toArray();
		// print_r($this->data['select']['specialty_id'] );exit;

		// $this->data['select']['now_unit_id'] = array(
        //     	array("id"=>"公立學校", "text"=>"公立學校"),
        //     	array("id"=>"研究機構", "text"=>"研究機構"),
        //     	array("id"=>"企業(含私立學校)", "text"=>"企業(含私立學校)"),
        //     );
        $this->data['select']['gender'] = array(
                array("id"=>"all", "text"=>"全部"),
            	array("id"=>"male", "text"=>"男"),
            	array("id"=>"female", "text"=>"女"),
            );
        $this->data['select']['source'] = array(
                array("id"=>"1", "text"=>"教育部"),
            	array("id"=>"2", "text"=>"國科會"),
            	array("id"=>"3", "text"=>"個人網頁"),
                array("id"=>"4", "text"=>"其他"),
            );
		$this->data['select']['specialty'] = array(
				array("id"=>"1", "text"=>"教育學門"),
				array("id"=>"2", "text"=>"藝術學門"),
				array("id"=>"3", "text"=>"人文學門"),
				array("id"=>"4", "text"=>"其他學門"),
			);
		$this->data['select']['specialty_classify'] = array(
				array("id"=>"1", "text"=>"教育學"),
				array("id"=>"2", "text"=>"幼兒師資教育"),
				array("id"=>"3", "text"=>"普通科目師資教育"),
				array("id"=>"3", "text"=>"專業科目師資教育"),
				array("id"=>"4", "text"=>"其他教育"),
			);
		$this->data['select']['edit_date'] = array(
				array("id"=>"1", "text"=>"2023-04-01"),
				array("id"=>"2", "text"=>"2023-04-02"),
				array("id"=>"3", "text"=>"2023-04-03"),
				array("id"=>"3", "text"=>"2023-04-04"),
				array("id"=>"4", "text"=>"2023-04-05"),
			);
		$this->data['select']['job_title'] = array(
			array("id"=>"1", "text"=>"教授"),
			array("id"=>"2", "text"=>"副教授"),
			array("id"=>"3", "text"=>"助理教授"),
			);

		$this->data['select']['status'] = array(
                // array("id"=>"all", "text"=>"全部"),
            	array("id"=>"male", "text"=>"啟用"),
            	array("id"=>"female", "text"=>"關閉"),
            );
		// $this->data['select']['before'] = MemberDepartment::get()->toArray();
		// // $this->data['select']['products'] = Product::get()->toArray();
		// // $this->data['select']['users'] = User::where(['status'=>'normal'])->get()->toArray();

		
	}
	private $edit_param = [

		['姓名',		'username',     		'text',   TRUE, '', 3, 12, ''],
        ['',		    'username',     		'',   TRUE, '', 12, 12, ''],
        ['服務單位',		'now_unit_id',     		'select_button',   TRUE, '', 3, 12, '',['id','title']],
		// ['',		    'username',     		'',   TRUE, '', 12, 12, ''],
		['單位名稱',		'now_unit',     	'text',   TRUE, '請輸入單位名稱', 3, 12, ''],
	
		['職稱',		'now_title_id',     		'select_now',   TRUE, '', 3, 12, '',['id','title']],

		['',		    'username',     		'',   TRUE, '', 12, 12, ''],
		['曾任服務單位~',		'old_unit_id',     		'select_old_unit',   false, '', 3, 12, '',['id','title']],
		// ['',		    'username',     		'',   TRUE, '', 12, 12, ''],
		['單位名稱~',		'old_unit',     	'text_old_unit',   false, '請輸入單位名稱', 3, 12, ''],

		// ['',			'username',     		'',   TRUE, '', 12, 12, ''],
		['職稱~',		'old_title_id',     		'select_old_title',   false, '', 3, 12, '',['id','title']],
		// ['其他',		'other_title',     		'text',   false, '', 3, 12, ''],
		['',			'username',     		'',   TRUE, '', 12, 12, ''],
	
		
        // ['',		'username',     		'',   TRUE, '', 12, 12, ''],
		// ['學門專長',		'specialty_id',     		'select',   TRUE, '', 3, 12, '',['id','text']],
		// ['學門專長資料來源',		'source',     		'select',   TRUE, '', 3, 12, '',['id','text']],
		// ['',		'username',     		'',   TRUE, '', 12, 12, ''],
		// ['學術專長',		'specialty',     		'text',   TRUE, '請輸入學術專長', 3, 12, ''],
		// ['學術專長資料來源',		'source',     		'select',   TRUE, '', 3, 12, '',['id','text']],
		['',		'username',     		'',   TRUE, '', 12, 12, ''],

		['',			'username',     		'',   TRUE, '', 12, 12, ''],
		['連絡電話',	'phone',     		'text',   false, '請輸入連絡電話', 3, 12, ''],
		['',			'username',     		'',   TRUE, '', 12, 12, ''],
		['電子郵件信箱',	'email',     		'text',   false, '請輸入電子郵件信箱', 3, 12, ''],
		['',			'username',     		'',   TRUE, '', 12, 12, ''],
		['相關資料網址',	'url',     		'text',   false, '請輸入相關資料網址', 3, 12, ''],
		['',			'username',     		'',   TRUE, '', 12, 12, ''],
       
	];
	private $edit_param_2 = [

		['姓名',		'username',     		'text',   TRUE, '', 3, 12, ''],
        ['',		    'username',     		'',   TRUE, '', 12, 12, ''],
        ['服務單位',		'now_unit_id',     		'select',   TRUE, '', 3, 12, '',['id','title']],
		// ['',		    'username',     		'',   TRUE, '', 12, 12, ''],
		['單位名稱',		'now_unit',     	'text',   TRUE, '請輸入單位名稱', 3, 12, ''],
		['職稱',		'now_title_id',     		'select_now',   TRUE, '', 3, 12, '',['id','title']],

		['',		    'username',     		'',   TRUE, '', 12, 12, ''],
		['曾任服務單位',		'old_unit_id',     		'select',   false, '', 3, 12, '',['id','title']],
		// ['',		    'username',     		'',   TRUE, '', 12, 12, ''],
		['單位名稱',		'old_unit',     	'text',   false, '請輸入單位名稱', 3, 12, ''],

		// ['',			'username',     		'',   TRUE, '', 12, 12, ''],
		// ['職稱~',		'old_title_id',     		'select_old_title',   false, '', 3, 12, '',['id','title']],
		['職稱',		'old_title_id',     		'select_old',   false, '', 3, 12, '',['id','title']],
		['',			'username',     		'',   TRUE, '', 12, 12, ''],
	
		
        // ['',		'username',     		'',   TRUE, '', 12, 12, ''],
		// ['學門專長',		'specialty_id',     		'select',   TRUE, '', 3, 12, '',['id','text']],
		// ['學門專長資料來源',		'source',     		'select',   TRUE, '', 3, 12, '',['id','text']],
		// ['',		'username',     		'',   TRUE, '', 12, 12, ''],
		// ['學術專長',		'specialty',     		'text',   TRUE, '請輸入學術專長', 3, 12, ''],
		// ['學術專長資料來源',		'source',     		'select',   TRUE, '', 3, 12, '',['id','text']],
		['',		'username',     		'',   TRUE, '', 12, 12, ''],

		['',			'username',     		'',   TRUE, '', 12, 12, ''],
		['連絡電話',	'phone',     		'text',   false, '請輸入連絡電話', 3, 12, ''],
		['',			'username',     		'',   TRUE, '', 12, 12, ''],
		['電子郵件信箱',	'email',     		'text',   false, '請輸入電子郵件信箱', 3, 12, ''],
		['',			'username',     		'',   TRUE, '', 12, 12, ''],
		['相關資料網址',	'url',     		'text',   false, '請輸入相關資料網址', 3, 12, ''],
		['',			'username',     		'',   TRUE, '', 12, 12, ''],
       
	];
	private $edit_param_3 = [

		['姓名',		'username',     		'text',   TRUE, '', 3, 12, ''],
        ['',		    'username',     		'',   TRUE, '', 12, 12, ''],
        ['服務單位',		'now_unit_id',     		'select_button',   TRUE, '', 3, 12, '',['id','title']],
		// ['',		    'username',     		'',   TRUE, '', 12, 12, ''],
		['單位名稱',		'now_unit',     	'text',   TRUE, '請輸入單位名稱', 3, 12, ''],
	
		['職稱',		'now_other_title_id',     		'select_now',   TRUE, '', 3, 12, '',['id','title']],

		['',		    'username',     		'',   TRUE, '', 12, 12, ''],
		['曾任服務單位~',		'old_unit_id',     		'select_old_unit',   false, '', 3, 12, '',['id','title']],
		// ['',		    'username',     		'',   TRUE, '', 12, 12, ''],
		['單位名稱~',		'old_unit',     	'text_old_unit',   false, '請輸入單位名稱', 3, 12, ''],

		// ['',			'username',     		'',   TRUE, '', 12, 12, ''],
		['職稱~',		'old_title_id',     		'select_old_title',   false, '', 3, 12, '',['id','title']],
		['',			'username',     		'',   TRUE, '', 12, 12, ''],
	
		
        // ['',		'username',     		'',   TRUE, '', 12, 12, ''],
		// ['學門專長',		'specialty_id',     		'select',   TRUE, '', 3, 12, '',['id','text']],
		// ['學門專長資料來源',		'source',     		'select',   TRUE, '', 3, 12, '',['id','text']],
		// ['',		'username',     		'',   TRUE, '', 12, 12, ''],
		// ['學術專長',		'specialty',     		'text',   TRUE, '請輸入學術專長', 3, 12, ''],
		// ['學術專長資料來源',		'source',     		'select',   TRUE, '', 3, 12, '',['id','text']],
		['',		'username',     		'',   TRUE, '', 12, 12, ''],

		['',			'username',     		'',   TRUE, '', 12, 12, ''],
		['連絡電話',	'phone',     		'text',   false, '請輸入連絡電話', 3, 12, ''],
		['',			'username',     		'',   TRUE, '', 12, 12, ''],
		['電子郵件信箱',	'email',     		'text',   false, '請輸入電子郵件信箱', 3, 12, ''],
		['',			'username',     		'',   TRUE, '', 12, 12, ''],
		['相關資料網址',	'url',     		'text',   false, '請輸入相關資料網址', 3, 12, ''],
		['',			'username',     		'',   TRUE, '', 12, 12, ''],
       
	];

	private $edit_param_4 = [

		['姓名',		'username',     		'text',   TRUE, '', 3, 12, ''],
        ['',		    'username',     		'',   TRUE, '', 12, 12, ''],
        ['服務單位',		'now_unit_id',     		'select',   TRUE, '', 3, 12, '',['id','title']],
		// ['',		    'username',     		'',   TRUE, '', 12, 12, ''],
		['單位名稱',		'now_unit',     	'text',   TRUE, '請輸入單位名稱', 3, 12, ''],
		['職稱',		'now_other_title_id',     		'select_now',   TRUE, '', 3, 12, '',['id','title']],

		['',		    'username',     		'',   TRUE, '', 12, 12, ''],
		['曾任服務單位',		'old_unit_id',     		'select',   false, '', 3, 12, '',['id','title']],
		// ['',		    'username',     		'',   TRUE, '', 12, 12, ''],
		['單位名稱',		'old_unit',     	'text',   false, '請輸入單位名稱', 3, 12, ''],

		// ['',			'username',     		'',   TRUE, '', 12, 12, ''],
		['職稱',		'old_other_title_id',     		'select_old',   false, '', 3, 12, '',['id','title']],
		['',			'username',     		'',   TRUE, '', 12, 12, ''],
	
		
        // ['',		'username',     		'',   TRUE, '', 12, 12, ''],
		// ['學門專長',		'specialty_id',     		'select',   TRUE, '', 3, 12, '',['id','text']],
		// ['學門專長資料來源',		'source',     		'select',   TRUE, '', 3, 12, '',['id','text']],
		// ['',		'username',     		'',   TRUE, '', 12, 12, ''],
		// ['學術專長',		'specialty',     		'text',   TRUE, '請輸入學術專長', 3, 12, ''],
		// ['學術專長資料來源',		'source',     		'select',   TRUE, '', 3, 12, '',['id','text']],
		['',		'username',     		'',   TRUE, '', 12, 12, ''],

		['',			'username',     		'',   TRUE, '', 12, 12, ''],
		['連絡電話',	'phone',     		'text',   false, '請輸入連絡電話', 3, 12, ''],
		['',			'username',     		'',   TRUE, '', 12, 12, ''],
		['電子郵件信箱',	'email',     		'text',   false, '請輸入電子郵件信箱', 3, 12, ''],
		['',			'username',     		'',   TRUE, '', 12, 12, ''],
		['相關資料網址',	'url',     		'text',   false, '請輸入相關資料網址', 3, 12, ''],
		['',			'username',     		'',   TRUE, '', 12, 12, ''],
       
	];
	private $edit_param_5 = [

		['姓名',		'username',     		'text',   TRUE, '', 3, 12, ''],
        ['',		    'username',     		'',   TRUE, '', 12, 12, ''],
        ['服務單位',		'now_unit_id',     		'select',   TRUE, '', 3, 12, '',['id','title']],
		// ['',		    'username',     		'',   TRUE, '', 12, 12, ''],
		['單位名稱',		'now_unit',     	'text',   TRUE, '請輸入單位名稱', 3, 12, ''],
		['職稱',		'now_other_title_id',     		'select_now',   TRUE, '', 3, 12, '',['id','title']],

		['',		    'username',     		'',   TRUE, '', 12, 12, ''],
		['曾任服務單位',		'old_unit_id',     		'select',   false, '', 3, 12, '',['id','title']],
		// ['',		    'username',     		'',   TRUE, '', 12, 12, ''],
		['單位名稱',		'old_unit',     	'text',   false, '請輸入單位名稱', 3, 12, ''],

		// ['',			'username',     		'',   TRUE, '', 12, 12, ''],
		['職稱',		'old_title_id',     		'select_old',   false, '', 3, 12, '',['id','title']],
		['',			'username',     		'',   TRUE, '', 12, 12, ''],
	
		
        // ['',		'username',     		'',   TRUE, '', 12, 12, ''],
		// ['學門專長',		'specialty_id',     		'select',   TRUE, '', 3, 12, '',['id','text']],
		// ['學門專長資料來源',		'source',     		'select',   TRUE, '', 3, 12, '',['id','text']],
		// ['',		'username',     		'',   TRUE, '', 12, 12, ''],
		// ['學術專長',		'specialty',     		'text',   TRUE, '請輸入學術專長', 3, 12, ''],
		// ['學術專長資料來源',		'source',     		'select',   TRUE, '', 3, 12, '',['id','text']],
		['',		'username',     		'',   TRUE, '', 12, 12, ''],

		['',			'username',     		'',   TRUE, '', 12, 12, ''],
		['連絡電話',	'phone',     		'text',   false, '請輸入連絡電話', 3, 12, ''],
		['',			'username',     		'',   TRUE, '', 12, 12, ''],
		['電子郵件信箱',	'email',     		'text',   false, '請輸入電子郵件信箱', 3, 12, ''],
		['',			'username',     		'',   TRUE, '', 12, 12, ''],
		['相關資料網址',	'url',     		'text',   false, '請輸入相關資料網址', 3, 12, ''],
		['',			'username',     		'',   TRUE, '', 12, 12, ''],
       
	];
	private $edit_param_6 = [

		['姓名',		'username',     		'text',   TRUE, '', 3, 12, ''],
        ['',		    'username',     		'',   TRUE, '', 12, 12, ''],
        ['服務單位',		'now_unit_id',     		'select',   TRUE, '', 3, 12, '',['id','title']],
		// ['',		    'username',     		'',   TRUE, '', 12, 12, ''],
		['單位名稱',		'now_unit',     	'text',   TRUE, '請輸入單位名稱', 3, 12, ''],
		['職稱',		'now_title_id',     		'select_now',   TRUE, '', 3, 12, '',['id','title']],

		['',		    'username',     		'',   TRUE, '', 12, 12, ''],
		['曾任服務單位',		'old_unit_id',     		'select',   false, '', 3, 12, '',['id','title']],
		// ['',		    'username',     		'',   TRUE, '', 12, 12, ''],
		['單位名稱',		'old_unit',     	'text',   false, '請輸入單位名稱', 3, 12, ''],

		// ['',			'username',     		'',   TRUE, '', 12, 12, ''],
		['職稱',		'old_other_title_id',     		'select_old',   false, '', 3, 12, '',['id','title']],
		['',			'username',     		'',   TRUE, '', 12, 12, ''],
	
		
        // ['',		'username',     		'',   TRUE, '', 12, 12, ''],
		// ['學門專長',		'specialty_id',     		'select',   TRUE, '', 3, 12, '',['id','text']],
		// ['學門專長資料來源',		'source',     		'select',   TRUE, '', 3, 12, '',['id','text']],
		// ['',		'username',     		'',   TRUE, '', 12, 12, ''],
		// ['學術專長',		'specialty',     		'text',   TRUE, '請輸入學術專長', 3, 12, ''],
		// ['學術專長資料來源',		'source',     		'select',   TRUE, '', 3, 12, '',['id','text']],
		['',		'username',     		'',   TRUE, '', 12, 12, ''],

		['',			'username',     		'',   TRUE, '', 12, 12, ''],
		['連絡電話',	'phone',     		'text',   false, '請輸入連絡電話', 3, 12, ''],
		['',			'username',     		'',   TRUE, '', 12, 12, ''],
		['電子郵件信箱',	'email',     		'text',   false, '請輸入電子郵件信箱', 3, 12, ''],
		['',			'username',     		'',   TRUE, '', 12, 12, ''],
		['相關資料網址',	'url',     		'text',   false, '請輸入相關資料網址', 3, 12, ''],
		['',			'username',     		'',   TRUE, '', 12, 12, ''],
       
	];

	private $edit_specialty_param = [

		// ['學門專長',		'title',     		'text',   TRUE, '', 3, 12, ''],
		['學門專長',	'title_id',     		'select',   TRUE, '', 3, 12, '',['id','title']],
        ['',		    'username',     		'',   TRUE, '', 12, 12, ''],
       
	];
	private $add_new_specialty_param = [

		['學門專長',	'title_id',     		'select',   TRUE, '', 3, 12, '',['id','title']],
        ['',		    'username',     		'',   TRUE, '', 12, 12, ''],
       
	];
	private $edit_academics_param = [

		['學術專長',		'title',     		'text',   TRUE, '', 3, 12, ''],
        ['',		    'username',     		'',   TRUE, '', 12, 12, ''],
       
	];
	private $add_academics_param = [

		['學術專長',		'title',     		'text',   TRUE, '', 3, 12, ''],
        ['',		    'username',     		'',   TRUE, '', 12, 12, ''],
       
	];
	private $param = [
		['姓名',		'username',     		'text',   false, '', 3, 12, ''],
        ['',		    'username',     		'',   TRUE, '', 12, 12, ''],
        // ['服務單位',		'now_unit_id_2',     		'select',   TRUE, '', 3, 12, '',['id','title']],
		['服務單位',		'now_unit_id_2',     		'checkbox_unit_title',   false, '', 3, 12, ''],
		['是否包含曾任單位?',		'old_unit_id',     		'checkbox_1',   false, '', 3, 12, ''],
		['',			'username',     		'',   TRUE, '', 12, 12, ''],
		['職稱',		'now_2',     		'checkbox_title',   false, '', 3, 12, ''],
		['',			'username',     		'',   TRUE, '', 12, 12, ''],
		['學門專長',		'specialty_id',     		'select',   TRUE, '', 3, 12, '',['id','title']],
		['',			'username',     		'',   TRUE, '', 12, 12, ''],
		['學術專長',	'academic_1',     		'text',   false, '請輸入學術專長', 3, 12, ''],
		['  ',			'academic_2',     		'text',   false, '請輸入學術專長', 3, 12, ''],
		['',			'username',     		'',   TRUE, '', 12, 12, ''],
		// ['最後異動時間',  'edit_date',     		'select',   TRUE, '', 3, 12, '',['id','text']],date("Y-m-d")
		['最後異動時間',       'last_date',        'day',   false, '', 3, 12, ''],
		['',			'username',     		'',   TRUE, '', 12, 12, ''],
		['查詢筆數',       'search_num',        'number',   false, '', 3, 12, ''],
		['',			'username',     		'',   TRUE, '', 12, 12, ''],
		
	];
	private $department_param = [
		['姓名',		'username',     		'text',   false, '', 3, 12, ''],
        ['帳號',		    'username',     	'text',   false, '', 3, 12, ''],
        ['管理系所',		'unit',     		'text',   false, '', 3, 12, ''],
		['狀態',			'status',     		'select',   false, '', 3, 12, '',['id','text']],
		// ['',			'username',     		'',   TRUE, '', 12, 12, ''],
		// ['職稱',		'now',     		'checkbox_2',   false, '', 3, 12, ''],
		// ['',			'username',     		'',   TRUE, '', 12, 12, ''],
		// ['專長',		'specialty',     		'select',   TRUE, '', 3, 12, '',['id','text']],
		// ['',			'username',     		'',   TRUE, '', 12, 12, ''],
		// ['學術專長',	'specialty',     		'text',   TRUE, '請輸入學術專長', 3, 12, ''],
		// [' ',			'specialty_2',     		'text',   false, '請輸入學術專長', 3, 12, ''],
		// ['',			'username',     		'',   TRUE, '', 12, 12, ''],
		// ['最後異動時間',  'edit_date',     		'select',   TRUE, '', 3, 12, '',['id','text']],
	];
	private $add_param = [
		['姓名',		'username',     		'text_search',   TRUE, '', 3, 12, ''],
        ['',		    'username',     		'',   TRUE, '', 12, 12, ''],
        ['服務單位',		'now_unit_id',     		'select_button',   TRUE, '', 3, 12, '',['id','title']],
		// ['',		    'username',     		'',   TRUE, '', 12, 12, ''],
		['單位名稱',		'now_unit',     	'text',   false, '請輸入單位名稱', 3, 12, ''],
		['職稱',		'now_title_id',     		'select_now',   TRUE, '', 3, 12, '',['id','title']],
		// ['問卷封面圖片',		'',        'text_button',            TRUE,   '',	3,	12,	''],
		['',		    'username',     		'',   TRUE, '', 12, 12, ''],
		['曾任服務單位~',		'old_unit_id',     		'select_old_unit',   FALSE, '', 3, 12, '',['id','title']],
		// ['',		    'username',     		'',   TRUE, '', 12, 12, ''],
		['單位名稱~',		'old_unit',     	'text_old_unit',   FALSE, '請輸入單位名稱', 3, 12, ''],
		['職稱~',		'old_title_id',     		'select_old_title',   FALSE, '', 3, 12, '',['id','title']],

		// ['',			'username',     		'',   TRUE, '', 12, 12, ''],
		// ['職稱',		'now_title_id',     		'select',   TRUE, '', 3, 12, '',['id','text']],
		// ['',			'username',     		'',   TRUE, '', 12, 12, ''],
	
		['',			'username',     		'',   TRUE, '', 12, 12, ''],
		['連絡電話',	'phone',     		'text',   false, '請輸入連絡電話', 3, 12, ''],
		['',			'username',     		'',   TRUE, '', 12, 12, ''],
		['電子郵件信箱',	'email',     		'text',   false, '請輸入電子郵件信箱', 3, 12, ''],
		['',			'username',     		'',   TRUE, '', 12, 12, ''],
		['相關資料網址',	'url',     		'text',   false, '請輸入相關資料網址', 3, 12, ''],
		['',			'username',     		'',   TRUE, '', 12, 12, ''],
		
	];
	private $add_specialty_parm = [
		['姓名 : XXX',		'username',     		'title',   TRUE, '', 3, 12, ''],
        
		['單位名稱 : XXX',		'now_unit',     	'title',   TRUE, '', 3, 12, ''],
		
		['職稱 : XXX',		'now_title',     		'title',   TRUE, '', 3, 12, '',['id','text']],
	
		['',			'username',     		'',   TRUE, '', 12, 12, ''],
		['第一專長',	'specialty_classify',     		'select',   TRUE, '', 3, 12, '',['id','text']],
		['',			'username',     		'',   TRUE, '', 12, 12, ''],
		['第一專長資料來源',	'source',     		'select',   TRUE, '', 3, 12, '',['id','text']],
		['',			'username',     		'',   TRUE, '', 12, 12, ''],
		['第二專長',	'email',     		'text',   TRUE, '', 3, 12, ''],
		['',			'username',     		'',   TRUE, '', 12, 12, ''],
		['第二專長資料來源',	'source',     		'select',   TRUE, '', 3, 12, '',['id','text']],
		
	];
	private $th_title = [
		['#', '', ''],
				['單位分類', '', ''],
				['單位', '', ''],
				['動作', '', '']
	];
	public function output_data(Request $request, $status = 'normal'){
		
		$this->data['controller'] = 'recommend_form';
		$this->data['title']      = "列印查詢結果頁";
		$this->data['parent']     = "";
		$this->data['parent_url'] = "";
		$this->data['th_title']   = $this->th_title_field(
			[
					['#', '', ''],
					['姓名', '', ''],
					['服務單位', '', ''],
					['單位名稱', '', ''],
					['職稱', '', ''],
					['曾任', '', ''],
					['單位名稱', '', ''],
					['專長', '', ''],
					['學術專長', '', ''],
					['最後異動時間', '', ''],
			]
		);
		
		$this->data['type']='output';
		$this->data['template_item'] = 'mgr/items/template_item';

		$data = array();
		// $data = Member::with('privilege')->with('department')->get();
		// $role = Auth::guard('mgr')->user()->role;

		$this->data['data'] = array();
		foreach ($data as $item) {
			$status = '正常';
			

			$obj = array();
			

            $priv_edit = TRUE;
			$priv_del = TRUE;
			

			$other_btns = array();
			

			$this->data['data'][] = array(
				"id"         => 1,
				"data"       => $obj,
                "priv_edit"  => $priv_edit,
                "priv_del"   => $priv_del,
				// "other_btns" => $other_btns
			);
		}
             
            $obj[] = '1';
            $obj[] = '王大文';
            $obj[] = '公立學校';
            $obj[] = '台灣大學';
            $obj[] = '教授';
			$obj[]  = '';
			$obj[]  = '';
			$obj[]  = '化學';
            $obj[] = '物理化學(XXX，2023/4/1建立)';
            $obj[]  = '2023/4/1';
           
			// $obj[]  = '';
            
            $priv_edit = false;
			$priv_del = false;

            $this->data['data'][] = array(
				"id"         => 1,
				"data"       => $obj,
                "priv_edit"  => $priv_edit,
                "priv_del"   => $priv_del,
				// "other_btns" => $other_btns
			);

        // print_r($this->data['data']);exit;
		return view('mgr/template_list', $this->data);
	}
	public static function search_data(Request $request){
		// foreach($request as $r){
		// 	print_r($r['data']);exit;
		// }
		print_r($request);
		exit;
		
	}
    public function search(Request $request, $status = 'normal'){

        
        if ($request->isMethod('post')) {
            // print 123;exit;if 
			
			
			$formdata = $this->process_post_data($this->param, $request);
			// print_r($formdata);exit;
			$search_num=$formdata['search_num'];
			$formdata['now_unit_id']=$formdata['now_unit_id_2'];
			unset($formdata['now_unit_id_2']);
			$search_last_date=$formdata['last_date'];
			$last_date=date("Y-m-d",strtotime($formdata['last_date'].'+1 day'));
			// $last_date=$formdata['last_date'];
			// print_r($last_date);exit;
			$academic_list=array();
			if(array_key_exists('academic_1',$formdata) && $formdata['academic_1']!=''){
				// print 123;exit;
				$academic_list[]=$formdata['academic_1'];
			}
			if(array_key_exists('academic_2',$formdata) && $formdata['academic_2']!=''){
				// print 123;exit;
				$academic_list[]=$formdata['academic_2'];
			}
			$search_academic=implode("、",$academic_list);

			$a_num=count(array_keys($academic_list));
			$a_key=array_keys($academic_list);
			$a_value=array_values($academic_list);
			$syntax_academic=array();
			for($i=0;$i<$a_num;$i++){

				// if(!empty($value[$i])){
					// $syntax_key[$i]=$key[$i];
					// $syntax_value[$i]=$value[$i];

					$syntax_academic[]=array(
						'key'=>$a_key[$i],
						'value'=>$a_value[$i]
					);
				// }
				
				
			}
			
			
			// $specialty_list_data=SpecialtyList::find($formdata['specialty_id']);
			// print_r($syntax_academic);exit;
			// if($formdata)
			unset($formdata['academic_1']);
			unset($formdata['academic_2']);
			unset($formdata['last_date']);
			unset($formdata['search_num']);
			
			// print_r($request->all());exit;
			$have='';
			$now_title=array();
			$now_unit=array();
			$flag_1=0;
			foreach($request->all() as $key=>$data_h){
				// print_r($request->all());exit;
				
				if($key== 'have'){
					$formdata['old_unit_id']=$formdata['now_unit_id'];
					$now_unit['have']='have';
					$flag_1=1;
				}
				if(substr($key, 0 ,7) == 'Jtitle_'){
					$now_title[] = explode("_", $key);
				} 
				if(substr($key, 0 ,7) == 'Utitle_'){
					$now_unit[] = explode("_", $key);
				} 
					// print_r($p['1']);
			}
		
			//取得查詢title名稱
			$search_job_title=Committeeman::get_job_title($now_title);

			//取得查詢unit名稱
			$search_unit_title=Committeeman::get_unit_title($now_unit);

			// print_r($search_title);exit;
		
			//取得查詢學門專長名稱
			$search_sdata=SpecialtyList::find($formdata['specialty_id']);

			$num=count(array_keys($formdata));
			$key=array_keys($formdata);
			$value=array_values($formdata);
			
			for($i=0;$i<$num;$i++){

				if(!empty($value[$i])){
					// $syntax_key[$i]=$key[$i];
					// $syntax_value[$i]=$value[$i];
					$syntax[]=array(
						'key'=>$key[$i],
						'value'=>$value[$i]
					);
				}
			}
			// print_r($now_title);exit;
			// print_r($now_unit);exit;
			if($formdata['specialty_id']==100){
				// $test="'specialties.title_id','!=',$formdata[specialty_id]";
				$sign="!=";
			}else{
				// $test= 'specialties.title_id',$formdata['specialty_id'] ;
				$sign='=';
			}
			// print_r($syntax);exit;
			// exit;
			// $test='specialties.title_id';
			
		//啟用紀錄SQL語法
		DB::enableQueryLog();
			//開始查詢
			// $data = DB::table('committeemen')->get();  
			// 目前有 姓名 + 職稱
			$data_num = Committeeman::select('committeemen.*', 'specialties.title_id','academics.title')
								->leftJoin('specialties', function($leftJoin) use($formdata)
									{
										$leftJoin->on('specialties.committeeman_id', '=', 'committeemen.id');
									})
								->where('specialties.title_id',$sign, $formdata['specialty_id'])
								->leftJoin('academics', function($leftJoin) use($academic_list)
									{
										$leftJoin->on('academics.committeeman_id', '=', 'committeemen.id');
									})
								->where(function($query) use ($syntax_academic) {
									foreach($syntax_academic as $a_list){
											$query->orWhere('academics.title','like', '%'. $a_list['value'].'%');
									}
								})->where(function($query) use ($syntax) {
									foreach($syntax as $s){
										if($s['key']!='specialty_id'){
											$query->Where($s['key'], $s['value']);
										}
									}
								})->where(function($query) use ($now_title) {
									// print_r($now_title);exit;
										if(isset($now_title)){
											foreach($now_title as $n_title){
												$query->orWhere('now_title_id', $n_title['1']);
											}
										}
									})
								///add new 0613
								->where(function($query) use ($now_unit) {
									if(isset($now_unit)){
										$flag=0;
										foreach($now_unit as $n_unit){
											if($n_unit=='have'){
												$flag=1;
												unset($now_unit['have']);
											}
											$query->orWhere('now_unit_id', $n_unit['1']);
										}
									}
									if($flag==1){
										
										foreach($now_unit as $n_unit){
											// print_r($n_unit['1']) ;
											$query->orWhere('old_unit_id', $n_unit['1']);
										}
										// exit;
									}
									
								})
								->where('committeemen.updated_at','<',$last_date)
								->where('status','=','on')
								->groupBy('id')
								->get()->count();
			// print_r($data_num);exit;
			if($search_num=='') $search_num=$data_num;
			$data = Committeeman::select('committeemen.*', 'specialties.title_id','academics.title',)
								->leftJoin('specialties', function($leftJoin) use($formdata)
									{
										$leftJoin->on('specialties.committeeman_id', '=', 'committeemen.id')
											// ->where('specialties.title_id',$formdata['specialty_id'])
											;
									})
								->where('specialties.title_id',$sign,$formdata['specialty_id'])
								->leftJoin('academics', function($leftJoin) use($academic_list)
									{
										$leftJoin->on('academics.committeeman_id', '=', 'committeemen.id')
										// foreach($academic_list as $a_list){
											// ->where('academics.title',$academic_list['specialty_id'])
										// }
											;
									})
								->where(function($query) use ($syntax_academic) {
								// $query->Where('username', $formdata['username']);
									foreach($syntax_academic as $a_list){
									
										// if($a_list['key']!='specialty_id'){
											$query->orWhere('academics.title','like', '%'. $a_list['value'].'%');
										// }
										// $query->orWhere('username', 'like', '%'.$search.'%');
									}
								})
								->where(function($query) use ($syntax) {
								// $query->Where('username', $formdata['username']);
									foreach($syntax as $s){
										if($s['key']!='specialty_id'){
											$query->Where($s['key'], $s['value']);
										}
										
										
									}
								})
								->where(function($query) use ($now_title) {
										if(isset($now_title)){
											foreach($now_title as $n_title){
												$query->orWhere('now_title_id', $n_title['1']);
											}
										}
										
									})
									///add new 0613
								->where(function($query) use ($now_unit) {
										if(isset($now_unit)){
											$flag=0;
											foreach($now_unit as $n_unit){
												if($n_unit=='have'){
													$flag=1;
													unset($now_unit['have']);
												}
												$query->orWhere('now_unit_id', $n_unit['1']);
											}
										}
										if($flag==1){
											
											foreach($now_unit as $n_unit){
												// print_r($n_unit['1']) ;
												$query->orWhere('old_unit_id', $n_unit['1']);
											}
											// exit;
										}
										
									})
								->where('committeemen.updated_at','<',$last_date)
								///以上隱藏
								// ->where(function($query) use ($academic_list) {
								// 	foreach($academic_list as $s){
								// 		$query->Where($s['key'], $s['value']);
								// 	}
								// 		// if(isset($academic_list)){
								// 		// 	foreach($academic_list as $sss){
								// 		// 		$query->orWhere('now_title_id', $sss['1']);
								// 		// 	}
								// 		// }
										
								// 	})
							// ->skip(($page - 1) * $this->page_count)
							// ->take($this->page_count)
							->where('status','=','on')
							->groupBy('id')
							->limit($search_num)
    						->inRandomOrder()
							// ->orderBy('committeemen.id','asc')
							->get();
			// echo count($data);exit;
			// dd(DB::getQueryLog());exit;
			// print_r($data);exit;
			
			$this->data['controller'] = 'committeemen';
            $this->data['title']      = "專家清單";
            $this->data['parent']     = "";
            $this->data['parent_url'] = "";
            $this->data['th_title']   = $this->th_title_field(
                [
					['#', '', ''],
					['姓名', '', ''],
					['服務單位', '', ''],
					['單位名稱', '', ''],
					['職稱', '', ''],
					['曾任單位', '', ''],
					['曾任單位名稱', '', ''],
					// ['曾任職稱', '', ''],
					['專長', '', ''],
					['學術專長', '', ''],
					['推薦人', '', ''],
					['最後異動時間', '', ''],
                ]
            );
			$this->data['bar_btns'] = [
				// ['列印', 'window.open(\''.route('mgr.committeeman.output_data').'\');', 'primary', '1'],
				// ['列印+保存到伺服器', 'window.open(\''.route('mgr.committeeman.output_data').'\');', 'primary', '2'],
				['下載PDF', 'window.open(\''.route('mgr.committeeman.pdf_export').'\');', 'primary', '2']
			];
			///查詢條件
			// print_r($flag);exit;
			// if($flag==1){
			// 	print 12321313;exit;
			// }elseif($flag==0){
			// 	print 787878;exit;
			// }else{
			// 	print 9999;exit;
			// }
			// print_r($flag==false);exit;
			$this->data['type']='search';
			$this->data['search_name']=($formdata['username'])?$formdata['username']:'未填寫';
			// $this->data['search_name']=($search_title!='')?$search_title:'未勾選';
			$this->data['search_now_unit']=($search_unit_title!='')?$search_unit_title:'未勾選';
			$this->data['have']=($flag_1==1)?'含曾任':'不含曾任';
			$this->data['search_title']=($search_job_title!='')?$search_job_title:'未勾選';  //職稱
			$this->data['search_specialty']=$search_sdata->title;  //學門專長
			$this->data['search_academic']=($search_academic)?$search_academic:'未填寫';  //學術專長
			$this->data['last_date']=$search_last_date;
			// $this->data['search_data']='查詢條件  姓名:'.$formdata['username'].'姓名:';
			// print_r($this->data['search_data']);exit;
			$this->data['search_data'][]=array(
				// "id"    =>  1,
				"search_name"  =>   ($formdata['username'])?$formdata['username']:'未填寫',
				"search_now_unit"  => ($search_unit_title!='')?$search_unit_title:'未勾選',
				"have"   => ($flag_1==1)?'含曾任':'不含曾任',
				"search_title" =>($search_job_title)?$search_job_title:'未填寫',
				"search_specialty" => ($search_sdata->title)?$search_sdata->title:'未填寫',
				"search_academic" => ($search_academic)?$search_academic:'未填寫',
				"last_date" => ($search_last_date)?$search_last_date:'未填寫',
				
			);
			///查詢條件 end
			$this->data['template_item'] = 'mgr/items/committeeman_item';
            $this->data['data'] = array();
			$x=0;
			// print count($data);exit;
			
            foreach ($data as $item) {
				// print_r($item->id);exit;
				// $member_id=1;
				$specialty_data_test=array();
				$academic_data_test=array();
			// 取得推薦人名
			$member_name=Committeeman::get_member_name($item['member_id']);
			// print_r($data);exit;
				// print_r($item);exit;
				//取得多筆學門專長
				$specialty_data=Committeeman::get_specialty($item->title_id,$item->id);
				// print_r($specialty_data);
				// echo count($specialty_data);exit;
				for($h=0;$h<count($specialty_data);$h++){
					if(isset($specialty_data[$h])){
						$specialty_data_test[]=$specialty_data[$h];
					}else{
						$specialty_data_test[]='';
					}
				}
				// print_r($specialty_data_test);exit;
				//取得多筆學術專長
				$academic_data=Committeeman::get_academic($item->title,$item->id);
				// print_r($academic_data);
				// $academic_data_test=array();
				for($h=0;$h<count($data);$h++){
					if(isset($academic_data[$h])){
						$academic_data_test[]=$academic_data[$h];
					}else{
						$academic_data_test[]='';
					}
				}

				foreach($item->academic as $a_title){
					// print_r($a_title);exit;
					$writer_data=Member::find($a_title->writer_id);
	
					// print_r($writer_data);exit;
					// $academic[$i]['title']=$a_title->title;
					// $academic[$i]['writer_name']=$writer_data->username;
					// $academic[$i]['create_date']=$a_title->create_date;
					$academic_updated_at[$i]=$a_title->updated_at;
					
					$academic_writer_id[$i]=$a_title->writer_id;
					$academic_list[$i]=$a_title->title.'('.$writer_data->username.'，'.$a_title->create_date.'建立)';
					$i++;
				}
				// print_r($academic_writer_id);exit;
				// $academic_w_id=implode("、",$academic_writer_id);
				// print_r($academic_w_id);
				
				$academic_data=implode("、",$academic_list);
				$last_updated_at=$item->updated_at;
				//抓出最後修改時間
				// foreach($academic_updated_at as  $key => $a_updated_at){
					
				// 	if($item->updated_at > $a_updated_at){
				// 		// print 123;
				// 		$last_updated_at=$item->updated_at;
				// 	}else{
				// 		// print 456;
				// 		$last_updated_at=$item->a_updated_at;
				// 	}
				// 	// print '__';
				// 	// print_r($a_updated_at);
				// }
				// foreach($academic_updated_at as   $a_updated_at){
				// 	if($last_updated_at < $a_updated_at){
				// 		$last_updated_at=$a_updated_at;
				// 	}

				// }
				
				// print_r($academic_data_test);
				// print_r($item->updated_at);exit;
                $obj = array();
				// $obj[] = $item->id;
                $obj[] = $x+1;
                $obj[] = $item->username;
				$obj[] = $item->now_service_unit->title;  	//服務單位
				$obj[] = $item->now_unit;
				$obj[] = $item->now_title->title; 
				$obj[] = (isset($item->old_service_unit->title))?$item->old_service_unit->title:'';    //曾任單位
				// $obj[] = $item->old_title->title;	//曾任
				$obj[] = $item->old_unit; 
				$obj[] = $specialty_data_test[0]; 			//學門專長
				$obj[] = $academic_data_test[0];				//學術專長
				$obj[] = $member_name; 		
				$obj[] =  $last_updated_at; 
                // $obj[] = '自然科學類';
                $priv_edit = false;
                $priv_del = false;
                $priv_verified=false;
                $priv_block=false;
                $priv_reset_pwd=false;
                $priv_reset_pwd_zero=false;
                $priv_reset_pwd_ext=false;
                $this->data['data'][] = array(
                    "id"    =>  1,
                    "data"  =>   $obj,
                    "priv_edit"  => $priv_edit,
                    "priv_del"   => $priv_del,
                    "priv_verified" => $priv_verified,
                    "priv_block" => $priv_block,
                    "priv_reset_pwd" => $priv_reset_pwd,
                    "priv_reset_pwd_zero" => $priv_reset_pwd_zero,
                    "priv_reset_pwd_ext" => $priv_reset_pwd_ext,
                );
            // $this->data['btns'] = [
				
            //     ['<i class="ri-add-fill"></i>', '新增帳號', route('mgr.member.add'), 'primary'],
			// 	['新增帳號', '新增帳號', route('mgr.member.add'), 'primary','2']
            // ];
            // print_r($this->data);exit;
			$x++;
            }
			// print_r($this->data['data']);exit;
			$request->session()->put('search_list', $this->data['data']);
			$request->session()->put('search_data', $this->data['search_data']);

			
			// Committeeman::save_search($this->data['data']);
			// $this->search_data($this->data['data'],'123');

			return view('mgr/template_list', $this->data);

		}
		// exit;
        
		$this->data['title'] = "查詢專家";
		$this->data['parent'] = "外審委員";
		$this->data['parent_url'] = route('mgr.committeeman');
		$this->data['action'] = route('mgr.committeeman.search');
		$this->data['submit_txt'] = '查詢';

		$this->data['params'] = $this->generate_param_to_view($this->param);
		$this->data['btns'] = [
			['<i class="ri-add-fill"></i>', '新增推薦資料', route('mgr.recommend_form.add'), 'primary']
		];

		$this->data['job_title'] = JobTitle::where('title','!=','其他')->get();
		$this->data['unit_title'] = ServiceUnit::where('id', '!=', 3)->get();
		$this->data['is_have'] = '是';
        // print_r($this->data);exit;
        // return view('mgr/template_list', $this->data);
		// return view('mgr/committeeman_lsit', $this->data);
		return view('mgr/template_form', $this->data);

    }
    public function search_old(Request $request, $status = 'normal'){

        
        if ($request->isMethod('post')) {
            
            $this->data['controller'] = 'committeeman';
            $this->data['title']      = "帳號管理";
            $this->data['parent']     = "";
            $this->data['parent_url'] = "";
            $this->data['th_title']   = $this->th_title_field(
                [
                    ['#', '', ''],
                    // ['', '', ''],
                    ['姓名', '', ''],
                    ['性別', '', ''],
                    ['服務單位', '', ''],
                    ['職稱', '', ''],
                    ['連絡電話', '', ''],
                    // ['狀態', '', ''],
                    // ['建立時間', '', ''],
                    ['學門/學類', '', ''],
                    ['學術專長(研究)', '', ''],
                    // ['審核未通過', '', ''],
                    // ['還原密碼', '', '']
                ]
            );
			$this->data['btns'] = [
				['<i class="ri-add-fill"></i>', '新增推薦資料', route('mgr.recommend_form.add'), 'primary']
			];
            $data = array();
            $data = array("123");
            // $data = array(array("123","123","123","123","123","123","123","123"));
            $this->data['data'] = array();
            foreach ($data as $item) {
                $obj = array();
                $obj[] = 1;
                $obj[] = '王大文';
                $obj[] = '男';
                $obj[] = '台灣大學';
                $obj[] = '教授';
                // $obj[] = $item->department->title;
                $obj[] = '0912345678';
                $obj[] = '生物科學類';
                $obj[] = '自然科學類';

                $priv_edit = false;
                $priv_del = false;
                $priv_verified=false;
                $priv_block=false;
                $priv_reset_pwd=false;
                $priv_reset_pwd_zero=false;
                $priv_reset_pwd_ext=false;
                $this->data['data'][] = array(
                    "id"    =>  1,
                    "data"  =>   $obj,
                    "priv_edit"  => $priv_edit,
                    "priv_del"   => $priv_del,
                    "priv_verified" => $priv_verified,
                    "priv_block" => $priv_block,
                    "priv_reset_pwd" => $priv_reset_pwd,
                    "priv_reset_pwd_zero" => $priv_reset_pwd_zero,
                    "priv_reset_pwd_ext" => $priv_reset_pwd_ext,
                );
            // $this->data['btns'] = [
            //     ['<i class="ri-add-fill"></i>', '新增帳號', route('mgr.member.add'), 'primary']
            // ];
            
            // print_r($this->data);exit;
            }
		    $this->data['template_item'] = 'mgr/items/template_item';
            // print 123;exit;
            // return view('mgr/template_list', $this->data);
            // print_r(123);exit;
			// $formdata = $this->process_post_data($this->param, $request);
			
			// if (Member::where('email', $formdata['email'])->count() > 0){
			// 	$this->js_output_and_back('Email已存在');
			// 	exit();
			// }

            
		

			// $res = Member::create($formdata);
			// if ($res) {
			// 	$this->js_output_and_redirect('新增成功', 'mgr.committeeman');
			// } else {
			// 	$this->js_output_and_back('新增發生錯誤');
			// }
			// exit();
		}
        // print 123;exit;
        
		$this->data['title'] = "查詢名單";
		$this->data['parent'] = "外審委員";
		$this->data['parent_url'] = route('mgr.committeeman');
		$this->data['action'] = route('mgr.committeeman.search');
		$this->data['submit_txt'] = '確認新增';

		$this->data['params'] = $this->generate_param_to_view($this->param);
		$this->data['btns'] = [
			['<i class="ri-add-fill"></i>', '新增推薦資料', route('mgr.recommend_form.add'), 'primary']
		];
		
        // print_r($this->data);exit;
		
        // return view('mgr/template_list', $this->data);
		return view('mgr/committeeman_lsit', $this->data);

    }
	public function index(Request $request, $status = 'normal')
	{
		$member_id=Auth::guard('mgr')->user()->id;
		$member_username=Auth::guard('mgr')->user()->username;
		$privilege_id=Auth::guard('mgr')->user()->privilege_id;
		// $user = Auth::user();
		// print_r($user);exit;

		// print 1231;exit; Committeeman::with('specialty')->with('service_units')->get()
		// $data=Committeeman::with('specialty')->with('service_units')->get();

		// foreach($data as $d){
		// 		print_r($d->specialty->title);exit;
		// 	}

		// print_r($data);exit;


		$this->data['controller'] = 'committeeman';
		$this->data['title']      = "推薦資料列表";
		$this->data['parent']     = "";
		$this->data['parent_url'] = "";
		$this->data['th_title']   = $this->th_title_field(
			[
				['#', '', ''],
				['姓名', '65px', ''],
				['服務單位', '80px', ''],
				['單位名稱', '80px', ''],
				['職稱', '', ''],
				['曾任', '80px', ''],
				['單位名稱', '', ''],
				['職稱', '', ''],
				['連絡電話', '', ''],
				['電子郵件信箱', '120px', ''],
				['相關資料網址', '120px', ''],
				['學門專長', '150px', ''],	
				['學術專長', '150px', ''],
				['最後異動時間', '120px', ''],
				['狀態', '', ''],
				['動作', '', ''],
			]
		);
		// $this->data['btns'] = [
		// 	['<i class="ri-add-fill"></i>', '新增推薦資料', route('mgr.recommend_form.add'), 'primary']
		// ];
		$this->data['type']='';
		$this->data['template_item'] = 'mgr/items/template_item';

		$data = array();
		// $data_writer=array();
		// $data_writer=Member::with('writer')->get();
		// // print_r($data_writer);exit;
		// $j=0;
		// foreach($data_writer as $d_writer){
		// 	$academic[$j]=$d_writer->username;
		// 	$j++;
		// }
		// print_r($academic);exit;
		$academic_list=array();
		if($privilege_id==1){
			$data=Committeeman::with('specialty')
							->with('academic')
							// ->with('writer')
							->with('get_member')
							// ->where('member_id',$member_id) //帳號本人才顯示
							->get();
		}else{
			$data=Committeeman::
			select('committeemen.*','academics.writer_id',)
			->leftJoin('academics', function($leftJoin) use($academic_list)
									{
										$leftJoin->on('academics.committeeman_id', '=', 'committeemen.id');
									})
			->with('specialty')
			// ->with('now_service_unit')
			// ->with('old_service_unit')
			// ->with('now_title')
			->with('old_title')
			->with('academic')
			// ->with('writer')
			->with('get_member')
			->where('member_id',$member_id) //帳號本人才顯示
			->orwhere('academics.writer_id',$member_id)
			->groupBy('id')
			->get();
		}

		
		// $data = Member::with('privilege')->with('department')->get();
		// $role = Auth::guard('mgr')->user()->role;

		// print_r($data);exit;

		$this->data['data'] = array();
		foreach ($data as $item) {
			
			
			
			// print_r($item->get_member->department_id);
			// print_r($item->get_member->my_department_id);
			//取得所有科系
			// $department=explode('、', $item->get_member->department_id);
			// $department[]=$item->get_member->my_department_id;
			// print_r($department);

			//取得多筆學門專長
			$j=0;
			$specialty_list=array();
			foreach($item->specialty as $s_title){
				// print_r($s_title->title_id);
				// exit;
				$writer_data=Member::find($s_title->writer_id);
				$specialty_list_data=SpecialtyList::find($s_title->title_id);
				// print_r($writer_data);exit;
				// $academic[$i]['title']=$a_title->title;
				// $academic[$i]['writer_name']=$writer_data->username;
				// $academic[$i]['create_date']=$a_title->create_date;

				$specialty_list[$j]=$specialty_list_data->title;
				$j++;
			}
			
			$specialty_data=implode("、",$specialty_list);
			// print_r($specialty_list);
			// print '___';
			//取得多筆學術專長
			$i=0;
			$academic_list=array();
			// print_r($item->academic);
			// exit;
			foreach($item->academic as $a_title){
				// print_r($a_title);exit;
				$writer_data=Member::find($a_title->writer_id);

				// print_r($writer_data);exit;
				// $academic[$i]['title']=$a_title->title;
				// $academic[$i]['writer_name']=$writer_data->username;
				// $academic[$i]['create_date']=$a_title->create_date;
				$academic_updated_at[$i]=$a_title->updated_at;
				
				$academic_writer_id[$i]=$a_title->writer_id;
				$academic_list[$i]=$a_title->title.'('.$writer_data->username.'，'.$a_title->create_date.'建立)';
				$i++;
			}
			// print_r($academic_writer_id);exit;
			// $academic_w_id=implode("、",$academic_writer_id);
			// print_r($academic_w_id);
			
			$academic_data=implode("、",$academic_list);
			// print_r($academic_data);
			// if(isset($item->now_service_unit->title)){
			// 	print_r($item->now_service_unit->title);
			// }

		// }
		// print_r( $item->updated_at);exit;
		$last_updated_at=$item->updated_at;
		//抓出最後修改時間
		// foreach($academic_updated_at as  $key => $a_updated_at){
			
		// 	if($item->updated_at > $a_updated_at){
		// 		// print 123;
		// 		$last_updated_at=$item->updated_at;
		// 	}else{
		// 		// print 456;
		// 		$last_updated_at=$item->a_updated_at;
		// 	}
		// 	// print '__';
		// 	// print_r($a_updated_at);
		// }
		// foreach($academic_updated_at as   $a_updated_at){
		// 	if($last_updated_at < $a_updated_at){
		// 		$last_updated_at=$a_updated_at;
		// 	}

		// }
		// exit;
		// $status = '正常';
			$status = '
					<div class="form-check form-switch">
						<input class="form-check-input switch_toggle" data-id="'.$item->id.'
						" type="checkbox" role="switch" '.(($item->status=='on')?'checked':'').'>
					</div>
				';
			
			if($item->status=='on'){
				$status_str='啟用';
			}else{
				$status_str='關閉';
			}
			if($privilege_id!=1){
				if($item->member_id!=Auth::guard('mgr')->user()->id){
					$status=$status_str;
				}
			}
			if(isset($item->now_title) && $item->now_title->title=='其他'){
				if(OtherTitle::where('committeeman_id',$item->id)->where('type','now')->first()){
					$other=OtherTitle::where('committeeman_id',$item->id)->where('type','now')->first();
					// print_r($other);exit;
					
					$job_title=$other['title'];
				}else{
					$job_title=(isset($item->now_title->title))?$item->now_title->title:'';
				}
				
			}else{
				if($item->now_title_id==4){
					if(OtherTitle::where('committeeman_id',$item->id)->where('type','now')->first()){
						$other=OtherTitle::where('committeeman_id',$item->id)->where('type','now')->first();
						$job_title=$other['title'];
					}else{
						$job_title=(isset($item->now_title->title))?$item->now_title->title:'';
						// $job_other_title=456;
					}
				}else{
					$job_title=(isset($item->now_title->title))?$item->now_title->title:'';
				// $job_title=$item->now_title->title;
				}
			}

			// print_r($item);exit;
			if(isset($item->old_title) && $item->old_title->title=='其他'){
				if($other=OtherTitle::where('committeeman_id',$item->id)->where('type','old')->first()){
					$other=OtherTitle::where('committeeman_id',$item->id)->where('type','old')->first();
					// print_r($other);exit;
					$job_other_title=$other['title'];
					// $job_other_title=123;
				}else{
					$job_other_title=(isset($item->old_title->title))?$item->old_title->title:'';
				}
				
			}else{
				if($item->old_title_id==4){
					if(OtherTitle::where('committeeman_id',$item->id)->where('type','old')->first()){
						$other=OtherTitle::where('committeeman_id',$item->id)->where('type','old')->first();
						$job_other_title=$other['title'];
					}else{
						$job_other_title=(isset($item->old_title->title))?$item->old_title->title:'';
						// $job_other_title=456;
					}
				}else{
					$job_other_title=(isset($item->old_title->title))?$item->old_title->title:'';
				}
				
				
			}
			
			$obj = array();
			$obj[] = $item->id;  
            $obj[] = $item->username; 					//姓名
            $obj[] = (isset($item->now_service_unit->title))? $item->now_service_unit->title:"";  	//服務單位
			$obj[] = $item->now_unit;  					//單位名稱
            $obj[] = $job_title;		 	//職稱
            $obj[] = (isset($item->old_service_unit->title))?$item->old_service_unit->title:''; 	//曾任
			// $obj[]  = $item->old_title->title; 			//單位名稱
			$obj[]  = (isset($item->old_unit))?$item->old_unit:""; 			 		//單位名稱
			// $obj[]  = (isset($item->old_title->title))?$item->old_title->title:""; 			//職稱
			$obj[]  = $job_other_title; 			//職稱
			$obj[] = $item->phone; 
			$obj[] = $item->email; 
			$obj[] = $item->url; 

			$obj[]  = $specialty_data; 			//學門專長
            // $obj[] = '物理化學(XXX，2023/4/1建立)';		//學術專長
			$obj[] =  $academic_data;				//學術專長
            $obj[]  = $last_updated_at;
            $obj[]  = $status;
			// $academics_writer_id=
            $priv_edit = TRUE;
			$priv_edit_academics = TRUE;
			$priv_edit_specialty = TRUE;
			$priv_del = false;
			$item_member_id=$item->member_id;
			$member_id=Auth::guard('mgr')->user()->id;
			$my_department=$item->get_member->my_department_id;
			// print_r($my_department);
			// exit;
			// exit;
			// 登入人科系
			// $member_id=Auth::guard('mgr')->user()->id;
			$member_login=Member::find($member_id);
			$member_department=explode('、', $member_login->department_id);
			// $member_department[]=$member_login->my_department_id;
			// print_r($member_department);
			// exit;
			$other_btns = array();
	
			$this->data['data'][] = array(
				"id"         => $item->id,
				"data"       => $obj,
                "priv_edit"  => $priv_edit,
				"priv_edit_academics"  => $priv_edit_academics,
				"priv_edit_specialty"  => $priv_edit_specialty,
                "priv_del"   => $priv_del,
				"item_member_id"  => $item_member_id,
				"member_id"  => $member_id,
				"my_department"  => $my_department,
				"member_department"  => $member_department,
				"academic_w_id"		=>$academic_writer_id,
				"privilege_id"		=>$privilege_id
				// "other_btns" => $other_btns
			);
			// print_r($academic_writer_id);
		}
		
		// exit;
           

        // print_r($this->data['data']);exit;
		return view('mgr/template_list', $this->data);
	}
	public function data(Request $request){
	
		$html='';
		
        // $data = DB::table('units')->get();
		$data[] = array('id'=>1,'unit_classify'=>'123','unit_name'=>'test');
		$this->data['data'] = array();
		foreach ($data as $item) {
			$obj = array();
            $obj[] = $item['id'];
			// $obj[] = $item['unit_classify'];
			// $obj[] = $item['unit_name'];
			$obj[] = '王大同123';
			$obj[] = '公立學校';
			$obj[] = '台大';
			$obj[] = '教授';
			$obj[] = '';
			$obj[] = '';
			$obj[] = '化學';
			$obj[] = '分析化學....';
			$obj[] = '20231/2/4';

			$priv_edit = false;
			$priv_del = false;
			$other_btns = array();
			
			
			$html .= view('mgr/items/template_item', [
				'item'      => array(
					"id"         => $item['id'],
					"data"       => $obj,
					"other_btns" => $other_btns,
					"priv_edit"  => $priv_edit,
					"priv_del"   => $priv_del
				),
				'th_title'  => $this->th_title_field($this->th_title)
			])->render();
		}

		$this->output(TRUE, 'success', array(
			'html'	=>	$html
		));

	}
	public function data_have(Request $request){
		// print 123;exit;
		
		$html='';
		
        // $data = DB::table('units')->get();
		$data[] = array('id'=>1,'unit_classify'=>'123','unit_name'=>'test');
		$this->data['data'] = array();
		foreach ($data as $item) {
			$obj = array();
            $obj[] = $item['id'];
			// $obj[] = $item['unit_classify'];
			// $obj[] = $item['unit_name'];
			$obj[] = '王大同';
			$obj[] = '公立學校';
			$obj[] = '台大';
			$obj[] = '教授';
			$obj[] = '';
			$obj[] = '';
			$obj[] = '化學';
			$obj[] = '分析化學....';
			// $obj[] = '20231/2/4';

			$priv_edit = false;
			$priv_del = false;
			$other_btns = array();
			
			
			$html .= view('mgr/items/template_item', [
				'item'      => array(
					"id"         => $item['id'],
					"data"       => $obj,
					"other_btns" => $other_btns,
					"priv_edit"  => $priv_edit,
					"priv_del"   => $priv_del
				),
				'th_title'  => $this->th_title_field($this->th_title)
			])->render();
		}

		$this->output(TRUE, 'success', array(
			'html'	=>	$html
		));

	}
	public function data_no(Request $request){
		// print 123;exit;
		
		$html='';
		
        // $data = DB::table('units')->get();
		$data[] = array('id'=>1,'unit_classify'=>'123','unit_name'=>'test');
		$this->data['data'] = array();
		foreach ($data as $item) {
			$obj = array();
            $obj[] = $item['id'];
			// $obj[] = $item['unit_classify'];
			// $obj[] = $item['unit_name'];
			$obj[] = '王大同';
			$obj[] = '公立學校';
			$obj[] = '台大';
			$obj[] = '教授';
			$obj[] = '';
			$obj[] = '';
			$obj[] = '化學';
			$obj[] = '分析化學....';
			$obj[] = '20231/2/4';

			$priv_edit = false;
			$priv_del = false;
			$other_btns = array();
			
			
			$html .= view('mgr/items/template_item', [
				'item'      => array(
					"id"         => $item['id'],
					"data"       => $obj,
					"other_btns" => $other_btns,
					"priv_edit"  => $priv_edit,
					"priv_del"   => $priv_del
				),
				'th_title'  => $this->th_title_field($this->th_title)
			])->render();
		}

		$this->output(TRUE, 'success', array(
			'html'	=>	$html
		));

	}
	public function unlink_line(Request $request, $id){
		if (Member::where('id', $id)->update(['line_id'=>''])) {
			$this->js_output_and_redirect("已解除綁定", 'mgr.member');
		}else{
			$this->js_output_and_back("解除發生錯誤");
		}
	}
	//新增專長及學門
	public function add_specialty(Request $request,$id){
		// print_r($_COOKIE['formdata']);
		$data=Committeeman::find($id);
	
		$committeeman_name =$data->username;
		// print_r($data['now_title_id']);exit;
		$title_data=JobTitle::find($data['now_title_id']);
		$data['now_title_id']=$title_data->title;
		
		// print_r($title_data->title);exit;
		$member_id=Auth::guard('mgr')->user()->id;
		$member_username=Auth::guard('mgr')->user()->username;
		
		$session_data = $request->session()->all();
		// print_r($session_data);exit;
		
		// $this->data['select']['specialty_id'] = SpecialtyList::where('id', '!=', 0)->get()->toArray();

		$add_specialty_parm = [
			// ['姓名 : XXXzzz',		'username',     		'title',   TRUE, '', 3, 12, ''],
			// ['姓名',		'username',     	'text_disabled',   TRUE, '', 3, 12, ''],
			// ['單位名稱',	'now_unit',     	'text_disabled',   TRUE, '', 3, 12, ''],
			// ['職稱',		'now_title_id',     'text_disabled',   TRUE, '', 3, 12, ''],

			['姓名',		'username',     	'text_readonly',   TRUE, '', 3, 12, ''],
			['單位名稱',	'now_unit',     	'text_readonly',   TRUE, '', 3, 12, ''],
			['職稱',		'now_title_id',     'text_readonly',   TRUE, '', 3, 12, ''],
			
			// ['單位名稱 : XXX',		'now_unit',     	'title',   TRUE, '', 3, 12, ''],
			
			// ['職稱 : XXX',		'now_title',     		'title',   TRUE, '', 3, 12, ''],
		
			['',			'username',     		'',   TRUE, '', 12, 12, ''],
			['學門專長',	'specialty_id',     		'select',   TRUE, '', 3, 12, '',['id','title']],
			// ['',			'username',     		'',   TRUE, '', 12, 12, ''],
			['學門專長資料來源',	'specialty_source',     		'select',   TRUE, '', 3, 12, '',['id','title']],
			['',			'username',     		'',   TRUE, '', 12, 12, ''],
			['學術專長',	'academic_id',     		'text',   TRUE, '', 3, 12, ''],
			// ['',			'username',     		'',   TRUE, '', 12, 12, ''],
			['學術專長資料來源',	'academic_source',     		'select',   TRUE, '', 3, 12, '',['id','title']],
			['',			'username',     		'',   TRUE, '', 12, 12, ''],
			
		];
		


		// print_r($session_data);exit;
		if ($request->isMethod('post')) {
			$formdata = $this->process_post_data($add_specialty_parm, $request);
			$specialty_list_data=SpecialtyList::find($formdata['specialty_id']);

			// print_r($specialty_data->title);exit;
			// $formdata['writer_id']=$member_id;
			// $formdata['committeeman_id']=$id;
			// $formdata['now_unit_id']=$session_data['now_unit_id'];
			// unset($formdata['username']);
			// unset($formdata['now_unit']);
			// unset($formdata['now_title_id']);
			//新增學術專長
			$academic_data['writer_id']=$member_id;
			$academic_data['committeeman_id']=$id;
			$academic_data['title']=$formdata['academic_id'];
			$academic_data['academic_sources_id']=$formdata['academic_source'];
			$academic_data['create_date']=date('Y/m/d');
			$academic_search=Academic::where('committeeman_id',$academic_data['committeeman_id'])->where('title',$academic_data['title'])->first();
			// print_r($academic_search);exit;
			if(!empty($academic_search)){
				$this->js_output_and_back('已有被推薦的專長');
			}else{
				$res = Academic::updateOrCreate($academic_data);
			}
			//新增學門專長
			$specialty_data['writer_id']=$member_id;
			$specialty_data['committeeman_id']=$id;
			$specialty_data['title_id']=$formdata['specialty_id'];
			$specialty_data['specialty_sources_id']=$formdata['specialty_source'];
			$specialty_data['create_date']=date('Y/m/d');
			$specialty_search=Specialty::where('committeeman_id',$specialty_data['committeeman_id'])->where('title_id',$specialty_data['title_id'])->first();
			if(empty($specialty_search)){
				// print 'is_no';
				$res = Specialty::updateOrCreate($specialty_data);
			}
			
			

			
			// print_r($academic_data);exit;
			// print_r($specialty_data);exit;
			// 新增異動紀錄
			$user_id=$member_id;
			$username=$member_username;
			$action='新增學門專長:'.$specialty_list_data->title.'，'.'新增學術專長:'.$formdata['academic_id'];
			$change_data=array(
				'user_id'  => $user_id,
				'username'  => $username,
				'action'  => $action,
				'committeeman_id'=>$id,
				'committeeman'=>$committeeman_name
			);
			// print_r($change_data);exit;
			$res=ChangeRecord::updateOrCreate($change_data);
			// ChangeRecord::add_change_record($change_data);
			// exit;
			// $res = Committeeman::updateOrCreate($formdata);
			if ($res) {
				$this->js_output_and_redirect('儲存成功', 'mgr.committeeman');
			} else {
				$this->js_output_and_back('編輯發生錯誤');
			}
			exit();
			// $this->js_output_and_redirect('儲存成功', 'mgr.committeeman');
			// print 123;exit;
		}
		$this->data['title'] = "新增外審專家";
		$this->data['parent'] = "標籤";
		$this->data['parent_url'] = route('mgr.committeeman');
		$this->data['action'] = route('mgr.committeeman.add_specialty');
		$this->data['submit_txt'] = '儲存';
		// $this->data['add_txt'] = '查詢';

		// $session_data = json_decode($session_data,true);
		// if(empty($session_data['username'])) $session_data['username']='';
		// if(empty($session_data['now_unit'])) $session_data['now_unit']='';
		// if(empty($session_data['now_title_id'])) $session_data['now_title_id']='';
		
		// $session_data['specialty_id']='';
		// $session_data['specialty_source']='';
		// $session_data['academic_id']='';
		// $session_data['academic_source']='';

		$this->data['params'] = $this->generate_param_to_view($add_specialty_parm,$data);

		

		return view('mgr/template_form', $this->data);
	}
	// 新增專家
	public function add_committeeman(Request $request){
		// print_r($_COOKIE['formdata']);
		
		$member_id=Auth::guard('mgr')->user()->id;
		$member_username=Auth::guard('mgr')->user()->username;
		
		$session_data = $request->session()->all();
		// print_r($session_data);exit;
		
		
		$add_specialty_parm = [
			// ['姓名 : XXXzzz',		'username',     		'title',   TRUE, '', 3, 12, ''],
			// ['姓名',		'username',     	'text_disabled',   TRUE, '', 3, 12, ''],
			// ['單位名稱',	'now_unit',     	'text_disabled',   TRUE, '', 3, 12, ''],
			// ['職稱',		'now_title_id',     'text_disabled',   TRUE, '', 3, 12, ''],

			['姓名',		'username',     	'text_readonly',   TRUE, '', 3, 12, ''],
			['',			'username',     		'',   TRUE, '', 12, 12, ''],
			['單位',	'now_unit_id',     	'text_readonly',   TRUE, '', 3, 12, ''],
			['單位名稱',	'now_unit',     	'text_readonly',   TRUE, '', 3, 12, ''],
			['職稱',		'now_title_id',     'text_readonly',   TRUE, '', 3, 12, ''],
			// ['',			'username',     		'',   TRUE, '', 12, 12, ''],
			// ['姓名',		'username',     	'text',   TRUE, '', 3, 12, ''],
			// ['單位名稱',	'now_unit',     	'text',   TRUE, '', 3, 12, ''],
			// ['職稱',		'now_title_id',     'text',   TRUE, '', 3, 12, ''],
			
			// ['單位名稱 : XXX',		'now_unit',     	'title',   TRUE, '', 3, 12, ''],
			
			// ['職稱 : XXX',		'now_title',     		'title',   TRUE, '', 3, 12, ''],
		
			['',			'username',     		'',   TRUE, '', 12, 12, ''],
			['學門專長',	'specialty_id',     		'select',   TRUE, '', 3, 12, '',['id','title']],
			// ['',			'username',     		'',   TRUE, '', 12, 12, ''],
			['學門專長資料來源',	'specialty_source',     		'select',   TRUE, '', 3, 12, '',['id','title']],
			['',			'username',     		'',   TRUE, '', 12, 12, ''],
			['學術專長',	'academic_id',     		'text',   TRUE, '', 3, 12, ''],
			// ['',			'username',     		'',   TRUE, '', 12, 12, ''],
			['學術專長資料來源',	'academic_source',     		'select',   TRUE, '', 3, 12, '',['id','title']],
			['',			'username',     		'',   TRUE, '', 12, 12, ''],
			
			
		];
		$add_specialty_parm_2 = [
			// ['姓名 : XXXzzz',		'username',     		'title',   TRUE, '', 3, 12, ''],
			// ['姓名',		'username',     	'text_disabled',   TRUE, '', 3, 12, ''],
			// ['單位名稱',	'now_unit',     	'text_disabled',   TRUE, '', 3, 12, ''],
			// ['職稱',		'now_title_id',     'text_disabled',   TRUE, '', 3, 12, ''],

			['姓名',		'username',     	'text_readonly',   TRUE, '', 3, 12, ''],
			['',			'username',     		'',   TRUE, '', 12, 12, ''],
			['單位',		'now_unit_id',     	'text_readonly',   TRUE, '', 3, 12, ''],
			['單位名稱',	'now_unit',     	'text_readonly',   TRUE, '', 3, 12, ''],
			['職稱',		'now_title_id',     'text_readonly',   TRUE, '', 3, 12, ''],
			['',			'username',     		'',   TRUE, '', 12, 12, ''],
			// ['姓名',		'username',     	'text',   TRUE, '', 3, 12, ''],
			['曾任單位',		'old_unit_id',     	'text_readonly',   TRUE, '', 3, 12, ''],
			['曾任單位名稱',	'old_unit',     	'text_readonly',   TRUE, '', 3, 12, ''],
			['曾任職稱',		'old_title_id',     'text_readonly',   TRUE, '', 3, 12, ''],
			
			// ['單位名稱 : XXX',		'now_unit',     	'title',   TRUE, '', 3, 12, ''],
			
			// ['職稱 : XXX',		'now_title',     		'title',   TRUE, '', 3, 12, ''],
		
			['',			'username',     		'',   TRUE, '', 12, 12, ''],
			['學門專長',	'specialty_id',     		'select',   TRUE, '', 3, 12, '',['id','title']],
			// ['',			'username',     		'',   TRUE, '', 12, 12, ''],
			['學門專長資料來源',	'specialty_source',     		'select',   TRUE, '', 3, 12, '',['id','title']],
			['',			'username',     		'',   TRUE, '', 12, 12, ''],
			['學術專長',	'academic_id',     		'text',   TRUE, '', 3, 12, ''],
			// ['',			'username',     		'',   TRUE, '', 12, 12, ''],
			['學術專長資料來源',	'academic_source',     		'select',   TRUE, '', 3, 12, '',['id','title']],
			['',			'username',     		'',   TRUE, '', 12, 12, ''],
			
			
		];
		
		// print_r($session_data);exit;
		if ($request->isMethod('post')) {
			// print_r($_POST['other_title']);exit;
			
			// print_r($other);exit;
			// print_r($session_data['old_unit']);exit;
			
			if(isset($session_data['old_unit']) && $session_data['old_unit']!=''){
				
				$formdata = $this->process_post_data($add_specialty_parm_2, $request);
			}else{
				$formdata = $this->process_post_data($add_specialty_parm, $request);
			}
			$specialty_list_data=SpecialtyList::find($formdata['specialty_id']);
		

			


			// exit;
			$formdata['phone']=$session_data['phone'];;
			$formdata['email']=$session_data['email'];
			$formdata['url']  =$session_data['url'];

			$formdata['member_id']=$member_id;
			$formdata['now_unit_id']=$session_data['now_unit_id'];

			// print_r($session_data['now_title_id']);
			// print_r($formdata['now_title_id']);exit;
			$title_data=JobTitle::find($session_data['now_title_id']);
			if($session_data['now_title_id']==4){
				$other['title']=$formdata['now_title_id'];//其他職稱
			}
			
			$formdata['now_title_id']=$title_data->id;
			
			

			

			if($formdata['now_unit_id']==3){
				$formdata['old_unit_id']=$session_data['old_unit_id'];
				$title_data=JobTitle::find($session_data['old_title_id']);
				$formdata['old_title_id']=$title_data->id;
			}else{
				$request->session()->forget('old_unit');
				$request->session()->forget('old_unit_id');
				$request->session()->forget('old_title_id');
			}
			
			if($formdata['now_title_id']==4){

			}

			// print_r($formdata['now_title_id']);exit;
			// $title_data=JobTitle::find($session_data['old_title_id']);
			// $formdata['old_title_id']=$title_data->id;
			
			$specialty_sources_id=$formdata['specialty_source'];
			$academic_sources_id=$formdata['academic_source'];
			unset($formdata['specialty_source']);
			unset($formdata['academic_source']);
			// print_r($formdata);exit;
			// 新增外審專家
			$res = Committeeman::updateOrCreate($formdata);
			$committeeman_id = $res->id;
			$committeeman_name = $res->username;
			// $committeeman_id = 1;
			// print_r($committeeman_id);exit;
			// 新增學門專長
			$specialty_data['writer_id']=$member_id;
			$specialty_data['committeeman_id']=$committeeman_id;
			$specialty_data['title_id']=$formdata['specialty_id'];
			$specialty_data['specialty_sources_id']=$specialty_sources_id;
			$specialty_data['create_date']=date('Y/m/d');
			$res = Specialty::updateOrCreate($specialty_data);
			// 新增學術專長
			$academic_data['writer_id']=$member_id;
			$academic_data['committeeman_id']=$committeeman_id;
			$academic_data['title']=$formdata['academic_id'];
			$academic_data['academic_sources_id']=$academic_sources_id;
			$academic_data['create_date']=date('Y/m/d');
			$res = Academic::updateOrCreate($academic_data);
			// print_r($academic_data);exit;
			// print_r($specialty_data);exit;
			// 新增異動紀錄
			$user_id=$member_id;
			$username=$member_username;
			$action='新增專家，新增學門專長:'.$specialty_list_data->title.'，'.'新增學術專長:'.$formdata['academic_id'];
			// print_r($action);exit;
			$change_data=array(
				'user_id'  => $user_id,
				'username'  => $username,
				'action'  => $action,
				'committeeman_id'=>$committeeman_id,
				'committeeman'  =>$committeeman_name
			);

			
			//新增其他職稱(有的話)
			if($session_data['now_title_id']==4){
				$other['committeeman_id']=$committeeman_id;
				$other['type']='now';
				// $other['title']=$other['other_title'];
				OtherTitle::updateOrCreate($other);
			}
			//新增其他職稱(有的話)
			if($session_data['old_title_id']==4){
				$other['committeeman_id']=$committeeman_id;
				$other['type']='old';
				// $other['title']=$other['other_title'];
				OtherTitle::updateOrCreate($other);
			}
			// print_r($change_data);exit;
			// $res=ChangeRecord::add_change_record($change_data);
			// DB::table('change_records')->insert(
            //     $change_data
            // );
			$res=ChangeRecord::Create($change_data);
			// $res=ChangeRecord::first()->username;
			// print_r($res);exit;
			
			// dd(DB::getQueryLog());exit;
			
			if ($res) {
				$this->js_output_and_redirect('儲存成功', 'mgr.committeeman');
			} else {
				$this->js_output_and_back('編輯發生錯誤');
			}
			exit();
			// $this->js_output_and_redirect('儲存成功', 'mgr.committeeman');
			// print 123;exit;
		}
		// print 12321333;exit;
		$this->data['title'] = "新增外審專家";
		$this->data['parent'] = "標籤";
		$this->data['parent_url'] = route('mgr.committeeman');
		$this->data['action'] = route('mgr.committeeman.add_committeeman');
		$this->data['submit_txt'] = '儲存';
		// $this->data['add_txt'] = '查詢';

		// $session_data = json_decode($session_data,true);
		$title_data=JobTitle::find($session_data['now_title_id']);
		$session_data['now_title_id']=$title_data->title;
		if($title_data->id==4){
			if(empty($session_data['other_title'])) $this->js_output_and_back('職稱未填寫');
			$session_data['now_title_id']=$session_data['other_title'];
		}else{
			$session_data['now_title_id']=$title_data->title;
		}
		

		$title_data=ServiceUnit::find($session_data['now_unit_id']);
		$session_data['now_unit_id']=$title_data->title;

		if(isset($session_data['old_title_id'])){
			$title_data=JobTitle::find($session_data['old_title_id']);
			// print_r($title_data);exit;
			if($title_data->id==4){
				if(empty($session_data['old_other_title'])) $this->js_output_and_back('曾任職稱未填寫');
				$session_data['old_title_id']=$session_data['old_other_title'];
			}else{
				$session_data['old_title_id']=$title_data->title;
			}
			
			$title_data=ServiceUnit::find($session_data['old_unit_id']);
			$session_data['old_unit_id']=$title_data->title;
		}
		

		// old_other_title

		if(empty($session_data['username'])) $session_data['username']='';
		if(empty($session_data['now_unit'])) $session_data['now_unit']='';
		if(empty($session_data['now_title_id'])) $session_data['now_title_id']='';

		if(empty($session_data['old_unit'])) $session_data['old_unit']='';
		
		$session_data['specialty_id']='';
		$session_data['specialty_source']='';
		$session_data['academic_id']='';
		$session_data['academic_source']='';

		if($session_data['old_unit']!=''){
			// print 12321;exit;
			$this->data['params'] = $this->generate_param_to_view($add_specialty_parm_2,$session_data);
		}else{
			$this->data['params'] = $this->generate_param_to_view($add_specialty_parm,$session_data);
		}
		

		

		return view('mgr/template_form', $this->data);
	}
	public function add(Request $request){
		
		$member_id=Auth::guard('mgr')->user()->id;

		// print_r($member_id);exit;
		if ($request->isMethod('post')) {
			$form_data_all=$request->all();
			// print_r($form_data_all['button_type']);
			// if($form_data_all['button_type']=='search')
			// exit;


			$formdata = $this->process_post_data($this->add_param, $request);
			// print_r($formdata);exit;
			// if($formdata['now_unit']!=''){
			// 	print 123;
			// }else{
			// 	print 444;
			// }
			// exit;
			if($form_data_all['button_type']=='search'){
				$have_data=Committeeman::with('now_title')
				->with('academic')
				->where('username',$formdata['username'])
				->where('status','on')
				// ->where('now_unit',$formdata['now_unit'])
				->get();
				$have_dataJ=(json_decode($have_data));
			}else{
				// print 1231;exit;
				if($formdata['now_unit']=='')$this->js_output_and_back('單位名稱未填寫', 'mgr.committeeman.add');
				$have_data=Committeeman::with('now_title')
				->with('academic')
				->where('username',$formdata['username'])
				->where('status','on')
				->where('now_unit',$formdata['now_unit'])
				->get();
				$have_dataJ=(json_decode($have_data));
			}
			if($formdata['now_unit_id']==3){
				// print 123213;exit;
				if($formdata['old_unit']=='')$this->js_output_and_back('曾任單位名稱未填寫', 'mgr.committeeman.add');
			}else{
				$request->session()->forget('old_unit');
			}
			
			// print_r($have_dataJ);exit;
			
			if( !empty($have_dataJ) && $form_data_all['button_type']=='search' ){
				// print 12312; exit;
				$this->data['data'] = array();
				// print_r($have_data);exit;
				foreach($have_data as $h_data){
				// print 123;exit;
				// $request->session()->forget('username');
				$request->session()->forget('username');
				$request->session()->forget('now_unit');
				$request->session()->forget('now_title_id');
				$request->session()->forget('now_unit_id');

				$request->session()->forget('old_unit');
				$request->session()->forget('old_unit_id');
				$request->session()->forget('old_title_id');
				$i=0;
				$academic_list=array();
				foreach($h_data->academic as $a_title){
					$academic_list[$i]=$a_title->title;
					$i++;
				}
			// 	$academic_data=Academic::where("committeeman_id",$have_data['id'])->get();
			// 	// print_r($academic_data);exit;
			// foreach($academic_data as $a_data){
			// 	$academic_list[$i]=$a_data->title;
			// 	$i++;
			// }
			$academic_data=implode("，",$academic_list);

			$j=0;
			$specialty_list=array();
			foreach($h_data->specialty as $s_title){
				// print_r($s_title->title_id);
				// exit;
				$writer_data=Member::find($s_title->writer_id);
				$specialty_list_data=SpecialtyList::find($s_title->title_id);
				// print_r($writer_data);exit;
				// $academic[$i]['title']=$a_title->title;
				// $academic[$i]['writer_name']=$writer_data->username;
				// $academic[$i]['create_date']=$a_title->create_date;

				$specialty_list[$j]=$specialty_list_data->title;
				$j++;
			}
			$specialty_data=implode("、",$specialty_list);
			// print_r($academic_data);exit;
			// $academic_data=implode("、",$academic_list);
				// print 'have';exit;
				$this->data['controller'] = 'committeeman';
				$this->data['title'] = "專家清單";
				$this->data['parent'] = "";
				$this->data['parent_url'] = "";
				$this->data['th_title'] = $this->th_title_field(
				
					[
						['#', '', ''],
						['姓名', '', ''],
						// ['服務單位', '', ''],
						['單位名稱', '', ''],
						// ['曾任職單位', '', ''],
						// ['單位名稱', '', ''],
						['職稱', '', ''],
						// ['曾任', '', ''],
						// ['單位名稱', '', ''],
						['學門專長', '', ''],
						['學術專長', '', ''],
						['連絡電話', '', ''],
						['電子郵件信箱', '', ''],
						['相關資料網址', '', ''],
						['', '', ''],

					]
				);
				// $this->data['bar_btns'] = [
				// 	['新增專長', 'window.open(\''.route('mgr.committeeman.add_specialty').'\');', 'primary', '2'],
				// 	// ['列印+保存到伺服器', 'window.open(\''.route('mgr.committeeman.search').'\');', 'primary', '2']
				// ];
				// $this->data['btns'] = [
				// 	['新增專長', '新增推薦資料', route('mgr.recommend_form.add'), 'primary','2']
				// ];
				$this->data['type']='search_have';
				$this->data['template_item'] = 'mgr/items/search_have_item';
				$data = array();
				$data = $h_data;
				// print_r($data->username);exit;
				
				$x=1;
				// foreach ($data as $item) {
					$obj = array();
					$obj[] = $x;
					$obj[] = $data->username;
					$obj[] = $data->now_unit;
					$obj[] = $h_data->now_title->title;
					// $obj[] = '政大';
					// $obj[] = '教授';
					$obj[] = $specialty_data;
					$obj[] = $academic_data;
					$obj[] = $data->phone;
					$obj[] = $data->email;
					$obj[] = $data->url;
					// $obj[] = '分析化學....';
					// $obj[] = '自然科學類';

					$priv_edit = true;
					$priv_del = false;
					$priv_verified=false;
					$priv_block=false;
					$priv_reset_pwd=false;
					$priv_reset_pwd_zero=false;
					$priv_reset_pwd_ext=false;
					// $this->data['submit_search_txt'] = '查詢';
					$this->data['data'][] = array(
						"id"    =>  $data->id,
						"data"  =>   $obj,
						"priv_edit"  => $priv_edit,
						"priv_del"   => $priv_del,
						"priv_verified" => $priv_verified,
						"priv_block" => $priv_block,
						"priv_reset_pwd" => $priv_reset_pwd,
						"priv_reset_pwd_zero" => $priv_reset_pwd_zero,
						"priv_reset_pwd_ext" => $priv_reset_pwd_ext,
					);
					// return view('mgr/template_list', $this->data);
					// $i++;
					

					// print_r($this->data['data']);exit;
				}
				
				return view('mgr/template_list', $this->data);
			}elseif(!empty($have_dataJ) && $form_data_all['button_type']=='add'){

				$this->js_output_and_back('此專家已存在');

			}elseif($form_data_all['button_type']=='search'){
				// print 12321;exit;
				$this->js_output_and_back('查詢此人不存在於系統中');
			}else{
				// print 'no_have';
				// print_r($formdata);exit;
				$request->session()->put('username', $formdata['username']);
				$request->session()->put('now_unit', $formdata['now_unit']);
				$request->session()->put('now_title_id', $formdata['now_title_id']);
				$request->session()->put('now_unit_id', $formdata['now_unit_id']);

				$request->session()->put('phone', $formdata['phone']);
				$request->session()->put('email', $formdata['email']);
				$request->session()->put('url', $formdata['url']);

				if(isset($form_data_all['other_title'])){
					$request->session()->put('other_title', $form_data_all['other_title']);
				}
				if(isset($form_data_all['old_unit'])){
					$request->session()->put('old_unit', $form_data_all['old_unit']);
				}
				if(isset($form_data_all['old_unit_id'])){
					$request->session()->put('old_unit_id', $form_data_all['old_unit_id']);
				}
				if(isset($form_data_all['old_title_id'])){
					$request->session()->put('old_title_id', $form_data_all['old_title_id']);
				}
				if(isset($form_data_all['old_other_title'])){
					$request->session()->put('old_other_title', $form_data_all['old_other_title']);
				}
				
				// $request->session()->put('old_unit', $formdata['old_unit']);
				// $request->session()->put('old_title_id', $formdata['old_title_id']);
				// $request->session()->put('old_unit_id', $formdata['old_unit_id']);
				// session(['username' => $formdata['username']]);
				// session(['now_unit' => $formdata['now_unit']]);
				// session(['not_title' => '教授']);
				$request->session()->save();
				// $data = $request->session()->all();
				// $request->session()->flush('not_unit');
				// print_r($data);
				// exit;
				$this->js_output_and_next('查無資料', 'mgr.committeeman.add_committeeman');
			}
		}

		$request->session()->forget('other_title');

		$this->data['title'] = "新增外審專家";
		$this->data['parent'] = "標籤";
		$this->data['parent_url'] = route('mgr.committeeman');
		$this->data['action'] = route('mgr.committeeman.add');
		$this->data['submit_txt'] = '新增';
		$this->data['submit_search_txt'] = '查詢';

		$this->data['params'] = $this->generate_param_to_view($this->add_param);
		
		return view('mgr/template_form', $this->data);
	}

	public function switch_toggle(Request $request){
		if ($request->isMethod('post')) {
			
			$id     = $request->post('id');
			$status = $request->post('status');
			// print_r($status);exit;
			if (Committeeman::where(['id'=>$id])->update(['status'=>$status])) {
				$this->output(TRUE, "success");
			}else{
				$this->output(FALSE, "fail");
			}
		}
	}
	public function department_add(request $request){
		// print 123;
		if ($request->isMethod('post')) {

			$this->js_output_and_redirect('新增成功', 'mgr.committeeman.department_manage');
			// print 123;
			// $formdata = $this->process_post_data($this->department_param, $request);
			$this->data['title'] = "新增管理系所";
			$this->data['parent'] = "系所管理";
			$this->data['parent_url'] = route('mgr.committeeman');
			$this->data['action'] = route('mgr.committeeman');
			$this->data['submit_txt'] = '新增';

		$this->data['params'] = $this->generate_param_to_view($this->department_param);
		

		return view('mgr/template_form', $this->data);
			// $this->department_manage($request);
			// exit;
			// print_r($formdata);exit;

			// if($formdata['username']=='王大文'){
				$this->data['controller'] = 'committeeman';
				$this->data['title'] = "專家清單~";
				$this->data['parent'] = "";
				$this->data['parent_url'] = "";
				$this->data['th_title'] = $this->th_title_field(
				
					[
						['#', '', ''],
						['姓名', '', ''],
						// ['服務單位', '', ''],
						['單位名稱', '', ''],
						['曾任職單位', '', ''],
						['單位名稱', '', ''],
						['職稱', '', ''],
						// ['曾任', '', ''],
						// ['單位名稱', '', ''],
						['專長', '', ''],
						['連絡電話', '', ''],
						['電子郵件信箱', '', ''],
						['相關資料網址', '', ''],

					]
				);
				$this->data['bar_btns'] = [
					['新增專長', 'window.open(\''.route('mgr.committeeman.add_specialty').'\');', 'primary', '2'],
					// ['列印+保存到伺服器', 'window.open(\''.route('mgr.committeeman.search').'\');', 'primary', '2']
				];
				// $this->data['btns'] = [
				// 	['新增專長', '新增推薦資料', route('mgr.recommend_form.add'), 'primary','2']
				// ];
				$this->data['type']='search_have';
				$this->data['template_item'] = 'mgr/items/template_item';
				$data = array();
				$data = array("123");
				$this->data['data'] = array();
				foreach ($data as $item) {
					$obj = array();
					$obj[] = 1;
					$obj[] = '王大文';
					$obj[] = '台大';
					$obj[] = '公立學校';
					$obj[] = '政大';
					$obj[] = '教授';
					$obj[] = '化學';
					$obj[] = '0912345678';
					$obj[] = 'XXX@gmail.com';
					$obj[] = 'www.xxx.com';
					// $obj[] = '分析化學....';
					// $obj[] = '自然科學類';

					$priv_edit = false;
					$priv_del = false;
					$priv_verified=false;
					$priv_block=false;
					$priv_reset_pwd=false;
					$priv_reset_pwd_zero=false;
					$priv_reset_pwd_ext=false;
					$this->data['data'][] = array(
						"id"    =>  1,
						"data"  =>   $obj,
						"priv_edit"  => $priv_edit,
						"priv_del"   => $priv_del,
						"priv_verified" => $priv_verified,
						"priv_block" => $priv_block,
						"priv_reset_pwd" => $priv_reset_pwd,
						"priv_reset_pwd_zero" => $priv_reset_pwd_zero,
						"priv_reset_pwd_ext" => $priv_reset_pwd_ext,
					);
					// return view('mgr/template_list', $this->data);
				}
				return view('mgr/template_list', $this->data);
			
				
		
			

			
		}

		$this->data['title'] = "新增管理系所";
		$this->data['parent'] = "系所管理";
		$this->data['parent_url'] = route('mgr.committeeman');
		$this->data['action'] = route('mgr.committeeman.department_add');
		$this->data['submit_txt'] = '新增';

		$this->data['params'] = $this->generate_param_to_view($this->department_param);
		

		return view('mgr/template_form', $this->data);
	}
	public function department_manage(request $request){
		$this->data['controller'] = 'committeeman';
		$this->data['title']      = "選擇管理科系";
		$this->data['parent']     = "";
		$this->data['parent_url'] = "";
		$this->data['th_title']   = $this->th_title_field(
			[
				['#', '', ''],
				['姓名', '', ''],
				['帳號', '', ''],
				['管理系所', '', ''],
				['狀態', '', ''],
				['動作', '', ''],
				
			]
		);
		$this->data['btns'] = [
			['<i class="ri-add-fill"></i>', '新增管理科系', route('mgr.committeeman.department_add'), 'primary']
		];
		$this->data['type']='';
		$this->data['template_item'] = 'mgr/items/template_item';

		$data = array();
		// $data = Member::with('privilege')->with('department')->get();
		// $role = Auth::guard('mgr')->user()->role;

		$this->data['data'] = array();
		foreach ($data as $item) {
		
			$obj = array();
	
            $priv_edit = TRUE;
			$priv_del = TRUE;
			$other_btns = array();
			
			$this->data['data'][] = array(
				"id"         => 1,
				"data"       => $obj,
                "priv_edit"  => $priv_edit,
                "priv_del"   => $priv_del,
				// "other_btns" => $other_btns
			);
		}
             
            $obj[] = '1';
            $obj[] = 'admin';
            $obj[] = 'admin';
            $obj[] = '教育系、經濟系';
            $obj[] = '啟用';
			
			// $obj[]  = '';
            
            $priv_edit = true;
			$priv_del = false;

            $this->data['data'][] = array(
				"id"         => 1,
				"data"       => $obj,
                "priv_edit"  => $priv_edit,
                "priv_del"   => $priv_del,
				// "other_btns" => $other_btns
			);

        // print_r($this->data['data']);exit;
		return view('mgr/template_list', $this->data);
	}
	public function modification_record(request $request){
		$this->data['controller'] = 'recommend_form';
		$this->data['title']      = "異動紀錄";
		$this->data['parent']     = "";
		$this->data['parent_url'] = "";
		$this->data['th_title']   = $this->th_title_field(
			[
				['#', '', ''],
				['專家名稱', '', ''],
				['異動資料', '', ''],
				['異動人', '', ''],
				['異動時間', '', ''],
				
			]
		);
		// $this->data['btns'] = [
		// 	['<i class="ri-add-fill"></i>', '新增推薦資料', route('mgr.recommend_form.add'), 'primary']
		// ];
		$this->data['type']='';
		$this->data['template_item'] = 'mgr/items/template_item';

		$data = array();
		// $data = Member::with('privilege')->with('department')->get();
		// $role = Auth::guard('mgr')->user()->role;

		$this->data['data'] = array();
		foreach ($data as $item) {
		
			$obj = array();
	
            $priv_edit = TRUE;
			$priv_del = TRUE;
			$other_btns = array();
			
			$this->data['data'][] = array(
				"id"         => 1,
				"data"       => $obj,
                "priv_edit"  => $priv_edit,
                "priv_del"   => $priv_del,
				// "other_btns" => $other_btns
			);
		}
             
            $obj[] = '1';
            $obj[] = 'XXX';
            $obj[] = '新增專家';
            $obj[] = 'XXX';
            $obj[] = '2023/4/1 11:00';
			
			// $obj[]  = '';
            
            $priv_edit = false;
			$priv_del = false;

            $this->data['data'][] = array(
				"id"         => 1,
				"data"       => $obj,
                "priv_edit"  => $priv_edit,
                "priv_del"   => $priv_del,
				// "other_btns" => $other_btns
			);

        // print_r($this->data['data']);exit;
		return view('mgr/template_list', $this->data);
	}
	public function edit(Request $request, $id){
		$member_id=Auth::guard('mgr')->user()->id;
		$member_username=Auth::guard('mgr')->user()->username;
		
		$data=Committeeman::find($id);
		$committeeman_name=$data->username;

		// print_r($data);exit;
		if($data->now_title_id==4){
			$other=OtherTitle::where('committeeman_id', $id)->where('type','now')->first();
			// print_r($other->id);exit;
			$data['now_other_title_id']=$other->id;
			$test=$this->data['select']['now_other_title_id'] = OtherTitle::where('committeeman_id', $id)->where('type','now')->orwhere('committeeman_id',0)->get()->toArray();
		}
		if($data->old_title_id==4){
			$other=OtherTitle::where('committeeman_id', $id)->where('type','old')->first();
			// print_r($other->id);exit;
			$data['old_other_title_id']=$other->id;
			$test=$this->data['select']['old_other_title_id'] = OtherTitle::where('committeeman_id', $id)->where('type','old')->orwhere('committeeman_id',0)->get()->toArray();
		}
	
		
		// $test_1=$this->data['select']['old_other_title_id'] = OtherTitle::where('committeeman_id', $id)->where('type','old')->orwhere('committeeman_id',0)->get()->toArray();

		// print_r($test_1);exit;
		// $session_data = $request->session()->all();
		// 	print_r($_POST);exit;
		// print_r($data);exit;
		// print_r($data->username);exit;
		if ($request->isMethod('post')) {
			if($data->old_unit!=''){
				// print 123;exit;
			// if($data->now_unit_id==3){
				if($data->now_title_id==4 && $data->old_title_id==4){
					$formdata = $this->process_post_data($this->edit_param_4, $request);
					if(isset($_POST['other_test'])) $old_other_title=$_POST['other_test'];

					if($formdata['now_other_title_id']==4){
						
						if(empty($_POST['other_title'])) $this->js_output_and_back('職稱未填寫');
						$now_other_title=$_POST['other_title'];
						$type='now';
						// print_r($now_other_title);
					}elseif(isset($formdata['now_other_title_id']) &&  $formdata['now_other_title_id']<4){
						$formdata['now_title_id']=$formdata['now_other_title_id'];
						if($formdata['now_title_id']>4) $formdata['now_title_id']=4;
					}else{
						
						$formdata['now_title_id']=$formdata['now_other_title_id'];
						if($formdata['now_title_id']>4) $formdata['now_title_id']=4;
						$O_title=OtherTitle::find($formdata['now_other_title_id']);
						$now_other_title=$O_title['title'];
					}
					

					if(isset($formdata['old_other_title_id']) && $formdata['old_other_title_id']==4){
						
						if(empty($_POST['other_test'])) $this->js_output_and_back('職稱未填寫');
						$old_other_title=$_POST['other_test'];
						$type='old';
						// print_r($now_other_title);
					}elseif(isset($formdata['old_other_title_id']) &&  $formdata['old_other_title_id']<4){
						$formdata['old_title_id']=$formdata['old_other_title_id'];
						if($formdata['old_title_id']>4) $formdata['old_title_id']=4;
					}else{
						$formdata['old_title_id']=$formdata['old_other_title_id'];
						if($formdata['old_title_id']>4) $formdata['old_title_id']=4;
						$O_title=OtherTitle::find($formdata['old_other_title_id']);
						$old_other_title=$O_title['title'];
					}
					
				}
				
				elseif($data->now_title_id==4){
					// print 477756;exit;

					$formdata = $this->process_post_data($this->edit_param_5, $request);
					if(isset($_POST['other_test'])) $old_other_title=$_POST['other_test'];
					

					if($formdata['now_other_title_id']==4){
						
						if(empty($_POST['other_title'])) $this->js_output_and_back('職稱未填寫');
						$now_other_title=$_POST['other_title'];
						$type='now';
						// print_r($now_other_title);
					}elseif(isset($formdata['now_other_title_id']) &&  $formdata['now_other_title_id']<4){
						$formdata['now_title_id']=$formdata['now_other_title_id'];
						// print  66;exit;
						// $j_title=JobTitle::find($formdata['now_other_title_id']);
						// $now_other_title=$j_title['title'];
						if($formdata['now_title_id']>4) $formdata['now_title_id']=4;
					}else{
						// print  6677;exit;
							// print_r($_POST);exit;
						$formdata['now_title_id']=$formdata['now_other_title_id'];
						if($formdata['now_title_id']>4) $formdata['now_title_id']=4;

						$O_title=OtherTitle::find($formdata['now_other_title_id']);
						$now_other_title=$O_title['title'];
						// $now_other_title=$_POST['other_title'];
					}
					
				}elseif($data->old_title_id==4){
					$formdata = $this->process_post_data($this->edit_param_6, $request);
					if(isset($_POST['other_title'])) $now_other_title=$_POST['other_title'];

					// $O_title=OtherTitle::find($formdata['now_other_title_id']);
					// $now_other_title=$O_title['title'];

					if(isset($formdata['old_other_title_id']) && $formdata['old_other_title_id']==4){
						
						if(empty($_POST['other_test'])) $this->js_output_and_back('職稱未填寫');
						$old_other_title=$_POST['other_test'];
						$type='old';
						// print_r($now_other_title);
					}elseif(isset($formdata['old_other_title_id']) &&  $formdata['old_other_title_id']<4){
						$formdata['old_title_id']=$formdata['old_other_title_id'];
						if($formdata['old_title_id']>4) $formdata['old_title_id']=4;
					}else{
						$formdata['old_title_id']=$formdata['old_other_title_id'];
						if($formdata['old_title_id']>4) $formdata['old_title_id']=4;
						$O_title=OtherTitle::find($formdata['old_other_title_id']);
						$old_other_title=$O_title['title'];
					}

				}else{
					// print_r($_POST);exit;
					// print 999;exit;
					$formdata = $this->process_post_data($this->edit_param_2, $request);
					// if(empty($_POST['other_title'])) $this->js_output_and_back('職稱未填寫');
					if(isset($_POST['other_title'])) $now_other_title=$_POST['other_title'];
					
					if(isset($_POST['other_test'])) $old_other_title=$_POST['other_test'];
					// if(empty($_POST['other_test'])) $this->js_output_and_back('曾任職稱未填寫');
					// $old_other_title=$_POST['other_test'];
				}
				
				
			}else{
				// print 456;exit;
				if($data->now_title_id==4){
					// print_r($formdata);
					// print 456;exit;
					$formdata = $this->process_post_data($this->edit_param_3, $request);
					if(isset($_POST['old_other_title'])) $old_other_title=$_POST['old_other_title'];
					// if($formdata['now_other_title_id']!=4){
					// 	$other=OtherTitle::find($formdata['now_other_title_id']);
					// 	// $now_other_title=$other['title'];
					// }else{
					// 	$now_other_title=$_POST['other_title'];
					// }

					if($formdata['now_other_title_id']==4){
						
						if(empty($_POST['other_title'])) $this->js_output_and_back('職稱未填寫');
						$now_other_title=$_POST['other_title'];
						// print_r($now_other_title);
					}elseif($formdata['now_other_title_id']<4){
						$formdata['now_title_id']=$formdata['now_other_title_id'];
						if($formdata['now_title_id']>4) $formdata['now_title_id']=4;
					}
					

					// print_r($other->title);exit;
				}else{
					// print_r($_POST);
					// print 1231;exit;
					$formdata = $this->process_post_data($this->edit_param, $request);
					if(isset($_POST['other_title'])) $now_other_title=$_POST['other_title'];
					if(isset($_POST['old_other_title'])) $old_other_title=$_POST['old_other_title'];
					// if(empty($_POST['other_title'])) $this->js_output_and_back('職稱未填寫');
					// $now_other_title=$_POST['other_title'];
				}
				
			}
			
			// $formdata = $this->process_post_data($this->edit_param, $request);
			// foreach($formdata as $f_data){
			// 	print_r()
			// }
			// print_r($_POST);exit;
			// if()
			// print_r($formdata);exit;
			// print_r($_POST['other_title']);exit;
			
			$change_data=array();
			if(isset($now_other_title)){
				
				$other=OtherTitle::where('committeeman_id',$id)->where('type','now')->first();
				if(isset($other)){
					if($other->title!=$now_other_title){
						$change_data[]= '修改職稱:'.$now_other_title;
					}
				}else{
					$change_data[]= '修改職稱:'.$now_other_title;
				}
				
				
				
			}elseif(isset($formdata['now_other_title_id'])){
				// print_r($_POST);exit;;
				// print_r($formdata['now_other_title_id']);exit;
				$other=OtherTitle::where('committeeman_id',$id)->where('type','now')->first();
				// print_r($other->title);
				$res=OtherTitle::find($formdata['now_other_title_id']);
				// print_r($res->title);
				// exit;
				if($other->title!=$res->title){
					$change_data[]= '修改職稱:'.$res->title;
				}
			}else{
				// print 555;exit;
				if(isset($formdata['now_title_id'])){
					if($formdata['now_title_id']!=$data->now_title_id){
						$res=JobTitle::find($formdata['now_title_id']);
						$change_data[]= '修改職稱:'.$res->title;
					}
				}
				
			}
			if($formdata['username']!=$data->username){
				$change_data[]= '修改姓名:'.$formdata['username'];	
			}
			if($formdata['now_unit_id']!=$data->now_unit_id){
				$res=ServiceUnit::find($formdata['now_unit_id']);
				$change_data[]= '修改單位:'.$res->title;	
			}
			if($formdata['now_unit']!=$data->now_unit){
				$change_data[]= '修改單位名稱:'.$formdata['now_unit'];
			}
			// if($formdata['now_title_id']!=$data->now_title_id){
			// 	$res=JobTitle::find($formdata['now_title_id']);
			// 	$change_data[]= '修改職稱:'.$res->title;
			// }
			if($formdata['old_unit_id']!=$data->old_unit_id){
				$res=ServiceUnit::find($formdata['old_unit_id']);
				if($res){
					$change_data[]= '修改曾任單位:'.$res->title;
				}
				
			}
			if($formdata['old_unit']!=$data->old_unit){
				$change_data[]= '修改曾任單位名稱:'.$formdata['old_unit'];
			}
			
			if(isset($old_other_title)){
				$other=OtherTitle::where('committeeman_id',$id)->where('type','old')->first();
				// $res=OtherTitle::find($formdata['old_other_title_id']);
				if(isset($other)){
					if($other->title!=$old_other_title){
						$change_data[]= '修改曾任職稱:'.$old_other_title;
					}
				}else{
					$change_data[]= '修改曾任職稱:'.$old_other_title;
				}
				
				
				// print_r($change_data);exit;
			}elseif(isset($formdata['old_other_title_id'])){
				$other=OtherTitle::where('committeeman_id',$id)->where('type','old')->first();
				$res=OtherTitle::find($formdata['old_other_title_id']);
				if($other->title!=$res->title){
					$change_data[]= '修改曾任職稱:'.$res->title;
				}
				// print_r($change_data);exit;
			}else{
				if($formdata['old_title_id']!=$data->old_title_id){
					$res=JobTitle::find($formdata['old_title_id']);
					if($res){
						$change_data[]= '修改曾任職稱:'.$res->title;
					}
				}
				// print_r('1'.$change_data);exit;
			}
			
			if($formdata['email']!=$data->email){
				$change_data[]= '修改電子郵件信箱:'.$formdata['email'];
			}
			if($formdata['phone']!=$data->phone){
				$change_data[]= '修改連絡電話:'.$formdata['phone'];
			}
			if($formdata['url']!=$data->url){
				$change_data[]= '修改相關資料網址:'.$formdata['url'];
			}
			
		
                $change_list=implode("、",$change_data);
      
			

			
				
			// print_r($formdata);exit;
			// print_r($formdata['username']);exit;
			if($change_list!=''){
				// 新增異動紀錄
				$user_id=$member_id;
				$username=$member_username;
				$action=$change_list;
				// print_r($action);exit;
				$change_data=array(
					'user_id'  => $user_id,
					'username'  => $username,
					'action'  => $action,
					'committeeman_id'=>$id,
					'committeeman'  =>$committeeman_name
				);
				$res=ChangeRecord::Create($change_data);
			}
			// 其他的職稱 other_title
			// $res=OtherTitle::find($id);
			// $res = OtherTitle::updateOrCreate(['id'=>$id], $formdata);
			// print_r(!empty($change_data));exit;
			if(!empty($change_data)) $res = Committeeman::updateOrCreate(['id'=>$id], $formdata);
			

			//新增其他職稱(有的話)
			if(isset($formdata['now_other_title_id']) && $formdata['now_other_title_id']==4){
				$type='now';
				$other_data['committeeman_id']=$id;
				$other_data['title']=$now_other_title;
				$other_data['type']=$type;
				if(OtherTitle::where('committeeman_id',$id)->where('type',$type)->count() > 0){
					OtherTitle::updateOrCreate(['committeeman_id'=>$id,'type'=>$type], $other_data);
				}else{
					OtherTitle::updateOrCreate($other_data);
				}

				
				
				// print_r($other);exit;
				
			}
			if(isset($formdata['now_title_id']) && $formdata['now_title_id']==4){
				$type='now';
				$other_data['committeeman_id']=$id;
				$other_data['title']=$now_other_title;
				$other_data['type']=$type;
				if(OtherTitle::where('committeeman_id',$id)->where('type',$type)->count() > 0){
					OtherTitle::updateOrCreate(['committeeman_id'=>$id,'type'=>$type], $other_data);
				}else{
					OtherTitle::updateOrCreate($other_data);
				}

				
				
				// print_r($other);exit;
				
			}
			if(isset($formdata['old_other_title_id']) && $formdata['old_other_title_id']==4){
		
				$type='old';
				$other_data['committeeman_id']=$id;
				$other_data['title']=$old_other_title;
				$other_data['type']=$type;
				if(OtherTitle::where('committeeman_id',$id)->where('type',$type)->count() > 0){
					// print 123;exit;
					OtherTitle::updateOrCreate(['committeeman_id'=>$id,'type'=>$type], $other_data);
				}else{
					// print 456;exit;
					OtherTitle::updateOrCreate($other_data);
				}
				
				// print_r($other);exit;
			}
			if(isset($formdata['old_title_id']) && $formdata['old_title_id']==4){
				// print_r($old_other_title);exit;
				$type='old';
				$other_data['committeeman_id']=$id;
				$other_data['title']=$old_other_title;
				$other_data['type']=$type;
				if(OtherTitle::where('committeeman_id',$id)->where('type',$type)->count() > 0){
					// print 123;exit;
					OtherTitle::updateOrCreate(['committeeman_id'=>$id,'type'=>$type], $other_data);
				}else{
					// print 456;exit;
					OtherTitle::updateOrCreate($other_data);
				}
				
				// print_r($other);exit;
			}

			// $res = OtherTitle::updateOrCreate(['committeeman_id'=>$id], $formdata);
			if (empty($change_data)) {
				$this->js_output_and_redirect('編輯成功!', 'mgr.committeeman');
			}elseif($res){
				$this->js_output_and_redirect('編輯成功', 'mgr.committeeman');
			} 
			else {
				$this->js_output_and_back('編輯發生錯誤');
			}
			exit();
		}
		// $2y$10$4366i0NSO7jboKNbaVLkFubgYg5qnovDQWFks4uPKW0zZ7nIpfDue
		$this->data['title'] = "編輯推薦資料 ";
		$this->data['parent'] = "推薦資料列表";
		$this->data['parent_url'] = route('mgr.committeeman');
		$this->data['action'] = route('mgr.committeeman.edit', ['id'=>$id]);
		$this->data['submit_txt'] = '確認編輯';

		
		// print_r($data);exit;
		if($data->old_unit!=''){
		// if($data->now_unit_id==3){
			if($data->old_title_id==4 && $data->now_title_id==4){
				// print 123;exit;
				// print 1288885;exit;
				$this->data['params'] = $this->generate_param_to_view($this->edit_param_4, $data);//有現任 4 曾任 4 和 有其他職稱
			
			}elseif($data->old_title_id==4){
				// print 123;exit;
				// print 1288885;exit;
				$this->data['params'] = $this->generate_param_to_view($this->edit_param_6, $data);//有 曾任4 和 有其他職稱
			}elseif($data->now_title_id==4){
				// print 123;exit;
				// print 1288885;exit;
				$this->data['params'] = $this->generate_param_to_view($this->edit_param_5, $data);//有現任4 曾任 和 有其他職稱
			}else{
				// print 444;exit;
				$this->data['params'] = $this->generate_param_to_view($this->edit_param_2, $data); //有曾任
			}
			
			
		}else{
			
			if($data->now_title_id==4){
				// print 123;exit;
				$this->data['params'] = $this->generate_param_to_view($this->edit_param_3, $data); //無曾任 和 有其他職稱
			}else{
				// print 123555;exit;
				$this->data['params'] = $this->generate_param_to_view($this->edit_param, $data);//無曾任
			}
			
		}

		// if($data->now_title_id==4){
		// 	$this->data['params'] = $this->generate_param_to_view($this->edit_param_2, $data);
		// }else{
		// 	$this->data['params'] = $this->generate_param_to_view($this->edit_param, $data);
		// }
		
	
		
		
		// $this->data['params'] = $this->generate_param_to_view($this->param, $data);

		// print_r($this->data);exit;
		
		//使用者僅要撈出自己管的＆尚未指派
		$this->data['select']['users'] = array();

		// $session_data['other_title']=$title_data->title;
		

		
		

		return view('mgr/template_form', $this->data);
	}

	public function edit_academics_old(Request $request, $id){

		// print 123213;exit;
		$member_id=Auth::guard('mgr')->user()->id;
		$member_username=Auth::guard('mgr')->user()->username;
		
		// $data=Committeeman::find($id);
		$data=Academic::where('committeeman_id',$id)->get();
		foreach($data as $da){
			print_r($da->title);
		}
		exit;
		print_r($data);exit;
		if ($request->isMethod('post')) {
			$formdata = $this->process_post_data($this->edit_param, $request);
			// 新增異動紀錄
			// $user_id=$member_id;
			// $username=$member_username;
			// $action=$change_list;
			// // print_r($action);exit;
			// $change_data=array(
			// 	'user_id'  => $user_id,
			// 	'username'  => $username,
			// 	'action'  => $action,
			// 	'committeeman_id'=>$id,
			// 'committeeman'  =>$committeeman_name
			// );
			// $res=ChangeRecord::Create($change_data);
			exit;
			$res = Committeeman::updateOrCreate(['id'=>$id], $formdata);
			if ($res) {
				$this->js_output_and_redirect('編輯成功', 'mgr.committeeman');
			} else {
				$this->js_output_and_back('編輯發生錯誤');
			}
			exit();
		}
			
		$this->data['title'] = "編輯學術專長 ";
		$this->data['parent'] = "推薦資料列表";
		$this->data['parent_url'] = route('mgr.committeeman');
		$this->data['action'] = route('mgr.committeeman.edit_academics', ['id'=>$id]);
		$this->data['submit_txt'] = '確認編輯';

		
		
		// print_r($data->now_unit_id);exit;
		// if($data->now_unit_id==3){
		// 	$this->data['params'] = $this->generate_param_to_view($this->edit_param_2, $data);
		// }else{
		// 	$this->data['params'] = $this->generate_param_to_view($this->edit_param, $data);
		// }
	
		$this->data['params'] = $this->generate_param_to_view($this->edit_param, $data);
		
		// $this->data['params'] = $this->generate_param_to_view($this->param, $data);

		// print_r($this->data);exit;
		
		//使用者僅要撈出自己管的＆尚未指派
		$this->data['select']['users'] = array();
		

		
		
		return view('mgr/template_list', $this->data);
		// return view('mgr/template_form', $this->data);
	}

	public function del(Request $request){
		$id = $request->post('id');

		$obj = Member::find($id);
		if ($obj->delete()) {
			$this->output(TRUE, "Delete success");
		}else{
			$this->output(FALSE, "Delete fail");
		}
	}
	public function del_academics(Request $request){
		$id = $request->post('id');

		$member_id=Auth::guard('mgr')->user()->id;
		$member_username=Auth::guard('mgr')->user()->username;
		
		$data=Academic::find($id);
		$committeeman_id=$data->committeeman_id;

		$data_com=Committeeman::find($committeeman_id);
		$committeeman_name=$data_com->username;
		// $committeeman_name=$data->username;
		$change_data= '刪除學術專長: '.$data['title'];	

		// print_r($change_data);exit;

				// 新增異動紀錄
				$user_id=$member_id;
				$username=$member_username;
				$action=$change_data;
				// print_r($action);exit;
				$change_data=array(
					'user_id'  => $user_id,
					'username'  => $username,
					'action'  => $action,
					'committeeman_id'=>$committeeman_id,
					'committeeman'  =>$committeeman_name
				);
				$res=ChangeRecord::Create($change_data);

		$obj = Academic::find($id);
		if ($obj->delete()) {
			$this->output(TRUE, "Delete success");
		}else{
			$this->output(FALSE, "Delete fail");
		}
	}


	public function del_specialty(Request $request){

		// print 555;exit;
		$id = $request->post('id');

		$member_id=Auth::guard('mgr')->user()->id;
		$member_username=Auth::guard('mgr')->user()->username;
		
		$specialty=Specialty::find($id);
		$committeeman_id=$specialty->committeeman_id;
		$data=SpecialtyList::find($specialty->title_id);
		// print_r($data['title']);exit;

		$data_com=Committeeman::find($committeeman_id);
		$committeeman_name=$data_com->username;

		$change_data= '刪除學門專長: '.$data['title'];	

		// print_r($change_data);exit;

				// 新增異動紀錄
				$user_id=$member_id;
				$username=$member_username;
				$action=$change_data;
				// print_r($action);exit;
				$change_data=array(
					'user_id'  => $user_id,
					'username'  => $username,
					'action'  => $action,
					'committeeman_id'=>$committeeman_id,
					'committeeman'  =>$committeeman_name
				);
				$res=ChangeRecord::Create($change_data);

		$obj = Specialty::find($id);
		if ($obj->delete()) {
			$this->output(TRUE, "Delete success");
		}else{
			$this->output(FALSE, "Delete fail");
		}
	}
	public function edit_specialty(Request $request, $id){
		// print 123;exit;
		// print_R($id);exit;
		$member_id=Auth::guard('mgr')->user()->id;
		$member_username=Auth::guard('mgr')->user()->username;

		$specialty=Specialty::find($id);
		$committeeman_id=$specialty->committeeman_id;
		$data=SpecialtyList::find($specialty->title_id);
		// print_r($data->id);exit;

		$data_com=Committeeman::find($committeeman_id);
		
		$committeeman_name=$data_com->username;
		$data_arr = $data_com->toArray();
		$id_arr=$data_arr['id'];
		unset($data_arr['id']);
		unset($data_arr['created_at']);
		unset($data_arr['updated_at']);
		unset($data_arr['status']);
		unset($data_arr['specialty_source']);
		unset($data_arr['academic_id']);
		unset($data_arr['academic_source']);
		unset($data_arr['specialty_id']);
		unset($data_arr['member_id']);
		unset($data_arr['deleted_at']);
		$data_arr['updated_at']=now();

		// unset($data_arr['phone']);
		// unset($data_arr['email']);
		// unset($data_arr['url']);
		// unset($data_arr['old_unit']);
		// print_r($data_arr);exit;
		// print_r($data_com->id);exit;
		if ($request->isMethod('post')) {
			$formdata = $this->process_post_data($this->edit_specialty_param, $request);
			// print_r($formdata);exit;
			if($formdata['title_id']!=$data->id){
				$old_specialty=SpecialtyList::find($data->id);
				$new_specialty=SpecialtyList::find($formdata['title_id']);

				$change_data= '修改學門專長: '.$old_specialty->title.' => '.$new_specialty->title;	

				// 新增異動紀錄
				$user_id=$member_id;
				$username=$member_username;
				$action=$change_data;
				// print_r($action);exit;
				$change_data=array(
					'user_id'  => $user_id,
					'username'  => $username,
					'action'  => $action,
					'committeeman_id'=>$committeeman_id,
					'committeeman'  =>$committeeman_name
				);
				$res=ChangeRecord::Create($change_data);

				$formdata['create_date']=date('Y/m/d');
				$res = Specialty::updateOrCreate(['id'=>$id], $formdata);
				$res = Committeeman::updateOrCreate(['id'=>$id_arr], $data_arr);
			}else{
				// print '=';
				$res=true;
			}
		
			

			// $res = Committeeman::updateOrCreate(['id'=>$id_arr], $data_arr);
			if ($res) {
				$this->js_output_and_redirect('編輯成功', 'mgr.committeeman.specialty',['id'=>$committeeman_id]);
			} else {
				$this->js_output_and_back('編輯發生錯誤');
			}
			exit();

		}
		$this->data['title'] = "編輯學門專長 ";
		$this->data['parent'] = "推薦資料列表";
		$this->data['parent_url'] = route('mgr.committeeman');
		$this->data['action'] = route('mgr.committeeman.edit', ['id'=>$id]);
		$this->data['submit_txt'] = '確認編輯';

		
		// print_r($data->now_unit_id);exit;
		// specialty_id
		// print_r($specialty->title_id);exit;
		// $specialty['']
		
		$this->data['params'] = $this->generate_param_to_view($this->edit_specialty_param, $specialty);
		
		
		
		// $this->data['params'] = $this->generate_param_to_view($this->param, $data);

		// print_r($this->data);exit;
		
		//使用者僅要撈出自己管的＆尚未指派
		$this->data['select']['users'] = array();
		

		
		

		return view('mgr/template_form', $this->data);
	}
	//編輯學術專長
	public function edit_academics(Request $request, $id){

		// print_r($id);exit;
		$member_id=Auth::guard('mgr')->user()->id;
		$member_username=Auth::guard('mgr')->user()->username;
		
		$data=Academic::find($id);
		$committeeman_id=$data->committeeman_id;

		$data_com=Committeeman::find($committeeman_id);
		$committeeman_name=$data_com->username;
		$data_arr = $data_com->toArray();
		$id_arr=$data_arr['id'];
		unset($data_arr['id']);
		unset($data_arr['created_at']);
		unset($data_arr['updated_at']);
		unset($data_arr['status']);
		unset($data_arr['specialty_source']);
		unset($data_arr['academic_id']);
		unset($data_arr['academic_source']);
		unset($data_arr['specialty_id']);
		unset($data_arr['member_id']);
		unset($data_arr['deleted_at']);
		$data_arr['updated_at']=now();

		// unset($data_arr['phone']);
		// unset($data_arr['email']);
		// unset($data_arr['url']);
		// unset($data_arr['old_unit']);
		// print_r($data_arr);exit;
		// print_r($data_com->id);exit;
		if ($request->isMethod('post')) {
			$formdata = $this->process_post_data($this->edit_academics_param, $request);
			// print_r($id_arr);exit;
			if($formdata['title']!=$data->title){
				$change_data= '修改學術專長: '.$data->title.' => '.$formdata['title'];	

				// 新增異動紀錄
				$user_id=$member_id;
				$username=$member_username;
				$action=$change_data;
				// print_r($action);exit;
				$change_data=array(
					'user_id'  => $user_id,
					'username'  => $username,
					'action'  => $action,
					'committeeman_id'=>$committeeman_id,
					'committeeman'  =>$committeeman_name
				);
				$res=ChangeRecord::Create($change_data);
			}
		
			$formdata['create_date']=date('Y/m/d');
			$res = Academic::updateOrCreate(['id'=>$id], $formdata);

			$res = Committeeman::updateOrCreate(['id'=>$id_arr], $data_arr);
			if ($res) {
				$this->js_output_and_redirect('編輯成功', 'mgr.committeeman.academics',['id'=>$data->committeeman_id]);
			} else {
				$this->js_output_and_back('編輯發生錯誤');
			}
			exit();

		}
		$this->data['title'] = "編輯學門專長 ";
		$this->data['parent'] = "推薦資料列表";
		$this->data['parent_url'] = route('mgr.committeeman');
		$this->data['action'] = route('mgr.committeeman.edit', ['id'=>$id]);
		$this->data['submit_txt'] = '確認編輯';

		
		// print_r($data->now_unit_id);exit;
		
		$this->data['params'] = $this->generate_param_to_view($this->edit_academics_param, $data);
		
		
		
		// $this->data['params'] = $this->generate_param_to_view($this->param, $data);

		// print_r($this->data);exit;
		
		//使用者僅要撈出自己管的＆尚未指派
		$this->data['select']['users'] = array();
		

		
		

		return view('mgr/template_form', $this->data);
		// print 123;exit;
	}
	//新增學術專長
	public function add_academics(Request $request,$id){

		// print_r(123);exit;
		$member_id=Auth::guard('mgr')->user()->id;
		$member_username=Auth::guard('mgr')->user()->username;
		
		$data_com=Committeeman::find($id);
		
		$committeeman_name=$data_com->username;
		$data_arr = $data_com->toArray();
		$id_arr=$data_arr['id'];
		unset($data_arr['id']);
		unset($data_arr['created_at']);
		unset($data_arr['updated_at']);
		unset($data_arr['status']);
		unset($data_arr['specialty_source']);
		unset($data_arr['academic_id']);
		unset($data_arr['academic_source']);
		unset($data_arr['specialty_id']);
		unset($data_arr['member_id']);
		unset($data_arr['deleted_at']);
		$data_arr['updated_at']=now();
		// print_r($data);exit;
		// $committeeman_id=$data->committeeman_id;
		if ($request->isMethod('post')) {
			$formdata = $this->process_post_data($this->edit_academics_param, $request);

			$data=Academic::where('committeeman_id',$id)->where('title',$formdata['title'])->first();
			if($data==''){
				// print 123;
				$change_data= '新增學術專長: '.$formdata['title'];	

				// 新增異動紀錄
				$user_id=$member_id;
				$username=$member_username;
				$action=$change_data;
				// print_r($action);exit;
				$change_data=array(
					'user_id'  => $user_id,
					'username'  => $username,
					'action'  => $action,
					'committeeman_id'=>$id,
					'committeeman'  =>$committeeman_name
				);
				$res=ChangeRecord::Create($change_data);
			}else{
				// print 444;
				$this->js_output_and_back('此學術專長已存在');
			}
		
			

			$formdata['writer_id']=$member_id;
			$formdata['committeeman_id']=$id;
		
			$formdata['create_date']=date('Y/m/d');
			$res = Academic::updateOrCreate($formdata);

			$res = Committeeman::updateOrCreate(['id'=>$id_arr], $data_arr);
			// $res='123';
			if ($res) {
				$this->js_output_and_redirect('新增成功', 'mgr.committeeman.academics',['id'=>$id]);
			} else {
				$this->js_output_and_back('新增發生錯誤');
			}
			exit();

		}
		$this->data['title'] = "新增學術專長 ";
		$this->data['parent'] = "學術專長列表";
		$this->data['parent_url'] = route('mgr.committeeman');
		$this->data['action'] = route('mgr.committeeman');
		$this->data['submit_txt'] = '確認編輯';

		
		// print_r($data->now_unit_id);exit;
		
		$this->data['params'] = $this->generate_param_to_view($this->add_academics_param);
		
		
		
		// $this->data['params'] = $this->generate_param_to_view($this->param, $data);

		// print_r($this->data);exit;
		
		//使用者僅要撈出自己管的＆尚未指派
		$this->data['select']['users'] = array();
		

		
		

		return view('mgr/template_form', $this->data);
		// print 123;exit;
	}
	//新增學門專長
	public function add_new_specialty(Request $request,$id){

		// print_r(123);exit;
		$member_id=Auth::guard('mgr')->user()->id;
		$member_username=Auth::guard('mgr')->user()->username;

		// $specialty=Specialty::find($id);
		// $committeeman_id=$specialty->committeeman_id;
		// $data=SpecialtyList::find($specialty->title_id);
		
		$data_com=Committeeman::find($id);
		
		$committeeman_name=$data_com->username;
		$data_arr = $data_com->toArray();
		$id_arr=$data_arr['id'];
		unset($data_arr['id']);
		unset($data_arr['created_at']);
		unset($data_arr['updated_at']);
		unset($data_arr['status']);
		unset($data_arr['specialty_source']);
		unset($data_arr['academic_id']);
		unset($data_arr['academic_source']);
		unset($data_arr['specialty_id']);
		unset($data_arr['member_id']);
		unset($data_arr['deleted_at']);
		$data_arr['updated_at']=now();
		// print_r($data);exit;
		// $committeeman_id=$data->committeeman_id;
		if ($request->isMethod('post')) {
			$formdata = $this->process_post_data($this->add_new_specialty_param, $request);
			// print_r($formdata);exit;

			$data=Specialty::where('committeeman_id',$id)->where('title_id',$formdata['title_id'])->first();
			// print_r($data);exit;
			if($data==''){
				// print 123;exit;
				$new_specialty=SpecialtyList::find($formdata['title_id']);
				$change_data= '新增學門專長: '.$new_specialty->title;	

				// 新增異動紀錄
				$user_id=$member_id;
				$username=$member_username;
				$action=$change_data;
				// print_r($action);exit;
				$change_data=array(
					'user_id'  => $user_id,
					'username'  => $username,
					'action'  => $action,
					'committeeman_id'=>$id,
					'committeeman'  =>$committeeman_name
				);
				$res=ChangeRecord::Create($change_data);
			}else{
				// print 444;
				$this->js_output_and_back('此學門專長已存在');
			}
		
			

			$formdata['writer_id']=$member_id;
			$formdata['committeeman_id']=$id;
		
			// $formdata['create_date']=date('Y/m/d');
			// 新增學門專長
			$formdata['writer_id']=$member_id;
			// $formdata['committeeman_id']=$committeeman_id;
			$formdata['title_id']=$formdata['title_id'];
			// $formdata['specialty_sources_id']=$specialty_sources_id;
			$formdata['create_date']=date('Y/m/d');
			$res = Specialty::updateOrCreate($formdata);
			// $res = Academic::updateOrCreate($formdata);

			$res = Committeeman::updateOrCreate(['id'=>$id_arr], $data_arr);
			// $res='123';
			if ($res) {
				$this->js_output_and_redirect('新增成功', 'mgr.committeeman.specialty',['id'=>$id]);
			} else {
				$this->js_output_and_back('新增發生錯誤');
			}
			exit();

		}
		$this->data['title'] = "新增學門專長 ";
		$this->data['parent'] = "學門專長列表";
		$this->data['parent_url'] = route('mgr.committeeman');
		$this->data['action'] = route('mgr.committeeman');
		$this->data['submit_txt'] = '確認編輯';

		
		// print_r($data->now_unit_id);exit;
		
		$this->data['params'] = $this->generate_param_to_view($this->add_new_specialty_param);
		
		
		
		// $this->data['params'] = $this->generate_param_to_view($this->param, $data);

		// print_r($this->data);exit;
		
		//使用者僅要撈出自己管的＆尚未指派
		$this->data['select']['users'] = array();
		

		
		

		return view('mgr/template_form', $this->data);
		// print 123;exit;
	}
	public function specialty(Request $request, $id){

		// print 333;exit;
		$member_id=Auth::guard('mgr')->user()->id;
		$privilege_id=Auth::guard('mgr')->user()->privilege_id;
		$member_username=Auth::guard('mgr')->user()->username;
		// print_r($id);exit;
		$res=Committeeman::find($id);

		$this->data['controller'] = 'committeeman';
		$this->data['title']      = $res->username." 的學門專長列表";
		$this->data['parent']     = "";
		$this->data['parent_url'] = "";
		$this->data['th_title']   = $this->th_title_field(
			[
				['#', '', ''],
				['學門專長', '', ''],
				['建立人', '', ''],
				['最後異動時間', '', ''],
				['動作', '', ''],
				
			]
		);
		$this->data['btns'] = [
			['<i class="ri-add-fill"></i>', '新增學門專長', route('mgr.committeeman.add_new_specialty',['id'=>$id]), 'primary']
		];
		$this->data['type']='';
		$this->data['template_item'] = 'mgr/items/specialty_item';

		$data = array();
		
		$data = Specialty::where('committeeman_id',$id)->get();

		$this->data['data'] = array();
		$x=1;
		
		// print_r(count($data));exit;
		foreach ($data as $item) {
			$writer_name = Member::where('id',$item->writer_id)->first();
			$specialty=SpecialtyList::where('id',$item->title_id)->first();
			$s_title=$specialty->title;
			
			$obj = array();
			$obj[] = $item->id;
            $obj[] = $s_title;
            $obj[] = $writer_name->username;
            // $obj[] = $item->title;
            $obj[] = $item->updated_at;
			if($privilege_id==1){
				$priv_edit_specialty = true;
				$priv_del_specialty = true;
			}
			// elseif($privilege_id==2){
			// 	$priv_edit_specialty = true;
			// 	$priv_del_specialty = true;
			// }
			elseif($item->writer_id==$member_id){
				$priv_edit_specialty = true;
				$priv_del_specialty = true;
			}else{
				$priv_edit_specialty = false;
				$priv_del_specialty = false;
			}

			// if($privilege_id==3){
			// 	$priv_edit_specialty = false;
			// 	$priv_del_specialty = false;
			// }
			// if($item->writer_id==$member_id){
			// 	$priv_edit_specialty = true;
			// 	$priv_del_specialty = true;
			// }
           
            $priv_edit = false;
			$priv_del = false;
			$item_member_id=$item->title;
			// $member_id=Auth::guard('mgr')->user()->id;
			// $other_btns = array();

			
			$this->data['data'][] = array(
				"id"         => $item->id,
				"data"       => $obj,
                "priv_edit"  => $priv_edit,
                "priv_del"   => $priv_del,
				'priv_edit_specialty'=>$priv_edit_specialty,
				'priv_del_specialty'=>$priv_del_specialty
				// "item_member_id"  => $item_member_id,
				// "member_id"  => $member_id,
				// "other_btns" => $other_btns
			);
			$x++;
		}
		
		return view('mgr/template_list', $this->data);

	}
	public function specialty_list(Request $request, $id){

		// print 333;exit;
		$member_id=Auth::guard('mgr')->user()->id;
		$privilege_id=Auth::guard('mgr')->user()->privilege_id;
		$member_username=Auth::guard('mgr')->user()->username;
		// print_r($id);exit;
		$res=Committeeman::find($id);

		$this->data['controller'] = 'committeeman';
		$this->data['title']      = $res->username." 的學門專長列表";
		$this->data['parent']     = "";
		$this->data['parent_url'] = "";
		$this->data['th_title']   = $this->th_title_field(
			[
				['#', '', ''],
				['學門專長', '', ''],
				['建立人', '', ''],
				['最後異動時間', '', ''],
				['動作', '', ''],
				
			]
		);
		$this->data['btns'] = [
			['<i class="ri-add-fill"></i>', '新增學門專長', route('mgr.committeeman.add_new_specialty',['id'=>$id]), 'primary']
		];
		$this->data['type']='';
		$this->data['template_item'] = 'mgr/items/specialty_item';

		$data = array();
		
		$data = Specialty::where('committeeman_id',$id)->get();

		$this->data['data'] = array();
		$x=1;
		
		// print_r(count($data));exit;
		foreach ($data as $item) {
			$writer_name = Member::where('id',$item->writer_id)->first();
			$specialty=SpecialtyList::where('id',$item->title_id)->first();
			$s_title=$specialty->title;
			
			$obj = array();
			$obj[] = $item->id;
            $obj[] = $s_title;
            $obj[] = $writer_name->username;
            // $obj[] = $item->title;
            $obj[] = $item->updated_at;
			if($privilege_id==1){
				$priv_edit_specialty = true;
				$priv_del_specialty = true;
			}elseif($privilege_id==2){
				$priv_edit_specialty = true;
				$priv_del_specialty = true;
			}
			elseif($item->writer_id==$member_id){
				$priv_edit_specialty = true;
				$priv_del_specialty = true;
			}else{
				$priv_edit_specialty = false;
				$priv_del_specialty = false;
			}

			// if($privilege_id==3){
			// 	$priv_edit_specialty = false;
			// 	$priv_del_specialty = false;
			// }
			// if($item->writer_id==$member_id){
			// 	$priv_edit_specialty = true;
			// 	$priv_del_specialty = true;
			// }
           
            $priv_edit = false;
			$priv_del = false;
			$item_member_id=$item->title;
			// $member_id=Auth::guard('mgr')->user()->id;
			// $other_btns = array();

			
			$this->data['data'][] = array(
				"id"         => $item->id,
				"data"       => $obj,
                "priv_edit"  => $priv_edit,
                "priv_del"   => $priv_del,
				'priv_edit_specialty'=>$priv_edit_specialty,
				'priv_del_specialty'=>$priv_del_specialty
				// "item_member_id"  => $item_member_id,
				// "member_id"  => $member_id,
				// "other_btns" => $other_btns
			);
			$x++;
		}
		
		return view('mgr/template_list', $this->data);

	}
	public function academics(Request $request, $id){
			// print_r($id);exit;
		// $Academic = Academic::where('id',$id)->first();
		// print_r($Academic->committeeman_id);exit;

		$member_id=Auth::guard('mgr')->user()->id;
		$privilege_id=Auth::guard('mgr')->user()->privilege_id;
		$member_username=Auth::guard('mgr')->user()->username;
		// print_r($id);exit;
		$res=Committeeman::find($id);
		// print_r($res);exit;

		$this->data['controller'] = 'committeeman';
		$this->data['title']      = $res->username." 的學術專長列表";
		$this->data['parent']     = "";
		$this->data['parent_url'] = "";
		$this->data['th_title']   = $this->th_title_field(
			[
				['#', '', ''],
				['學術專長', '', ''],
				['建立人', '', ''],
				['最後異動時間', '', ''],
				['動作', '', ''],
				
			]
		);
		$this->data['btns'] = [
			['<i class="ri-add-fill"></i>', '新增學術專長', route('mgr.committeeman.add_academics',['id'=>$id]), 'primary']
		];
		$this->data['type']='';
		$this->data['template_item'] = 'mgr/items/academics_item';

		$data = array();
		
		// $data = Member::with('get_member_department')->get();
		$data = Academic::where('committeeman_id',$id)->get();
		
		// print_r($data);exit;
		// $Committeeman=Committeeman::find($id);
		
		
		$this->data['data'] = array();
		$x=1;
		
		// print_r(count($data));exit;
		foreach ($data as $item) {
			$writer_name = Member::where('id',$item->writer_id)->first();

			// print_r($writer_name->username);exit;
			// $department_title=array();
			// $department=$item->department_array();
			// foreach($item->department_array() as $department_id){
			// 	$data=Department::where('id',$department_id)->first();
			// 	$department_title[]=$data->title;
			// 	// print_r($data->title);exit;
			// }
			// $departmen_all=implode("、",$department_title);
		
			// print_r($department_data);
			// exit;
			$obj = array();
			$obj[] = $item->id;
            $obj[] = $item->title;
            $obj[] = $writer_name->username;
            // $obj[] = $item->title;
            $obj[] = $item->updated_at;
			if($privilege_id==1){
				$priv_edit_academics = true;
				$priv_del_academics = true;
			}
			// elseif($privilege_id==2){
			// 	$priv_edit_academics = true;
			// 	$priv_del_academics = true;
			// }
			elseif($item->writer_id==$member_id){
				$priv_edit_academics = true;
				$priv_del_academics = true;
			}else{
				$priv_edit_academics = false;
				$priv_del_academics = false;
			}
           
            $priv_edit = false;
			$priv_del = false;
			$item_member_id=$item->title;
			// $member_id=Auth::guard('mgr')->user()->id;
			// $other_btns = array();

			
			$this->data['data'][] = array(
				"id"         => $item->id,
				"data"       => $obj,
                "priv_edit"  => $priv_edit,
                "priv_del"   => $priv_del,
				'priv_edit_academics'=>$priv_edit_academics,
				'priv_del_academics'=>$priv_del_academics
				// "item_member_id"  => $item_member_id,
				// "member_id"  => $member_id,
				// "other_btns" => $other_btns
			);
			$x++;
		}
		
		return view('mgr/template_list', $this->data);
	}
	public function academics_list(Request $request, $id){
		// print_r($id);exit;
	// $Academic = Academic::where('id',$id)->first();
	// print_r($Academic->committeeman_id);exit;

	$member_id=Auth::guard('mgr')->user()->id;
	$privilege_id=Auth::guard('mgr')->user()->privilege_id;
	$member_username=Auth::guard('mgr')->user()->username;
	// print_r($id);exit;
	$res=Committeeman::find($id);
	// print_r($res);exit;

	$this->data['controller'] = 'committeeman';
	$this->data['title']      = $res->username." 的學術專長列表";
	$this->data['parent']     = "";
	$this->data['parent_url'] = "";
	$this->data['th_title']   = $this->th_title_field(
		[
			['#', '', ''],
			['學術專長', '', ''],
			['建立人', '', ''],
			['最後異動時間', '', ''],
			['動作', '', ''],
			
		]
	);
	$this->data['btns'] = [
		['<i class="ri-add-fill"></i>', '新增學術專長', route('mgr.committeeman.add_academics',['id'=>$id]), 'primary']
	];
	$this->data['type']='';
	$this->data['template_item'] = 'mgr/items/academics_item';

	$data = array();
	
	// $data = Member::with('get_member_department')->get();
	$data = Academic::where('committeeman_id',$id)->get();
	
	// print_r($data);exit;
	// $Committeeman=Committeeman::find($id);
	
	
	$this->data['data'] = array();
	$x=1;
	
	// print_r(count($data));exit;
	foreach ($data as $item) {
		$writer_name = Member::where('id',$item->writer_id)->first();

		// print_r($writer_name->username);exit;
		// $department_title=array();
		// $department=$item->department_array();
		// foreach($item->department_array() as $department_id){
		// 	$data=Department::where('id',$department_id)->first();
		// 	$department_title[]=$data->title;
		// 	// print_r($data->title);exit;
		// }
		// $departmen_all=implode("、",$department_title);
	
		// print_r($department_data);
		// exit;
		$obj = array();
		$obj[] = $item->id;
		$obj[] = $item->title;
		$obj[] = $writer_name->username;
		// $obj[] = $item->title;
		$obj[] = $item->updated_at;
		if($privilege_id==1){
			$priv_edit_academics = true;
			$priv_del_academics = true;
		}elseif($privilege_id==2){
			$priv_edit_academics = true;
			$priv_del_academics = true;
		}
		elseif($item->writer_id==$member_id){
			$priv_edit_academics = true;
			$priv_del_academics = true;
		}else{
			$priv_edit_academics = false;
			$priv_del_academics = false;
		}
	   
		$priv_edit = false;
		$priv_del = false;
		$item_member_id=$item->title;
		// $member_id=Auth::guard('mgr')->user()->id;
		// $other_btns = array();

		
		$this->data['data'][] = array(
			"id"         => $item->id,
			"data"       => $obj,
			"priv_edit"  => $priv_edit,
			"priv_del"   => $priv_del,
			'priv_edit_academics'=>$priv_edit_academics,
			'priv_del_academics'=>$priv_del_academics
			// "item_member_id"  => $item_member_id,
			// "member_id"  => $member_id,
			// "other_btns" => $other_btns
		);
		$x++;
	}
	
	return view('mgr/template_list', $this->data);
}
	public function edit_department(Request $request, $id){


		print_r(123);exit;
		$data = Member::find($id);
		if ($request->isMethod('post')) {
			$formdata = $this->process_post_data($this->param, $request);

			if (Member::where('email', $formdata['email'])->where('id','!=',$id)->count() > 0){
				$this->js_output_and_back('Email已存在');
				exit();
			}
			if ($formdata['password'] == ''){
				unset($formdata['password']);
			}else{
				if ($formdata['password'] != $formdata['password_confirm']) $this->js_output_and_back('兩次密碼輸入不相同');
				$formdata['password'] = Hash::make($formdata['password']);
			}
			unset($formdata['password_confirm']);

			$formdata['update_by'] = Auth::guard('mgr')->user()->id;
			$res = Member::updateOrCreate(['id'=>$id], $formdata);
			if ($res) {
				$res->subordinate_refresh($request->post('subordinate'));
				$res->manage_product_refresh($request->post('products'));
				$res->manage_user_refresh($request->post('users'));

				Product::where(['assistant'=>$id])->update(['assistant'=>0]);
				if ($request->post('products_assistant')){
					foreach ($request->post('products_assistant') as $product_id) {
						Product::where('id', $product_id)->update(['assistant'=>$id]);
					}
				}

				$this->js_output_and_redirect('編輯成功', 'mgr.member');
			} else {
				$this->js_output_and_back('編輯發生錯誤');
			}
			exit();
		}
		// $2y$10$4366i0NSO7jboKNbaVLkFubgYg5qnovDQWFks4uPKW0zZ7nIpfDue
		$this->data['title'] = "編輯 ".$data->username;
		$this->data['parent'] = "帳號管理";
		$this->data['parent_url'] = route('mgr.member');
		$this->data['action'] = route('mgr.member', ['id'=>$id]);
		$this->data['submit_txt'] = '確認編輯';

		$subordinate = $data->subordinate_array();
		$users = $data->manage_user_array();
		$products = $data->manage_product_array();
		$data = $data->toArray();

		$data['subordinate'] = $subordinate;
		$data['users'] = $users;
		$data['products'] = $products;
		
		$data['products_assistant'] = array();
		foreach (Product::where(['assistant'=>$id])->get() as $p) {
			$data['products_assistant'][] = $p->id;
		}
		
		$this->data['params'] = $this->generate_param_to_view($this->param, $data);
		
		$this->data['select']['subordinate'] = Member::where('id', '!=', Auth::guard('mgr')->user()->id)->get()->toArray();

		//使用者僅要撈出自己管的＆尚未指派
		$this->data['select']['users'] = array();
		foreach (User::where(['status'=>'normal'])->whereNull('deleted_at')->with('manage_user')->get() as $user) {
			if (is_null($user->manage_user) || count($user->manage_user) <= 0){
				$this->data['select']['users'][] = $user->toArray();
				continue;
			}
			if (count($user->manage_user) > 0) {
				$mine = false;
				foreach ($user->manage_user as $m) {
					if ($m['id'] == $id) $mine = true;
				}
				if ($mine) $this->data['select']['users'][] = $user->toArray();
			}			
		}

		//僅要撈出自己管的＆尚未指派的商品
		$this->data['select']['products'] = array();//Product::get()->toArray();
		foreach (Product::where(['status'=>'on', 'lang'=>'tw'])->whereNull('deleted_at')->with('manager')->get() as $product) {
			if (is_null($product->manager) || count($product->manager) <= 0){
				$this->data['select']['products'][] = $product->toArray();
				continue;
			}
			if (count($product->manager) > 0) {
				$mine = false;
				foreach ($product->manager as $m) {
					if ($m['id'] == $id) $mine = true;
				}
				if ($mine) $this->data['select']['products'][] = $product->toArray();
			}	
		}

		$this->data['select']['products_assistant'] = Product::where(['lang'=>'tw', 'status'=>'on'])->where(function($q) use ($id) {
			$q->where('assistant', 0)
			  ->orWhere('assistant', $id);
		})->get()->toArray();

		return view('mgr/template_form', $this->data);

	}

	public function pdf_output(){
		// print 123;exit;
		require_once("../app/Extend/tcpdf/tcpdf.php");
		$pdf = new \TCPDF();
				// 設定檔案資訊
				$pdf->SetCreator('懶人開發網');
				$pdf->SetAuthor('懶人開發網');
				$pdf->SetTitle('TCPDF12313範例');
				$pdf->SetSubject('TCPDF12312範例');
				$pdf->SetKeywords('TCPDF, PDF, PHP');
		 
				// 設定頁首和頁尾資訊
				// $pdf->SetHeaderData('tcpdf_logo.jpg', 30, '韓LanRenKaiFA.co', '學會偷懶，並懶出效率！', [0, 64, 255], [0, 64, 128]);
				// $pdf->SetHeaderData('', 30, '查詢條件:123', '日期:2023-06-30', [0, 64, 128], [0, 64, 128]);
				$pdf->SetHeaderData('', 0, '此份文件供教評會使用', '', [255,0,0], [0, 64, 128]);
				$pdf->setFooterData([0, 64, 0], [0, 64, 128]);
		 
				// 設定頁首和頁尾字型
				$pdf->setHeaderFont(['msungstdlight', '', '10']);
				$pdf->setFooterFont(['helvetica', '', '8']);
		 
				// 設定預設等寬字型
				$pdf->SetDefaultMonospacedFont('courier');
		 
				// 設定間距
				$pdf->SetMargins(15, 15, 15);//頁面間隔
				$pdf->SetHeaderMargin(5);//頁首top間隔
				$pdf->SetFooterMargin(10);//頁尾bottom間隔
		 
				// 設定分頁
				$pdf->SetAutoPageBreak(true, 25);
		 
				// set default font subsetting mode
				$pdf->setFontSubsetting(true);
		 
				//設定字型 stsongstdlight支援中文
				// $pdf->SetFont('stsongstdlight', '', 14);
				$pdf->SetFont('msungstdlight', '', 20);
				//第一頁
				$pdf->AddPage();
				$pdf->writeHTML('<p style="text-align: center"><h1>test1</h1></p>');
				$pdf->writeHTML('<p>我是第一行內容</p>');
				$pdf->writeHTML('<p style="color: red">我是第二行內容</p>');
				$pdf->writeHTML('<p>我是第三行內容</p>');
				$pdf->Ln(5);//換行符
				$pdf->writeHTML('<p><a href="http://www.lanrenkaifa.com/" rel="external nofollow"  title="">test1</a></p>');
		 
				//第二頁
				// $pdf->AddPage();
				// $pdf->writeHTML('<h1>第二頁內容</h1>');
		 
				//輸出PDF
				$pdf->Output('t.pdf', 'I');//I輸出、D下載
	}
	public function pdf_export(Request $request){
		// echo Auth::guard('mgr')->user()->id;exit;
		// $user = Auth::user();
		// print_r($user);exit;
		// print_r(Auth::user()->email);exit;

		$session_data = $request->session()->all();
		// print_r($session_data);exit;
		$search_list=$session_data['search_list'];
		$search_data=$session_data['search_data'];

		// print_r($session_data['search_list']);exit;
		$search_data_all='';
		foreach($search_data as $s_data){
			// print_r($s_data);
			$search_data_all=	'查詢條件  姓名:'.$s_data['search_name'].
								' ，服務單位:'.$s_data['search_now_unit'].
								'  ，'.	$s_data['have'].
								'  ，職稱:'.$s_data['search_title'].
								'  ，學門專長:'.$s_data['search_specialty'].
								'  ，學術專長:'.$s_data['search_academic'].
								'  ，最後異動時間:'.$s_data['last_date'];
			
		}
	
		// $request->session()->forget('search_data'); // 清除session查詢條件
		// $request->session()->forget('search_list'); // 清除session查詢列表
	
		require_once("../app/Extend/tcpdf/tcpdf.php");
			// create new PDF document
			// $this->Header();
			$html='';
			$pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

			$pdf->SetFont('msungstdlight', '', 10);

        // 公司與報表名稱
        $title = '
			<h4 style="font-size: 20pt; font-weight: normal; text-align: center;">外審查詢結果表單</h4>
			<h3 color="red">此份文件供教評會使用</h3>
			<table>
				
				<tr>
					<td style="font-size: 10pt; font-weight: normal;">查詢人:.'.Auth::guard('mgr')->user()->username.'</td>
				</tr>
				<tr>
					<td colspan="2"></td>
				</tr>
				<tr>
					<td style="font-size: 10pt; font-weight: normal;">'.$search_data_all.'</td>
				</tr>
				<tr>
					<td colspan="2"></td>
				</tr>
			</table>';


					/**
					 * 標題欄位
					 *
					 * 所有欄位的 width 設定值均與「資料欄位」互相對應，除第一個 <td> width 須向左偏移 5px，才能讓後續所有「標題欄位」與「資料欄位」切齊
					 * 最後一個 <td> 必須設定 width: auto;，才能將剩餘寬度拉至最寬
					 * style 屬性可使用 text-align: left|center|right; 來設定文字水平對齊方式
					 * <td style="border-bottom: 1px solid black; width: 50px;">姓名</td>
					 * <td style="border-bottom: 1.5px solid black; width: 50px;">姓名</td>
					* <td style="border-bottom: 1.5px solid black; width: 60px;">服務單位</td>
					* <td style="border-bottom: 1.5px solid black; width: 60px;">單位名稱</td>
					* <td style="border-bottom: 1.5px solid black; width: 60px;">職稱</td>
					* <td style="border-bottom: 1.5px solid black; width: 60px;">曾任單位</td>
					* <td style="border-bottom: 1.5px solid black; width: 90px;">曾任單位名稱</td>
					* <td style="border-bottom: 1.5px solid black; width: 70px;">學門專長</td> 
					* <td style="border-bottom: 1.5px solid black; width: 170px;">學術專長</td> 
					* <td style="border-bottom: 1.5px solid black; width: auto;">最後異動時間</td> 
					 * 
					 */  

					$fields = '
			<table cellpadding="1">
				<tr>
					<td style="border-bottom: 1.5px solid black; width: 20px;">序號</td>
					<td style="border-bottom: 1.5px solid black; width: 50px;">姓名</td>
					<td style="border-bottom: 1.5px solid black; width: 60px;">服務單位</td>
					<td style="border-bottom: 1.5px solid black; width: 60px;">單位名稱</td>
					<td style="border-bottom: 1.5px solid black; width: 60px;">職稱</td>
					<td style="border-bottom: 1.5px solid black; width: 60px;">曾任單位</td>
					<td style="border-bottom: 1.5px solid black; width: 90px;">曾任單位名稱</td>
					<td style="border-bottom: 1.5px solid black; width: 70px;">學門專長</td> 
					<td style="border-bottom: 1.5px solid black; width: 170px;">學術專長</td> 
					<td style="border-bottom: 1.5px solid black; width: 50px;">推薦人</td> 
					<td style="border-bottom: 1.5px solid black; width: auto;">最後異動時間</td> 
				</tr>
			</table>';

					// 設定不同頁要顯示的內容 (數值為對應的頁數)
					switch ($pdf->getPage()) {
						case '1':
							// 設定資料與頁面上方的間距 (依需求調整第二個參數即可)
							$pdf->SetMargins(1, 50, 1);

							// 增加列印日期的資訊
							$html = $title . '
			<table cellpadding="1">
				<tr>
					<td>列印日期：' . date('Y-m-d') . ' ' . date('H:i') . '</td>
					<td></td>
					<td></td>        
				</tr>
				<tr>
					<td colspan="3"></td>
				</tr>
			</table>' .  $fields;
							break;
						// 其它頁
						default:
							$pdf->SetMargins(1, 40, 1);
							$html = $title . $fields;
        }
		$html = $title . '
				<table cellpadding="1">
					<tr>
						<td>列印日期：' . date('Y-m-d') . ' ' . date('H:i') . '</td>
						<td></td>
						<td></td>        
					</tr>
					<tr>
						<td colspan="3"></td>
					</tr>
				</table>' .  $fields;
        
		// Title
        // $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
			// set document information
			$pdf->SetCreator(PDF_CREATOR);
			$pdf->SetAuthor('Nicola Asuni');
			$pdf->SetTitle('外審查詢結果表單');
			$pdf->SetSubject('TCPDF Tutorial');
			$pdf->SetKeywords('TCPDF, PDF, example, test, guide');
			

			// set default header data
			$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 001', PDF_HEADER_STRING, array(0,64,255), array(0,64,128));
			$pdf->setFooterData(array(0,64,0), array(0,64,128));

			// set header and footer fonts
			$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
			$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

			// set default monospaced font
			$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

			// set margins
			// 版面配置 > 邊界
			// $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
			$pdf->SetMargins(1, 1, 1);

			// 頁首上方與頁面頂端的距離
			$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
			// 頁尾上方與頁面底端的距離
			$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

			// set auto page breaks
			// 自動分頁
			$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

			// set image scale factor
			$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

			// set some language-dependent strings (optional)
			if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
			require_once(dirname(__FILE__).'/lang/eng.php');
			$pdf->setLanguageArray($l);
			}

			// ---------------------------------------------------------

			// set default font subsetting mode
			$pdf->setFontSubsetting(true);

			// Set font
			// dejavusans is a UTF-8 Unicode font, if you only need to
			// print standard ASCII chars, you can use core fonts like
			// helvetica or times to reduce file size.
			// $pdf->SetFont('dejavusans', '', 14, '', true);
			// 中文字體名稱, 樣式 (B 粗, I 斜, U 底線, D 刪除線, O 上方線), 字型大小 (預設 12pt), 字型檔, 使用文字子集 
			$pdf->SetFont('msungstdlight', '', 10);

			// Add a page
			// This method has several options, check the source code documentation for more information.
			// 版面配置：P 直向 | L 橫向, 紙張大小 (必須大寫字母)
			$pdf->AddPage('P', 'LETTER');

			// set text shadow effect
			// 文字陰影
			// $pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));

			// Set some content to print
			// $html = <<<EOD
			// <h1>Welcome to <a href="http://www.tcpdf.org" style="text-decoration:none;background-color:#CC0000;color:black;"> <span style="color:black;">TC</span><span style="color:white;">PDF</span> </a>!</h1>
			// <i>This is the first example of TCPDF library.</i>
			// <p>This text is printed using the <i>writeHTMLCell()</i> method but you can also use: <i>Multicell(), writeHTML(), Write(), Cell() and Text()</i>.</p>
			// <p>Please check the source code documentation and other examples for further information.</p>
			// <p style="color:#CC0000;">TO IMPROVE AND EXPAND TCPDF I NEED YOUR SUPPORT, PLEASE <a href="http://sourceforge.net/donate/index.php?group_id=128076">MAKE A DONATION!</a></p>
			// EOD;
				$num=1;
			foreach($search_list as $s_data){
				// print_r($s_data['data']);exit;
				// $id=$s_data['data'][0];
				$id=$num;
				$username=$s_data['data'][1];
				$now_unit=$s_data['data'][2];
				$now_unit_name=$s_data['data'][3];
				$now_title=$s_data['data'][4];
				$old_unit=$s_data['data'][5];
				$old_unit_name=$s_data['data'][6];
				$specialty_data=$s_data['data'][7];
				$academic_data=$s_data['data'][8];
				$member_name=$s_data['data'][9];
				// foreach($s_data['data'][10] as $l_date){
				// 	print_r($l_date);exit;
				// 	print_r(substr($l_date,0,19)); exit;
				// 	// print_r(substr($last_date,0,19)); exit;
				// 	$last_date=substr($l_date,0,19); break;
				// }
				// $last_date=;
				// print_r($s_data['data'][10]);exit;
				// $test=json_decode(json_encode($s_data['data'][10],true));
				$test=$s_data['data'][10];
				$test->format('Y-m-d H:i:s');
				$last_date=substr($test,0,19); 
				// print_r($test);exit;
			// }
			// exit;
			// for ($i = 0; $i < 10; $i++) {
			/**
			* 資料欄位
			*
			* 所有欄位的 width 設定值均與「標題欄位」互相對應，除第一個 <td> width 須 -5px
			* 最後一個 <td> 必須設定 width: auto;，才能將剩餘寬度拉至最寬
			* style 屬性可使用 text-align: left|center|right; 來設定文字水平對齊方式
			*/

				$html .= '
					<tr>
						<td style="border-bottom: 1px solid black; line-height: 1.5; width: 20px;">'.$id .'</td>
						<td style="border-bottom: 1px solid black; line-height: 1.5; width: 50px;">'.$username .'</td>
						<td style="border-bottom: 1px solid black; line-height: 1.5; width: 60px;">'. $now_unit .'</td>
						<td style="border-bottom: 1px solid black; line-height: 1.5; width: 60px;">'.$now_unit_name .'</td>
						<td style="border-bottom: 1px solid black; line-height: 1.5; width: 60px;">'.$now_title .'</td>
						<td style="border-bottom: 1px solid black; line-height: 1.5; width: 60px;">'.$old_unit .'</td>
						<td style="border-bottom: 1px solid black; line-height: 1.5; width: 90px;">'.$old_unit_name .'</td>
						<td style="border-bottom: 1px solid black; line-height: 1.5; width: 70px;">'.$specialty_data .'</td>
						<td style="border-bottom: 1px solid black; line-height: 1.5; width: 170px;">'.$academic_data .'</td>
						<td style="border-bottom: 1px solid black; line-height: 1.5; width: 50px;">'.$member_name .'</td>
						<td style="border-bottom: 1px solid black; line-height: 1.5; width: auto;">'.$last_date .'</td>
					</tr>';

				$num++;
			}
			

			$html = '
			<table cellpadding="1">' . $html . '</table>';

			$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

			// ---------------------------------------------------------
			// $request->session()->forget('search_data'); // 清除session查詢條件
			// $request->session()->forget('search_list'); // 清除session查詢列表	
			// Close and output PDF document
			// This method has several options, check the source code documentation for more information.
			// 下載 PDF 的檔案名稱 (不可取中文名，即使有也會自動省略中文名)
			$pdf->Output('mis-employees.pdf', 'I');	
			
	}

	public function title_data(){
		// $id = Request::_post('id');
		// $order_no = $request->post("id");
		$id=$_POST['id'];
		$type=$_POST['action'];
		$data=JobTitle::find($id);
		
		$this->output(TRUE, 'success', array(
			'title'	=>	$data->title,
			'type'	=>	$type
		));
		// return $data->title;
		// print_r($data->title);exit;
	}

	public function unit_data(){
		// $id = Request::_post('id');
		// $order_no = $request->post("id");
		// print_r(1231);exit;
		$id=$_POST['id'];
		$type=$_POST['action'];
		// print_r($id);exit;
		$data=ServiceUnit::find($id);
		$ServiceUnit=ServiceUnit::where('id', '!=', 0)->get()->toArray();
		$JobTitle=JobTitle::where('id', '!=', 0)->get()->toArray();
		// print_r($all_data);exit;
		
		$this->output(TRUE, 'success', array(
			'title'	=>	$data->title,
			'ServiceUnit'	=>	$ServiceUnit,
			'JobTitle'	=>	$JobTitle,
		));
		// return $data->title;
		// print_r($data->title);exit;
	}

	
       
	
	// Page footer
	public function Footer() {
		// Position at 15 mm from bottom
		$this->SetY(-15);
		// Set font
		$this->SetFont('helvetica', 'I', 8);
		// Page number
		$this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');
	}

	///////////////
	public function get_specialties(){
		// print 1231;exit;
		$data = Committeeman::select('committeemen.*', 'specialties.title_id',)
		
		->where('specialties.title_id','=','1')
		
		// ->where('status','=','on')
		// ->groupBy('id')
		->get();

	print_r($data);exit;
		///////////////
	}

	public function get_other_title(){
		$formdata['title']='老師';
		$formdata['committeeman_id']='1';

		OtherTitle::updateOrCreate($formdata);
		// $res = Committeeman::updateOrCreate($formdata);
		$data=OtherTitle::find(4);
		print_r($data);exit;
	}

	/*
		匯入功能
	*/
	public function import(Request $request, $type = ''){
		// print 1231;exit;
		if ($type == 'stock') {
			return $this->stock_import($request);
		}else if ($type == 'userprice') {
			return $this->userprice_import($request);
		}
		$this->data['sub_active'] = 'PRODUCT_IMPORT';
		$this->data['title'] = "產品匯入";
		$this->data['parent'] = "";
		$this->data['parent_url'] = "";
		$this->data['action'] = "default";
		$this->data['form_action'] = route('mgr.committeeman.import');
		$this->data['sample_title'] = "匯出產品資料(匯入範本)";
		// $this->data['sample_file'] = route('mgr.product.export');//env('APP_URL').'/product.xlsx';
		if ($request->isMethod('post')) {
			$cnt = 0;
			if ($request->post('action') == 'default') {
				$this->data['title'] = "產品匯入確認";
				$this->data['action'] = "check";
				$import = new ProductImport();
				$data = Excel::toCollection($import, $request->file('file'))[0]->toArray();

			// print_r($data);exit;

			// [姓名] => 含果仁
            // [服務單位] => 公立學校   轉 數字
            // [單位名稱] => 台灣大學   
            // [職稱] => 教授   	     轉 數字
            // [曾任服務單位(選填)] =>    轉 數字
            // [曾任單位名稱(選填)] =>    轉 數字
            // [曾任職稱(選填)] =>        轉 數字
            // [連絡電話(選填)] => 
            // [電子郵件信箱(選填)] => 
            // [相關資料網址(選填)] => 
            // [學門專長] => 數學		    轉 數字 table 麻煩
            // [學門專長資料來源] => 教育部    轉 數字
            // [學術專長] => 國文   	    轉 數字 table 麻煩
            // [學術專長資料來源] => 教育部    轉 數字
            // [建立者] => admin	   	    轉 數字

				foreach ($data as $item) {

					$c_data['username']=$item['姓名'];

					$s =  ServiceUnit::where('title', $item['服務單位'])->first();
					$c_data['now_unit_id']=$s['id'];

					
					$c_data['now_unit']=$item['單位名稱'];

					$j =  JobTitle::where('title', $item['職稱'])->first();
					$c_data['now_title_id']=$j['id'];

					$j =  ServiceUnit::where('title', $item['曾任服務單位(選填)'])->first();
					$c_data['old_unit_id']=(isset($j['id']))? $j['id']:0;

					$c_data['old_unit']=(isset($item['曾任單位名稱(選填)']))? $item['曾任單位名稱(選填)']:'';
					
					$j =  JobTitle::where('title', $item['曾任職稱(選填)'])->first();
					$c_data['old_title_id']=(isset($j['id']))? $j['id']:0;

					
					$c_data['phone']=(isset($item['連絡電話(選填)']))? $item['連絡電話(選填)']:'';
					$c_data['email']=(isset($item['電子郵件信箱(選填)']))? $item['電子郵件信箱(選填)']:'';
					$c_data['url']=(isset($item['相關資料網址(選填)']))? $item['相關資料網址(選填)']:'';

					$SpecialtyList =  SpecialtyList::where('title', $item['學門專長'])->first();
					$c_data['specialty_id']=1;

					$j =  Source::where('title', $item['學門專長資料來源'])->first();
					$c_data['specialty_source']=$j['id'];

					$Academic =  Academic::where('title', $item['學術專長'])->first();
					$c_data['academic_id']=$item['學術專長'];


					$j =  Source::where('title', $item['學術專長資料來源'])->first();
					$c_data['academic_source']=$j['id'];


					$j =  Member::where('username', $item['建立者'])->first();
					$c_data['member_id']=$j['id'];

					
					print_r($c_data);exit;
					$com=Committeeman::updateOrCreate($c_data);
				// exit;
					$committeeman_id = $com->id;
					// exit;
					$s_data=array(
						'title_id' =>$SpecialtyList['id'],
						'committeeman_id' =>$com->id,
						'writer_id' =>	$c_data['member_id'],
						'create_date'=>	date('Y/m/d'),
						'specialty_sources_id' => $c_data['specialty_source']
					);

					Specialty::updateOrCreate($s_data);

					$a_data=array(
						'title' =>$item['學術專長'],
						'committeeman_id' =>$com->id,
						'writer_id' =>$c_data['member_id'],
						'create_date'=>date('Y/m/d'),
						'specialty_sources_id' => $c_data['academic_source']
					);

					Academic::updateOrCreate($a_data);

					// $p = Product::where('no', $item['no'])->first();
					// if ($p == null) continue;
					// $price = json_decode($p->price, true);
					// if ($price['data'][0]['price_new'] == 0 && $item['price_new1'] != 0){
						
					// 	$price = array(
					// 		"price_new_percent" => $item['price_new_percent'],
					// 		"price_old_percent" => $item['price_old_percent'],
					// 		"data"              => array()
					// 	);
					// 	for ($i=1; $i <= 10 ; $i++) { 
					// 		$price['data'][] = array(
					// 			'range_start' => intval($item['range_start'.$i]),
					// 			'range_end'   => intval($item['range_end'.$i]),
					// 			'price'       => intval($item['price'.$i]),
					// 			'price_new'   => intval($item['price_new'.$i]),
					// 			'price_old'   => intval($item['price_old'.$i])
					// 		);
					// 	}
					// 	$price = json_encode($price);
					// 	Product::where(['id'=>$p->id])->update(['price'=>$price]);
					// }
				}
				exit;

				//刪除
				// foreach ($data as $item) {
				// 	$p = Product::where('no', $item['no'])->first();
				// 	if ($p == null || $p->cover == '') continue;
				// 	Storage::delete($p->cover);
				// 	$p->forceDelete();
				// }
				
				foreach ($data as $key => &$item) {
					if ($item['no'] == '') unset($data[$key]);
					
					$classify_arr = [];
					foreach ($item['classify'] as &$val){
						if ($val == '') continue;
						$c = explode(">", $val);
						if (count($c) < 2) continue;
						$exist = ProductCategory::where('title', 'like', $c[0])->where(['lang'=>'tw'])->with('classify')->first();
						
						if ($exist != null) {
							$classify_exist = false;
							foreach ($exist->classify as $classify) {
								if ($classify->title == $c[1]) {
									$classify_exist = true;
									$classify_arr[] = $classify->id;
								}
							}
							if (!$classify_exist) {
								$pcs = ProductClassify::create([
									'lang'        => 'tw',
									'parent_id'   => 0,
									'category_id' => $exist->id,
									'title'       => $c[1],
									'status'      => 'on'
								]);
								ProductClassify::create([
									'lang'        => 'en',
									'parent_id'   => $pcs->id,
									'category_id' => $exist->id,
									'title'       => $c[1],
									'status'      => 'on'
								]);
								$classify_arr[] = $pcs->id;
								$val = $c[0].'><span class="text-danger">'.$c[1].'</span>';
							}
						} else {
							$pc = ProductCategory::create([
								'lang'      => 'tw',
								'parent_id' => 0,
								'title'     => $c[0],
								'status'    => 'on'
							]);
							
							ProductCategory::create([
								'lang'      => 'en',
								'parent_id' => $pc->id,
								'title'     => $c[0],
								'status'    => 'on'
							]);
							$pcs = ProductClassify::create([
								'lang'        => 'tw',
								'parent_id'   => 0,
								'category_id' => $pc->id,
								'title'       => $c[1],
								'status'      => 'on'
							]);
							$classify_arr[] = $pcs->id;
							ProductClassify::create([
								'lang'        => 'en',
								'parent_id'   => $pcs->id,
								'category_id' => $pc->id,
								'title'       => $c[1],
								'status'      => 'on'
							]);
							$val = '<span class="text-danger">'.$val.'</span>';
						}
					}
					$item['classify'] = $classify_arr;

					$brand_arr = [];
					foreach ($item['brand'] as &$val){
						if ($val == '') continue;
						$exist = AgencyBrand::where('name', 'like', $val)->where(['lang'=>'tw'])->first();
						if ($exist == null) {
							$ab = AgencyBrand::create([
								'lang'      => 'tw',
								'name'      => $val,
								'parent_id' => 0,
								'summary'   => '',
								'content'   => ''
							]);
							$brand_arr[] = $ab->id;
							AgencyBrand::create([
								'lang'      => 'en',
								'name'      => $val,
								'parent_id' => $ab->id,
								'summary'   => '',
								'content'   => ''
							]);
							$val = '<span class="text-danger">'.$val.'</span>';
						}else{
							$brand_arr[] = $exist->id;
						}
					}
					$item['brand'] = $brand_arr;

					$function_arr = [];
					foreach ($item['function'] as &$val){
						if ($val == '') continue;
						$exist = ProductFunction::where('title', 'like', $val)->where(['lang'=>'tw'])->first();
						if ($exist == null) {
							$ab = ProductFunction::create([
								'lang'      => 'tw',
								'title'      => $val,
								'parent_id' => 0
							]);
							$function_arr[] = $ab->id;
							ProductFunction::create([
								'lang'      => 'en',
								'title'      => $val,
								'parent_id' => $ab->id
							]);
							$val = '<span class="text-danger">'.$val.'</span>';
						}else{
							$function_arr[] = $exist->id;
						}
					}
					$item['function'] = $function_arr;


					$tag_arr = [];
					foreach ($item['tag'] as &$val){
						if ($val == '') continue;
						$exist = Tags::where('title', 'like', $val)->where(['lang'=>'tw'])->first();
						if ($exist == null) {
							$ab = Tags::create([
								'lang'      => 'tw',
								'title'      => $val,
								'parent_id' => 0
							]);
							$tag_arr[] = $ab->id;
							Tags::create([
								'lang'      => 'en',
								'title'      => $val,
								'parent_id' => $ab->id
							]);
							$val = '<span class="text-danger">'.$val.'</span>';
						}else{
							$tag_arr[] = $exist->id;
						}
					}
					$item['tag'] = $tag_arr;

					if($this->product_import($item)) $cnt++;
				}
				$this->js_output_and_redirect('成功匯入更新 '.$cnt.' 筆產品', 'mgr.product.import');
				// Session::put('import_data', $data);
				// $this->data['data'] = $data;
				// $this->data['th_title'] = $import->import_field();
			}else if ($request->post('action') == 'check') {
				
				foreach (Session::get('import_data') as $item) {
					if($this->product_import($item)) $cnt++;
					// $product = Product::where('no', $item['no'])->first();
					// $m = Member::where('id', '!=', 1)->where('username', 'like', "%".$item['manager']."%")->first();
					// $manager_id = 0;
					// if ($product != null && $m != null) {
					// 	$manager_id = $m->id;
					// 	$product->manager_refresh([$manager_id]);
					// }
					
				}
				$this->js_output_and_redirect('成功匯入 '.$cnt.' 筆產品', 'mgr.product');
				return;
			}
		}
		return view('mgr/template_import', $this->data);
	}

	public function get_teacher(){
	
		$data['privilege_end_date'] = Date('Y-m-d H:i:s', strtotime('+27 year'));

		print_r($data['privilege_end_date']);exit;
		print_r(time());exit;

		$str = '123456';
		$str_md5=md5($str);

		if($str_md5=='e10adc3949ba59abbe56e057f20f883e'){
			print '密碼正確';
		}else{
			print '錯誤';
		}
		
		print_r($str_md5);exit;
		return md5($str) ;//会生成一个32位的字符串



		$data['title']='自然系';
		Department::updateOrCreate($data);
		exit;
		// [工號] => 13026
		// [姓名] => 林慶俊
		// [身分證字號] => E121546391
		// [差勤單位代碼] => 066
		// [單位名稱] => 音樂學系
		// [職稱代碼] => 7038
		// [職稱] => 教授
		// [是否在職] => 在職
		// [人員類別代碼] => 58
		// [人員類別] => 教師
		// [密碼] => 3b9cd3d9f45ee6301fefba84a515ea0c

		$users = DB::connection('mysql')->table('members')		
					->get();
		// $users->toJson();
		// print_r($users);exit;
		foreach($users as $user){
			$user_json=json_encode($user);
			$user_arr=json_decode($user_json,true);


			// print_r(json_decode($user_arr,true));exit;
			$data=array(
				'number' =>$user_arr['工號']
			);
			print_r($user_arr);exit;
		}
		print_r($users);exit;
		
	}
	
	
}