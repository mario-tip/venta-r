<?php
namespace App\Http\Controllers;

use Intervention\Image\ImageManagerStatic as Image;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade as PDF;
use App\Mail\MessageReceived;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Warehouse;
use App\Customer;
use App\Company;
use App\Product;
use App\Category;
use App\Sector;
use App\Order;
use App\Stock;
use App\Sale;
use App\User;
use DB;

class AppController extends ApiController{

  public function AddNewCustomer(Request $request){

      $rules = [
        'socialReason' => 'required',
        'phone' => 'required',
        'email' => 'required|email',
        'sectorId' => 'required|numeric',
        'externalNumber' => 'required'
      ];
      $this->validate($request, $rules);

      $statement   = DB::select("SHOW TABLE STATUS LIKE 'orders'");
                $nextId      = $statement[0]->Auto_increment;
                $_folio      = str_pad($nextId, 5, '0', STR_PAD_LEFT);

      $request['code'] = $_folio;
      $request['company_id'] = $request->user()->company->id;
      $request['social_reason'] = $request->socialReason;
      $request['sector_id'] = $request->sectorId;
      if ($request->has('externalNumber')) {
        $request['external_number'] = $request->externalNumber;
      }
      if ($request->has('internalNumber')) {
        $request['internal_number'] = $request->internalNumber;
      }
      try {
        $resp = Customer::create($request->all());
        $data =[
          'message'=>'Se guardo el cliente',
          'status' => true,
          'customer' => $resp
        ];

      } catch (\Exception $e) {
        $data =[
          'message'=>'No se pudo guardar cliente',
          'status' => false,
          'customer' => $e
        ];
      }

      return $this->showSave($data);

    }

  public function GetCustomers(Request $request){
      $data = $request->user()->company->customers_emma;
      return ['customers' => $data];
    }

  public function GetProducts(Request $request){
      $id_company = $request->user()->company_id;
      $data = Product::join('stocks','products.id','=','stocks.product_id')
      ->select('products.id AS id', 'name' ,'description' ,'img' ,'bazaarPrice' ,'expoPrice' ,'barCode' ,'category_id AS categoryId',
      'stocks.quantity AS availability','stocks.warehouse_id AS wareHouseId','company_id AS companyId')
      ->where('company_id',$id_company)->get();

      return ['products' => $data ];
    }

  public function data(Request $request){

      $id_company = $request->user()->company_id;

      $user = User::where('id', $request->user()->id)->select('id' ,'name' ,'last_name AS lastName' ,'img' ,'email' ,'phone' ,'address' ,'user_type AS userType' ,'company_id AS companyId')->first();
      $compania   = Company::where('id',$id_company)->select('id','name','description','address','phone','rfc','email_billing AS emailBilling','social_reason AS socialReason','page_web AS webPage','state','cedis_id AS cedisId','colony')->first();
      $categorias = Category::whereCompany_id($id_company)->select('id', 'name', 'description', 'company_id AS companyId')->get();
      $sectores   = Sector::whereCompany_id($id_company)->select('id', 'name', 'description', 'company_id AS companyId')->get();
      $clientes   = Customer::whereCompany_id($id_company)->select('id', 'social_reason AS socialReason')->get();

      return [
        'user' => $user,
        'company' => $compania,
        'categories' => $categorias,
        'sectors' => $sectores,
        'customers' => $clientes,
      ];
    }

  public function GetWarehouses(Request $request){
      $data = $request->user()->company->warehouse_emma;
      return ['wareHouses' => $data];
    }

  public function GenerateOrder(Request $request){

      $rules = [
        'userId'=> 'required|numeric',
        'customerId'=> 'required|numeric',
        'type'=> 'required|numeric',
        'sales'=> 'required',
      ];

      $this->validate($request, $rules);

      $client = Customer::findOrfail($request->customerId);
      $user = User::findOrfail($request->userId);
      $id_company = $request->user()->company_id;

      $order     = count(Order::where('company_id',$id_company)->get())+1;
      $_folio    = str_pad($order, 5, '0', STR_PAD_LEFT);

      $statement   = DB::select("SHOW TABLE STATUS LIKE 'orders'");
                $nextId      = $statement[0]->Auto_increment;
                $order_number = str_pad($nextId, 8, '0', STR_PAD_LEFT);

      $data = $request->all();
      $req['orderType'] = $data['type'];
      $req['folio'] = $_folio;
      $req['user_id'] = $user->id;
      $req['customer_id'] = $client->id;
      $req['order_number'] = $order_number;
      $req['company_id'] = $request->user()->company->id;

      $newOrder = Order::make($req);
      $newOrder->save();

      foreach ($data['sales'] as $key => $value) {

        $sale['quantity'] = $value['quantity'];
        $price = Product::findOrfail($value['productId']);
        if ($data['type'] == 1) {
          $idP = $price->bazaarPrice;
        }else{
          $idP = $price->expoPrice;
        }
        $sale['total'] = $idP * $value['quantity'];
        $sale['order_id'] = $newOrder['id'];
        $sale['product_id'] = $value['productId'];
        $sale['type_finish'] = $value['type_finish'];

        $busca = Stock::where('warehouse_id', $value['warehouseId'])
                ->where('product_id', $value['productId'])
                ->first();

        $stock_cant = $busca['quantity'];
        $_resta = $stock_cant - $value['quantity'];

        Stock::where('id',$busca['id'])
              ->update(['quantity' => $_resta]);

        $newSale = Sale::make($sale);
        $newSale->save();
      }

      $data_order = Order::findOrfail($newOrder->id);
      $data_sales = Sale::where('order_id', $newOrder->id)->with('product')->get();

        $tot = 0;
        foreach ($data_sales as $key2 => $value2) {
          $var = 'acabado de Oro fino';
          $tot = $tot + $value2['total'];
          if ($data_order['orderType'] == 1) {
            $data_sales[$key2]['price'] = $data_sales[$key2]['product']->bazaarPrice;
          }else{
            $data_sales[$key2]['price'] = $data_sales[$key2]['product']->expoPrice;
          }
        }

        $data_order['totales'] = $tot;
        $data_order['iva'] = $tot * 16 /100;
        $data_order['subtotal'] = $tot - $data_order['iva'];

        // IDEA: read the view and send params
        $pdf = PDF::loadView('order', compact('client','user','data_order','data_sales','lop'));
        // IDEA: save the view in pdf format
        $pdf->save('docs/orders/' . $user->name.'.pdf');
        // IDEA: encode in base 64 the file pdf
        $myPdf = base64_encode($pdf->output());

        // Instantiation and passing `true` enables exceptions
        $mail = new PHPMailer(true);

        if (!$client->email == null) {
          try {
              //Server settings
              $mail->SMTPDebug = 2;                                       // Enable verbose debug output
              $mail->isSMTP();                                            // Set mailer to use SMTP
              $mail->Host       = 'smtp.ionos.mx';  // Specify main and backup SMTP servers
              $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
              $mail->Username   = 'edwin.rosales@altatec.com.mx';                     // SMTP username
              $mail->Password   = 'pxrUG5ey';                               // SMTP password
              $mail->SMTPSecure = 'tls';                                  // Enable TLS encryption, `ssl` also accepted
              $mail->Port       = 587;                                    // TCP port to connect to

              //Recipients
              $mail->setFrom('ventasantasofia@hotmail.com', 'Santa Sofia');
              $mail->addAddress($client->email, 'Cliente distinguido');
              // Attachments
              $mail->addAttachment(public_path().'/docs/orders/'.$user->name.'.pdf');

              // Content
              $mail->isHTML(true);                                  // Set email format to HTML
              $mail->Subject = 'Orden de compra Bazar Santa Sofia';
              $mail->Body    = '<b>Poner plantilla Choche';
              $mail->AltBody = 'Orden de compra Bazar Santa Sofia';

              // $mail->send();
          } catch (Exception $e) {}
        }

      $data_o = Order::whereId($newOrder->id)
      ->select('id','folio','user_id AS userId','customer_id AS customerId','created_at AS createdAt', 'order_number AS orderNumber')
      ->with('sales')->first();

      return [
        'message'=>'Se genero la orden',
        'status' => true,
        'order' => $data_o,
        'pdf' => $myPdf
      ];
    }

  public function GetOrders(Request $request){
      $id_company = $request->user()->company_id;
      $ordenes = Order::where('company_id',$id_company)
      ->where( 'created_at', '>', Carbon::now()->subMonth())
      ->select('id','folio','user_id AS userId','customer_id AS customerId','created_at AS createdAt')
      ->with('sales')->get();

      if ($request->has('from_date','to_date') ) {
        $start = Carbon::parse($request->from_date)->startOfDay();
        $end = Carbon::parse($request->to_date)->endOfDay();
          $order->whereBetween('created_at', [$start, $end] )->get();
      }

      $data = ['orders' => $ordenes];

      $test = $data['orders'];

      foreach ($test as $key => $value1) {
        $tot = 0;
        foreach ($value1['sales'] as $key2 => $value2) {
          $tot = $tot + $value2['total'];
          $test[$key]['total'] = $tot;
          $datos = Product::join('stocks','products.id','=','stocks.product_id')
          ->select('products.id AS id', 'name' ,'description' ,'img' ,'bazaarPrice' ,'expoPrice' ,'barCode' ,'category_id AS categoryId',
          'stocks.quantity AS availability','stocks.warehouse_id AS wareHouseId','company_id AS companyId')
          ->where('products.id', $value2['productId'])
          ->first();
          $data['orders'][$key]['sales'][$key2]['product'] = $datos;
          $data['orders'][$key]['sales'][$key2]['product']['img'] = $this->ReturnImage($datos->barCode);

        }
      }

      return $data;
    }

  public function SyncOrders(Request $request){

      $rules = [
        'orders' => 'required|array',
        'orders.*.userId'=> 'required|numeric',
        'orders.*.customerId'=> 'required|numeric',
        'orders.*.type'=> 'required|numeric',
        'orders.*.sales'=> 'required|array',
      ];
      $this->validate($request, $rules);

      $data = $request['orders'];
      $id_c = $request->user()->company->id;


      foreach ($data as $key1 => $value1) {

        $statement = DB::select("SHOW TABLE STATUS LIKE 'orders'");
        $order     = count(Order::where('company_id', $id_c)->get())+1;
        $_folio    = str_pad($order, 5, '0', STR_PAD_LEFT);

        $save_o['orderType'] = $value1['type'];
        $save_o['folio'] = $_folio;
        $save_o['user_id'] = $value1['userId'];
        $save_o['customer_id'] = $value1['customerId'];
        $save_o['company_id'] = $id_c;
        $newOrder = Order::make($save_o);
        $newOrder->save();

        foreach ($value1['sales'] as $key2 => $value2) {

          $price = Product::findOrfail($value2['productId']);
            if ($value1['type'] == 1) {
              $idP = $price->bazaarPrice;
            }else{
              $idP = $price->expoPrice;
            }
          $save_s['quantity'] = $value2['quantity'];
          $save_s['order_id'] = $newOrder->id;
          $save_s['product_id'] = $value2['productId'];
          $save_s['total'] = $idP * $value2['quantity'];

          $busca = Stock::where('warehouse_id', $value2['warehouseId'])
              ->where('product_id', $value2['productId'])
              ->first();

          $stock_cant = $busca['quantity'];
          $_resta = $stock_cant - $value2['quantity'];

          Stock::where('id',$busca['id'])
          ->update(['quantity' => $_resta]);

          $newSale = Sale::make($save_s);
          $newSale->save();
        }
      }

      $response = [
        'message' => 'Se guardaron la ordenes de venta',
        'status' => true
      ];
      return $response;
    }

  public function GetOrder(Request $request){

    $rules = ['order_number' => 'required']; $this->validate($request, $rules);


    $data_order = Order::findOrfail($request->order_number);
    $client = Customer::findOrfail($data_order->customer_id);
    $user = User::findOrfail($data_order->user_id);
    $data_sales = Sale::where('order_id', $data_order->id)->with('product')->get();

      $tot = 0;
      foreach ($data_sales as $key2 => $value2) {
        $tot = $tot + $value2['total'];
        if ($data_order['orderType'] == 1) {
          $data_sales[$key2]['price'] = $data_sales[$key2]['product']->bazaarPrice;
        }else{
          $data_sales[$key2]['price'] = $data_sales[$key2]['product']->expoPrice;
        }
      }

      $data_order['totales'] = $tot;
      $data_order['iva'] = $tot * 16 /100;
      $data_order['subtotal'] = $tot - $data_order['iva'];

    $pdf = PDF::loadView('order', compact('client','user','data_order','data_sales'));
        // IDEA: save the view in pdf format
        $pdf->save('docs/orders/' . $user->name.'.pdf');
        // IDEA: encode in base 64 the file pdf
        $myPdf = base64_encode($pdf->output());

        $data = [
          'status' => true,
          'message' => 'Orden generada',
          'orderNumber'=> $data_order->order_number,
          'pdf' => $myPdf
        ];

    return $data;
  }

  public function sendImage(){

    $folder = public_path('imagenes_santaf');
    $files = scandir($folder);
    $data = [];
    // foreach ($files as $key2 => $value2) {
    //     if (condition) {}
    //   $corta = explode('.', $files[$key2]);
    //     }

    $product = Product::all('id','barCode');

    foreach ($product as $key => $value) {

      $mi_imagen = public_path().'/imagenes_santaf/'.$value->barCode.'.jpg';

      if (@getimagesize($mi_imagen)) {
      // hacer el update a la base con el archivo en base 64
        $img = file_get_contents($mi_imagen);
        $base64 = base64_encode($img);
        Product::where('id', $value->id)->update(['img' => $base64]);
      }
    }
    return $product;
  }

  public function getImage(Request $request){

    $rules = ['productId' => 'required']; $this->validate($request, $rules);

    $p = Product::findOrfail($request->productId);

    $mi_imagen = public_path().'/imagenes_santaf/'.$p->barCode.'.jpg';

    if (@getimagesize($mi_imagen)) {
      $img = 'imagenes_santaf/'.$p->barCode.'.jpg';
      // file_get_contents($mi_imagen);
      //   $base64 = base64_encode($img);

        $data = [
          'status' => true,
          'message' => 'Se encontro imagen',
          'img' => $img
        ];
        return $data;
    }

    $data = [
      'status' => false,
      'message' => 'no se encontro imagen',
      'img' => null
    ];

    return $data;

  }

  public function Getbarcode(Request $request){

    $folder = public_path('imagenes_santaf');
    $files = scandir($folder);
    $data = [];
    foreach ($files as $key2 => $value2) {
      $corta = explode('.', $files[$key2]);
        }

    $data =  Product::all('id','barCode');
    return $data;
  }

  public function GetProduct(Request $request){

    $rules = ['barCode' => 'required']; $this->validate($request, $rules);

    $id_company = $request->user()->company->id;

    $datos = Product::join('stocks','products.id','=','stocks.product_id')
    ->select('products.id AS id', 'name' ,'description' ,'img' ,'bazaarPrice' ,'expoPrice' ,'barCode' ,'category_id AS categoryId',
    'stocks.quantity AS availability','stocks.warehouse_id AS wareHouseId','company_id AS companyId')
    ->where('company_id', $id_company)
    ->where('barCode', $request->barCode)
    ->first();


    if ($datos) {
      $datos['img'] = $this->ReturnImage($request->barCode);

      $data = [
        'status' => true,
        'message' => 'Se encontro producto',
        'product' => $datos
      ];
    }else {
      $data = [
        'status' => false,
        'message' => 'No se encontro producto en el almacen.',
        'product' => null
      ];
    }

    return $data;
  }

  public static function ReturnImage($value){

    $mi_img1 = glob(public_path().'/imagenes_santaf/'.$value.'.*');
    if($mi_img1){
      $corta = str_replace('/var/www/html/santasofia-backend/public/imagenes_santaf/', '', $mi_img1[0]);
      return $corta;
    }else {
      return null;
    }
  }

  public function GetStatistics(Request $request){

    $id_user = $request->user()->id;

    $rules = ['date' => 'required|date']; $this->validate($request, $rules);

    $date_start = new Carbon($request->date);
    $date_end = new Carbon($request->date);

    $start = $date_start->startOfWeek()->toDateString();
    $end = $date_end->endOfWeek()->toDateString();

    $order = Order::whereBetween('created_at', [$start, $end])
    ->with('salecat')
    ->where('user_id',$id_user)
    ->get();

    // return $order;

    if ($order != null) {
      $arr_cat = [];
      $monday=0; $tuesday=0; $wednesday=0; $thursday=0; $friday=0; $saturday=0; $sunday=0;

      foreach ($order as $key => $value) {
        $tot = 0;
        foreach ($order[$key]['salecat'] as $key1 => $value1){
            $tot = $tot + $value1['total'];
            $order[$key]['total'] = (float)money_format('%i',$tot);

            $nameCate = $value1['productcat']['category']['name'];
            if (isset($arr_cat[$nameCate])) {
              $var = $arr_cat[$nameCate];
              $var += $value1['total'];
              $arr_cat[$nameCate] = $var;
            }
            else {
              $arr_cat[$nameCate] = $value1['total'];
            }
          }

          $dia = new Carbon($value->created_at);

          switch ($dia->format('l')) {

            case 'Monday':
            $monday = $monday + $order[$key]['total'];
              break;

            case 'Tuesday':
            $tuesday = $tuesday + $order[$key]['total'];
              break;

            case 'Wednesday':
            $wednesday = $wednesday + $order[$key]['total'];
              break;

            case 'Thursday':
            $thursday = $thursday + $order[$key]['total'];
              break;

            $friday = $monday + $order[$key]['total'];
              break;

            case 'Friday':
            $friday = $friday + $order[$key]['total'];
              break;

            case 'Saturday':
              $saturday = $saturday + $order[$key]['total'];
              break;

            case 'Sunday':
              $sunday = $sunday + $order[$key]['total'];
              break;

            default:
              break;
          }
        }

        $data = [
          'monday' => (float)money_format('%i',$monday),
          'tuesday' => (float)money_format('%i',$tuesday),
          'wednesday' => (float)money_format('%i',$wednesday),
          'thursday' => (float)money_format('%i',$thursday),
          'friday' => (float)money_format('%i',$friday),
          'saturday' => (float)money_format('%i',$saturday),
          'sunday' => (float)money_format('%i',$sunday)
        ];

          $gran_total = 0;
        foreach ($data as $key => $value) {
          $gran_total = $gran_total + $value;
        }

        $porcent = [];
        foreach ($arr_cat as $key => $value) {
          $pecent = ($value*100)/$gran_total;
          array_push($porcent,['name'=> $key, 'percentage' => (float)money_format('%i',$pecent)]);
        }

      }else{
        $data = [
          'monday' => 0,
          'tuesday' => 0,
          'wednesday' => 0,
          'thursday' => 0,
          'friday' => 0,
          'saturday' => 0,
          'sunday' => 0
        ];
      }
    return ['sales'=>$data , 'saleCategories'=>$porcent];
  }

  public function GetCustomerId(Request $request){

    $rules = ['idCustomer' => 'required']; $this->validate($request, $rules);
    $clientes   = Customer::whereId($request->idCustomer)->select('id', 'code AS barCode', 'social_reason AS socialReason', 'rfc', 'phone', 'email', 'street', 'colony', 'city', 'state', 'cp', 'external_number AS externalNumber', 'internal_number AS internalNumber', 'sector_id AS sectorId', 'company_id AS companyId')->first();

    return $clientes ? ['customer' => $clientes] : ['message' => 'No existe el cliente'];

  }

  public function SaveStock(){
    $productos = Product::all('id');

    foreach ($productos as $key => $value) {
      $data = [
        'product_id' => $value->id,
        'warehouse_id' => 1,
        'quantity' => 100
      ];

      Stock::create($data);
    }
    return $productos;
  }
}
