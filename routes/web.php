<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Request;

use App\Http\Middleware\Locale;

use App\Http\Controllers\PdfController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('pdf', [PdfController::class, 'index']);

// Route::get('/', function () {
//     return view('welcome');
// });
// Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/', [Mgr\DashboardController::class, 'index'])->name('home');
Route::match(array('GET','POST'),'util/{any?}', [BaseController::class, 'util'])->name('util');

Route::match(array('GET','POST'),'util/title_data', [BaseController::class, 'title_data'])->name('title_data');
// Route::match(array('GET','POST'),'util/unit_data', [BaseController::class, 'unit_data'])->name('unit_data');

Route::get('locale/{locale}', [HomeController::class, 'swtich_locale'])->name('locale');
Route::post('signin', [HomeController::class, 'login'])->name('signin');
Route::post('linebot/callback', [LineBotController::class, 'callback']);
Route::get('linebot/send', [LineBotController::class, 'send']);

// Route::group([
//    'prefix' =>  Locale::prefix((string)(Request::segment(1))) 
// ], function() {
//     Route::get('/', [HomeController::class, 'index'])->name('home');
//     Route::get('about', [HomeController::class, 'about'])->name('about');
//     Route::get('brand', [HomeController::class, 'brand'])->name('brand');
//     Route::get('brand/{id}', [HomeController::class, 'brand_detail'])->name('brand.detail');
//     Route::get('news', [HomeController::class, 'news'])->name('news');
//     Route::get('news/list', [HomeController::class, 'news_list'])->name('news.list');
//     Route::get('news/{id}', [HomeController::class, 'news_detail'])->name('news.detail');
//     Route::get('products', [ProductController::class, 'index'])->name('products');
//     Route::get('products/search/{keyword?}', [ProductController::class, 'search'])->name('products.search');
//     Route::get('products/{id}', [ProductController::class, 'detail'])->name('products.detail');
//     Route::match(array('GET','POST'),'contact', [HomeController::class, 'contact'])->name('contact');
//     Route::match(array('GET','POST'),'products/data', [ProductController::class, 'data'])->name('products.data');
    
//     Route::get('shopping_flow', [HomeController::class, 'shopping_flow'])->name('shopping_flow');
//     Route::get('payment_method', [HomeController::class, 'payment_method'])->name('payment_method');
//     Route::get('privacy', [HomeController::class, 'privacy'])->name('privacy');
    
//     Route::get('login', [HomeController::class, 'login'])->name('login');
//     Route::match(array('GET','POST'),'register/{step?}', [HomeController::class, 'register'])->name('register');
//     Route::match(array('GET','POST'),'register_invoice', [HomeController::class, 'register_invoice'])->name('register_invoice');
//     Route::match(array('GET','POST'),'register_recpient', [HomeController::class, 'register_recpient'])->name('register_recpient');
//     Route::match(array('GET','POST'),'forgetpwd', [HomeController::class, 'forgetpwd'])->name('forgetpwd');
    
//     Route::get('member', [MemberController::class, 'index'])->name('member');
//     Route::match(array('GET','POST'),'member/edit', [MemberController::class, 'edit'])->name('member.edit');
//     Route::match(array('GET','POST'),'member/add_invoice', [MemberController::class, 'add_invoice'])->name('member.add_invoice');
//     Route::match(array('GET','POST'),'member/edit_invoice', [MemberController::class, 'edit_invoice'])->name('member.edit_invoice');
//     Route::match(array('GET','POST'),'member/del_invoice/{id?}', [MemberController::class, 'del_invoice'])->name('member.del_invoice');
//     Route::match(array('GET','POST'),'member/add_recipient', [MemberController::class, 'add_recipient'])->name('member.add_recipient');
//     Route::match(array('GET','POST'),'member/edit_recipient', [MemberController::class, 'edit_recipient'])->name('member.edit_recipient');
//     Route::match(array('GET','POST'),'member/del_recipient/{id?}', [MemberController::class, 'del_recipient'])->name('member.del_recipient');
//     Route::match(array('GET','POST'),'member/bill', [MemberController::class, 'bill'])->name('member.bill');
//     Route::match(array('GET','POST'),'member/bill/again', [MemberController::class, 'bill_again'])->name('member.bill.again');
//     Route::match(array('GET','POST'),'member/bill/confirm', [MemberController::class, 'bill_confirm'])->name('member.bill.confirm');
//     Route::match(array('GET','POST'),'member/bill/exchange', [MemberController::class, 'bill_exchange'])->name('member.bill.exchange');
//     Route::match(array('GET','POST'),'member/bill/return', [MemberController::class, 'bill_return'])->name('member.bill.return');

//     Route::get('cart', [CartController::class, 'index'])->name('cart');
//     Route::get('cart/step2', [CartController::class, 'step2'])->name('cart.step2');
//     Route::post('cart/confirm', [CartController::class, 'confirm'])->name('cart.confirm');
//     Route::post('cart/copy', [CartController::class, 'copy'])->name('cart.copy');
//     Route::match(array('GET','POST'),'cart/add', [CartController::class, 'add'])->name('cart.add');
//     Route::match(array('GET','POST'),'cart/update', [CartController::class, 'update'])->name('cart.update');
//     Route::match(array('GET','POST'),'cart/del', [CartController::class, 'del'])->name('cart.del');
    
//     Route::get('order/{order_no}', [OrderController::class, 'index'])->name('order');
//     Route::get('order/quotation/{order_no}', [OrderController::class, 'quotation'])->name('order.quotation');

//     Route::get('logout', [MemberController::class, 'logout'])->name('logout');
// });

Route::prefix('mgr')->name('mgr.')->group(function (){
    // Auth::routes();
    // Authentication Routes...
    Route::get('/', [Mgr\DashboardController::class, 'index'])->name('home');
    Route::get('simulate/{id?}', [Mgr\DashboardController::class, 'simulate_login'])->name('simulate');
    Route::get('login', [Mgr\SigninController::class, 'showLoginForm'])->name('login');
    Route::post('login', [Mgr\SigninController::class, 'login'])->name('login');
    Route::post('logout', [Mgr\SigninController::class, 'logout'])->name('logout');

    Route::get('about', [Mgr\AboutController::class, 'index'])->name('about');
    Route::match(array('GET','POST'),'about/add', [Mgr\AboutController::class, 'add'])->name('about.add');
    Route::match(array('GET','POST'),'about/edit/{id?}', [Mgr\AboutController::class, 'edit'])->name('about.edit');
    Route::post('about/del', [Mgr\AboutController::class, 'del'])->name('about.del');

    Route::get('company', [Mgr\CompanyController::class, 'index'])->name('company');
    Route::match(array('GET','POST'),'company/add', [Mgr\CompanyController::class, 'add'])->name('company.add');
    Route::match(array('GET','POST'),'company/edit/{id?}', [Mgr\CompanyController::class, 'edit'])->name('company.edit');
    Route::post('company/del', [Mgr\CompanyController::class, 'del'])->name('company.del');

    Route::get('contact', [Mgr\ContactController::class, 'index'])->name('contact');
    // Route::match(array('GET','POST'),'contact/add', [Mgr\CompanyController::class, 'add'])->name('contact.add');
    Route::match(array('GET','POST'),'contact/success/{id?}', [Mgr\ContactController::class, 'success'])->name('contact.success');
    Route::post('contact/del', [Mgr\ContactController::class, 'del'])->name('contact.del');
    
    Route::get('agency_brand', [Mgr\AgencyBrandController::class, 'index'])->name('agency_brand');
    Route::match(array('GET','POST'),'agency_brand/add', [Mgr\AgencyBrandController::class, 'add'])->name('agency_brand.add');
    Route::match(array('GET','POST'),'agency_brand/edit/{id?}', [Mgr\AgencyBrandController::class, 'edit'])->name('agency_brand.edit');
    Route::post('agency_brand/del', [Mgr\AgencyBrandController::class, 'del'])->name('agency_brand.del');

    Route::get('carousel', [Mgr\CarouselController::class, 'index'])->name('carousel');
    Route::match(array('GET','POST'),'carousel/add', [Mgr\CarouselController::class, 'add'])->name('carousel.add');
    Route::match(array('GET','POST'),'carousel/edit/{id?}', [Mgr\CarouselController::class, 'edit'])->name('carousel.edit');
    Route::post('carousel/del', [Mgr\CarouselController::class, 'del'])->name('carousel.del');

    Route::get('page_banner', [Mgr\PageBannerController::class, 'index'])->name('page_banner');
    Route::match(array('GET','POST'),'page_banner/add', [Mgr\PageBannerController::class, 'add'])->name('page_banner.add');
    Route::match(array('GET','POST'),'page_banner/edit/{id?}', [Mgr\PageBannerController::class, 'edit'])->name('page_banner.edit');
    Route::post('page_banner/del', [Mgr\PageBannerController::class, 'del'])->name('page_banner.del');
    
    Route::match(array('GET','POST'),'setting', [Mgr\SettingController::class, 'index'])->name('settings');
    Route::match(array('GET','POST'),'setting/page/{type?}', [Mgr\SettingController::class, 'page'])->name('setting');
    Route::match(array('GET','POST'),'setting/page/shopping_flow', [Mgr\SettingController::class, 'page'])->name('setting.shopping_flow');
    Route::match(array('GET','POST'),'setting/page/payment', [Mgr\SettingController::class, 'page'])->name('setting.payment');
    Route::match(array('GET','POST'),'setting/page/privacy', [Mgr\SettingController::class, 'page'])->name('setting.privacy');
    Route::match(array('GET','POST'),'setting/page/home_intro', [Mgr\SettingController::class, 'page'])->name('setting.home_intro');
    Route::match(array('GET','POST'),'setting/page/default_carousel', [Mgr\SettingController::class, 'page'])->name('setting.default_carousel');
    Route::match(array('GET','POST'),'setting/page/footer_intro', [Mgr\SettingController::class, 'page'])->name('setting.footer_intro');

    Route::get('news/{lang?}', [Mgr\NewsController::class, 'index'])->name('news.tw');
    Route::get('news/en', [Mgr\NewsController::class, 'index'])->name('news.en');
    Route::match(array('GET','POST'),'news/add/{lang?}', [Mgr\NewsController::class, 'add'])->name('news.add');
    Route::match(array('GET','POST'),'news/edit/{id?}', [Mgr\NewsController::class, 'edit'])->name('news.edit');
    Route::post('news/del', [Mgr\NewsController::class, 'del'])->name('news.del');


    Route::get('product_category', [Mgr\ProductCategoryController::class, 'index'])->name('product_category');
    Route::match(array('GET','POST'),'product_category/add', [Mgr\ProductCategoryController::class, 'add'])->name('product_category.add');
    Route::match(array('GET','POST'),'product_category/edit/{id?}', [Mgr\ProductCategoryController::class, 'edit'])->name('product_category.edit');
    Route::post('product_category/del', [Mgr\ProductCategoryController::class, 'del'])->name('product_category.del');

    Route::get('product_classify', [Mgr\ProductClassifyController::class, 'index'])->name('product_classify');
    Route::match(array('GET','POST'),'product_classify/add', [Mgr\ProductClassifyController::class, 'add'])->name('product_classify.add');
    Route::match(array('GET','POST'),'product_classify/edit/{id?}', [Mgr\ProductClassifyController::class, 'edit'])->name('product_classify.edit');
    Route::post('product_classify/del', [Mgr\ProductClassifyController::class, 'del'])->name('product_classify.del');

    Route::get('product_function', [Mgr\ProductFunctionController::class, 'index'])->name('product_function');
    Route::match(array('GET','POST'),'product_function/add', [Mgr\ProductFunctionController::class, 'add'])->name('product_function.add');
    Route::match(array('GET','POST'),'product_function/edit/{id?}', [Mgr\ProductFunctionController::class, 'edit'])->name('product_function.edit');
    Route::post('product_function/del', [Mgr\ProductFunctionController::class, 'del'])->name('product_function.del');

    Route::get('product_package', [Mgr\ProductPackageController::class, 'index'])->name('product_package');
    Route::match(array('GET','POST'),'product_package/add', [Mgr\ProductPackageController::class, 'add'])->name('product_package.add');
    Route::match(array('GET','POST'),'product_package/edit/{id?}', [Mgr\ProductPackageController::class, 'edit'])->name('product_package.edit');
    Route::post('product_package/del', [Mgr\ProductPackageController::class, 'del'])->name('product_package.del');

    Route::get('product_weight', [Mgr\ProductWeightController::class, 'index'])->name('product_weight');
    Route::match(array('GET','POST'),'product_weight/add', [Mgr\ProductWeightController::class, 'add'])->name('product_weight.add');
    Route::match(array('GET','POST'),'product_weight/edit/{id?}', [Mgr\ProductWeightController::class, 'edit'])->name('product_weight.edit');
    Route::post('product_weight/del', [Mgr\ProductWeightController::class, 'del'])->name('product_weight.del');

    Route::get('product', [Mgr\ProductController::class, 'index'])->name('product');
    Route::match(array('GET','POST'),'product/add', [Mgr\ProductController::class, 'add'])->name('product.add');
    Route::match(array('GET','POST'),'product/edit/{id?}', [Mgr\ProductController::class, 'edit'])->name('product.edit');
    Route::post('product/del', [Mgr\ProductController::class, 'del'])->name('product.del');
    Route::post('product/check/{id?}', [Mgr\ProductController::class, 'check'])->name('product.check');
    
    Route::get('tags', [Mgr\TagsController::class, 'index'])->name('tags');
    Route::match(array('GET','POST'),'tags/add', [Mgr\TagsController::class, 'add'])->name('tags.add');
    Route::match(array('GET','POST'),'tags/edit/{id?}', [Mgr\TagsController::class, 'edit'])->name('tags.edit');
    Route::post('tags/del', [Mgr\TagsController::class, 'del'])->name('tags.del');

    // Route::match(array('GET','POST'),'users/product/{user_id?}', [Mgr\UserController::class, 'product'])->name('users.product');
    Route::match(array('GET','POST'),'users/product_price', [Mgr\UserController::class, 'product_price'])->name('users.product_price');
    Route::match(array('GET','POST'),'users/product/{user_id?}/{product_id?}', [Mgr\UserController::class, 'product'])->name('users.product');
    Route::match(array('GET','POST'),'users/add', [Mgr\UserController::class, 'add'])->name('users.add');
    Route::match(array('GET','POST'),'users/edit/{id?}', [Mgr\UserController::class, 'edit'])->name('users.edit');
    Route::match(array('GET','POST'),'users/review/{id?}', [Mgr\UserController::class, 'review'])->name('users.review');
    Route::match(array('GET','POST'),'users/data', [Mgr\UserController::class, 'data'])->name('users.data');
    Route::post('users/del', [Mgr\UserController::class, 'del'])->name('users.del');
    Route::get('users/{status?}', [Mgr\UserController::class, 'index'])->name('users');
    Route::get('users/new', [Mgr\UserController::class, 'index'])->name('users.new');

    Route::get('order', [Mgr\OrderController::class, 'index'])->name('order');
    Route::match(array('GET','POST'), 'order/data', [Mgr\OrderController::class, 'data'])->name('order.data');
    Route::match(array('GET','POST'), 'order/export', [Mgr\OrderController::class, 'export'])->name('order.export');
    Route::match(array('GET','POST'), 'order/notification/{id?}/{action?}', [Mgr\OrderController::class, 'notification'])->name('order.notification');
    Route::get('order/detail/{id?}', [Mgr\OrderController::class, 'detail'])->name('order.detail');
    Route::match(array('GET','POST'),'order/action/{action?}', [Mgr\OrderController::class, 'action'])->name('order.action');
    Route::match(array('GET','POST'),'order/quantity_change', [Mgr\OrderController::class, 'quantity_change'])->name('order.quantity_change');
    
    
    Route::get('member', [Mgr\MemberController::class, 'index'])->name('member');
    Route::match(array('GET','POST'),'member/add', [Mgr\MemberController::class, 'add'])->name('member.add');
    Route::match(array('GET','POST'),'member/data', [Mgr\MemberController::class, 'data'])->name('member.data');
    Route::match(array('GET','POST'),'member/edit/{id?}', [Mgr\MemberController::class, 'edit'])->name('member.edit');
    Route::post('member/del', [Mgr\MemberController::class, 'del'])->name('member.del');
    Route::post('member/switch_toggle', [Mgr\MemberController::class, 'switch_toggle'])->name('member.switch_toggle');
    Route::get('member/unlink_line/{id?}', [Mgr\MemberController::class, 'unlink_line'])->name('member.unlink_line');

    Route::match(array('GET','POST'),'member/department_manage', [Mgr\MemberController::class, 'department_manage'])->name('member.department_manage');
    Route::match(array('GET','POST'),'member/department_add', [Mgr\MemberController::class, 'department_add'])->name('member.department_add');
    Route::match(array('GET','POST'),'member/department_edit/{id?}', [Mgr\MemberController::class, 'department_edit'])->name('member.department_edit');
    Route::match(array('GET','POST'),'member/department_view/{id?}', [Mgr\MemberController::class, 'department_view'])->name('member.department_view');

    Route::get('member_department', [Mgr\MemberDepartmentController::class, 'index'])->name('member_department');
    Route::match(array('GET','POST'),'member_department/add', [Mgr\MemberDepartmentController::class, 'add'])->name('member_department.add');
    Route::match(array('GET','POST'),'member_department/edit/{id?}', [Mgr\MemberDepartmentController::class, 'edit'])->name('member_department.edit');
    Route::post('member_department/del', [Mgr\MemberDepartmentController::class, 'del'])->name('member_department.del');

    Route::get('privilege', [Mgr\PrivilegeController::class, 'index'])->name('privilege');
    Route::match(array('GET','POST'),'privilege/add', [Mgr\PrivilegeController::class, 'add'])->name('privilege.add');
    Route::match(array('GET','POST'),'privilege/edit/{id?}', [Mgr\PrivilegeController::class, 'edit'])->name('privilege.edit');
    Route::post('privilege/del', [Mgr\PrivilegeController::class, 'del'])->name('privilege.del');

    Route::match(array('GET','POST'),'report/stock/{action?}', [Mgr\ReportController::class, 'stock'])->name('report.stock');
    Route::match(array('GET','POST'),'report/company/{action?}', [Mgr\ReportController::class, 'company'])->name('report.company');
    Route::match(array('GET','POST'),'report/collection/{action?}', [Mgr\ReportController::class, 'collection'])->name('report.collection');
    Route::match(array('GET','POST'),'report/bill/{action?}', [Mgr\ReportController::class, 'bill'])->name('report.bill');

    /////0322
    Route::get('recommend_form', [Mgr\RecommendFormController::class, 'index'])->name('recommend_form');
    Route::match(array('GET','POST'),'recommend_form/add', [Mgr\RecommendFormController::class, 'add'])->name('recommend_form.add');
    Route::match(array('GET','POST'),'recommend_form/edit/{id?}', [Mgr\RecommendFormController::class, 'edit'])->name('recommend_form.edit');
    Route::post('recommend_form/del', [Mgr\RecommendFormController::class, 'del'])->name('recommend_form.del');

    ///外審查詢 
    Route::match(array('GET','POST'),'committeeman/title_data', [Mgr\CommitteemanController::class, 'title_data'])->name('title_data');
    Route::match(array('GET','POST'),'committeeman/unit_data', [Mgr\CommitteemanController::class, 'unit_data'])->name('unit_data');
    Route::match(array('GET','POST'),'committeeman/pdf_output', [Mgr\CommitteemanController::class, 'pdf_output'])->name('pdf_output');
    Route::match(array('GET','POST'),'committeeman/pdf_export', [Mgr\CommitteemanController::class, 'pdf_export'])->name('committeeman.pdf_export');
    
    Route::get('committeeman', [Mgr\CommitteemanController::class, 'index'])->name('committeeman');
    Route::match(array('GET','POST'),'committeeman/add_specialty/{id?}', [Mgr\CommitteemanController::class, 'add_specialty'])->name('committeeman.add_specialty');
    Route::match(array('GET','POST'),'committeeman/search', [Mgr\CommitteemanController::class, 'search'])->name('committeeman.search');
    /// test 
    Route::match(array('GET','POST'),'committeeman/get_specialties', [Mgr\CommitteemanController::class, 'get_specialties'])->name('committeeman.get_specialties');
    ///
    Route::match(array('GET','POST'),'committeeman/search_data', [Mgr\CommitteemanController::class, 'search_data'])->name('committeeman.search_data');
    Route::match(array('GET','POST'),'committeeman/data', [Mgr\CommitteemanController::class, 'data'])->name('committeeman.data');
    Route::match(array('GET','POST'),'committeeman/add', [Mgr\CommitteemanController::class, 'add'])->name('committeeman.add');

    Route::post('committeeman/switch_toggle', [Mgr\CommitteemanController::class, 'switch_toggle'])->name('committeeman.switch_toggle');
    Route::match(array('GET','POST'),'committeeman/output_data', [Mgr\CommitteemanController::class, 'output_data'])->name('committeeman.output_data');
    Route::match(array('GET','POST'),'committeeman/modification_record', [Mgr\CommitteemanController::class, 'modification_record'])->name('committeeman.modification_record');
    Route::match(array('GET','POST'),'committeeman/department_manage', [Mgr\CommitteemanController::class, 'department_manage'])->name('committeeman.department_manage');
    Route::match(array('GET','POST'),'committeeman/department_add', [Mgr\CommitteemanController::class, 'department_add'])->name('committeeman.department_add');
    Route::match(array('GET','POST'),'committeeman/edit/{id?}', [Mgr\CommitteemanController::class, 'edit'])->name('committeeman.edit');
    Route::match(array('GET','POST'),'committeeman/edit_academics/{id?}', [Mgr\CommitteemanController::class, 'edit_academics'])->name('committeeman.edit_academics');
    Route::match(array('GET','POST'),'committeeman/add_academics/{id?}', [Mgr\CommitteemanController::class, 'add_academics'])->name('committeeman.add_academics');
    Route::match(array('GET','POST'),'committeeman/del_academics', [Mgr\CommitteemanController::class, 'del_academics'])->name('committeeman.del_academics');

    Route::match(array('GET','POST'),'committeeman/specialty/{id?}', [Mgr\CommitteemanController::class, 'specialty'])->name('committeeman.specialty');
    Route::match(array('GET','POST'),'committeeman/specialty_list/{id?}', [Mgr\CommitteemanController::class, 'specialty_list'])->name('committeeman.specialty_list');
    Route::match(array('GET','POST'),'committeeman/add_new_specialty/{id?}', [Mgr\CommitteemanController::class, 'add_new_specialty'])->name('committeeman.add_new_specialty');
    Route::match(array('GET','POST'),'committeeman/edit_specialty/{id?}', [Mgr\CommitteemanController::class, 'edit_specialty'])->name('committeeman.edit_specialty');
    Route::match(array('GET','POST'),'committeeman/del_specialty/{id?}', [Mgr\CommitteemanController::class, 'del_specialty'])->name('committeeman.del_specialty');

    Route::match(array('GET','POST'),'committeeman/academics/{id?}', [Mgr\CommitteemanController::class, 'academics'])->name('committeeman.academics');
    Route::match(array('GET','POST'),'committeeman/academics_list/{id?}', [Mgr\CommitteemanController::class, 'academics_list'])->name('committeeman.academics_list');
    Route::match(array('GET','POST'),'committeeman/add_specialty', [Mgr\CommitteemanController::class, 'add_specialty'])->name('committeeman.add_specialty');
    Route::match(array('GET','POST'),'committeeman/add_committeeman', [Mgr\CommitteemanController::class, 'add_committeeman'])->name('committeeman.add_committeeman');
    Route::match(array('GET','POST'),'committeeman/data_have', [Mgr\CommitteemanController::class, 'data_have'])->name('committeeman.data_have');
    Route::match(array('GET','POST'),'committeeman/data_not', [Mgr\CommitteemanController::class, 'data_not'])->name('committeeman.data_not');

    Route::match(array('GET','POST'),'committeeman/get_other_title', [Mgr\CommitteemanController::class, 'get_other_title'])->name('committeeman.get_other_title');
    Route::match(array('GET','POST'),'committeeman/get_teacher', [Mgr\CommitteemanController::class, 'get_teacher'])->name('committeeman.get_teacher');
    Route::match(array('GET','POST'),'committeeman/import', [Mgr\CommitteemanController::class, 'import'])->name('committeeman.import');
    /////0522
    Route::get('change_record', [Mgr\ChangeRecordController::class, 'index'])->name('change_record');
    Route::match(array('GET','POST'),'change_record/data', [Mgr\ChangeRecordController::class, 'data'])->name('change_record.data');

    Route::match(array('GET','POST'),'mail/log', [Mgr\MailController::class, 'log'])->name('mail.log');
    Route::match(array('GET','POST'),'mail/data', [Mgr\MailController::class, 'data'])->name('mail.data');
    Route::match(array('GET','POST'),'mail/word', [Mgr\MailController::class, 'word'])->name('mail.word');

    Route::get('{any}', [Mgr\DashboardController::class, 'index']);

    Route::fallback([Mgr\DashboardController::class, 'index']);
});



// Route::fallback([Mgr\NewsController::class, 'list']);
