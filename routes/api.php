<?php
use Illuminate\Http\Request;

Route::middleware('auth:api')->get('/user', function (Request $request) {
  return $request->user();
});

Route::post('oauth/token','\Laravel\Passport\Http\Controllers\AccessTokenController@issueToken');

Route::group([
    'prefix' => 'auth',
], function () {
    Route::post('login', 'AuthController@login');
    Route::post('signup', 'AuthController@signup');

    Route::group([
        'middleware' => 'auth:api',
    ], function () {
        Route::get('logout', 'AuthController@logout');
        Route::get('user', 'AuthController@user');
    });
});

Route::resource('users','UserController');
Route::get('getUsersByCompanyID','UserController@getUsersByCompanyID');
Route::get('getUserByID/{id}','UserController@getUserByID');
Route::get('drivers','UserController@getDrivers');
Route::resource('products','ProductController');
Route::resource('warehouses','WarehouseController');
Route::get('wareRoute','WarehouseController@WarehousesinRoute');
Route::resource('companies','CompanyController');
Route::resource('deliveries','DeliveryController');

Route::resource('orders','OrderController');
Route::post('date_filter','OrderController@filter_date');
Route::get('export-sales', 'OrderController@export');

Route::resource('payments','PaymentController');
Route::resource('categories','CategoryController');
Route::resource('sectores','SectorController');
Route::resource('stock','StockController');
Route::resource('sales','SaleController');
Route::post('refound','SaleController@refound');
Route::post('test','SaleController@test');
Route::get('refounds','SaleController@getRefound');
Route::resource('customers','CustomerController');
Route::get('clientes','CustomerController@getClientes');
Route::get('getcustomer','CustomerController@getCustomer');
Route::post('getCustomerByJSON','CustomerController@getCustomerByJSON');
Route::get('getCustomersByCompanyID','CustomerController@getCustomersByCompanyID');

Route::get('join','ProductController@join');
Route::post('testimg','ProductController@test');
Route::post('getProductByJSON','ProductController@getProductsByJson');
Route::resource('vehicles','VehicleController');
//
Route::get('product_vehicle','RouteController@GetProducts');
Route::resource('routes','RouteController');
Route::get('clients/{id}','RouteController@getClients');
Route::resource('customerspiv','PivotCustomerController');
Route::resource('wallets','WalletController');
Route::resource('discounts','DiscountController');

/*
  ANDROID APPLICATION RESOURCES
*/
Route::get('android_initialize','AndroidController@android_initialize');
Route::post('android_process_order','AndroidController@process_order');
Route::post('android_process_devolution','AndroidController@process_devolution');
Route::post('synchronizeData','AndroidController@synchronizeData');
Route::post('generateOrder','AndroidController@generate_order');
Route::post('generateDevolution','AndroidController@generate_devolution');
Route::get('android_get_stock','AndroidController@get_stock');
// este es un test
Route::get('responser','ResponseController@getData');
Route::get('getpdf','ResponseController@generatePDF');
Route::get('mails' , 'ResponseController@sendEmail');
Route::post('generateOrder','OrderController@generateOrder');

// IDEA: App Santa Sofia
Route::get('data','AppController@data');
Route::post('createnewcustomer','AppController@AddNewCustomer');
Route::get('getcustomers','AppController@GetCustomers');
Route::get('getproducts','AppController@GetProducts');
Route::get('getwarehouses','AppController@GetWarehouses');
Route::post('generateorder','AppController@GenerateOrder');
Route::get('getorders','AppController@GetOrders');
Route::post('synchronizeorders','AppController@SyncOrders');
Route::post('getorder','AppController@GetOrder');
Route::get('insertimg','AppController@sendImage');
Route::post('sendimage','AppController@getImage');
Route::post('getstatistics', 'AppController@GetStatistics');
Route::post('getcustomerid', 'AppController@GetCustomerId');
Route::post('getproduct','AppController@GetProduct');
// Route::get('savestock','AppController@SaveStock');
