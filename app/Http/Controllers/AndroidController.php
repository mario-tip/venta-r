<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Company;
use App\Warehouse;
use App\Route;
use App\Stock;
use App\Sector;
use App\Product;
use App\Payment;
use App\Category;
use App\Customer;
use App\PivotCustomer;
use App\Order;
use App\Sale;
use App\Vehicle;
use App\Wallet;
use Carbon\Carbon;
use DB;

class AndroidController extends ApiController
{

 /*
    METHOD THAT RETURNS ALL THE DATA FILTERED BY SELLER, ROUTE , DAY AND COMPANY, IT IS CALLED WHEN A USER WAS AUTHENTICATED IN THE ANDROID APPLICATION
 */
  public function android_initialize( Request $request ){

      $data = [];
      $validation = [];
      $user = $this->getUser($request);
      $day_of_week = $this->getDayOfWeek();
      $validation['company'] = $this->getCompany( $user->company_id ); //GETTING COMPANY
      $validation['cedis'] = $this->getCedis( $validation['company']->cedis_id ); //GETTING CEDIS
      $validation['warehouses'] = $this->getWareHouses($user->company_id); //GETTING WAREHOUSES
      $validation['vehicles'] = $this->getVehicles($user->company_id); //GETTING VEHICLES
      $validation['routes'] = (count($validation['vehicles']) > 0)? $this->getRoutes(Vehicle::where('company_id',$user->company_id)->pluck('id')) : []; //GETTING ROUTES
      $validation['customers'] = $this->getCustomers( $user->company_id); //GETTING CUSTOMERS
      $validation['products'] = $this->getProducts( $user->company_id ); //GETTING PRODUCTS

      //VALIDATING THAT ALL THE INFORMATION IS COMPLETED
      $valid = $this->validate_information($validation['company'],$validation['cedis'],$validation['warehouses'],$validation['vehicles'],$validation['routes'],$validation['customers'],$validation['products']);

      if( $valid == NULL ){

            $data['user'] = $user; //SAVING USER
            $data['company'] = $validation['company']; //SAVING COMPANY
            $data['cedis'] = $validation['cedis']; //SAVING CEDIS
            $vehicles_ids = Vehicle::where('company_id',$data['company']->id)->pluck('id'); //GETTING VEHICLES IDS OF THE COMPANIES
            $route = Route::whereIn('vehicle_id',$vehicles_ids)->where('user_id',$user->id)->first(); //GETTING MY ROUTE
            $data['products'] = $validation['products'];
            $data['product_categories'] = $this->getProductCategories( $data['company']->id );
            $data['sectors'] = $this->getSectors( $data['company']->id ); //GETTING SECTORS

            if( !$this->isNull($route) ){ //VALIDATING IF EXISTS A ROUTE FOR THE USER

                  $data['route'] = $route;
                  $data['vehicle'] = Vehicle::find($route->vehicle_id);
                  $data['warehouse'] = Warehouse::find($data['vehicle']->warehouse_id);
                  $customers_on_route = PivotCustomer::where('route_id',$route->id)->pluck('customer_id'); //GETTING IDS OF THE CUSTOMERS IN THE ROUTE

                  if( !$this->is_empty( $customers_on_route ) ){ //VALIDATING IF THERE ARE CUSTOMERS ON THE ROUTE

                      $data['customers'] = Customer::whereIn('id',$customers_on_route)->select('id','code',DB::raw('UC_DELIMETER(name," ",TRUE) as name'),DB::raw('UC_DELIMETER(last_name," ",TRUE) as last_name'),'phone',DB::raw('LOWER(email) as email'),'street','colony','city','state','cp','external_number','internal_number','latitude','longitude','company_id','sector_id')->orderBy('name', 'asc')->get();
                      $data['products'] = $this->getProductStock( $data['products'] , $data['warehouse'] ); //SAVING PRODUCTS WITH THEIR STOCK
                      $data['product_categories'] = Category::where('company_id',$data['company']->id)->select('id','company_id','name','description')->get(); //GETTING THE PRODUCT CATEGORIES
                      $data['orders'] = $this->getOrders($customers_on_route,$user->id); //GETTING ORDERS

                      return response()->json($data,200); //RESPONSE WITH ALL THE DATA

                  }
                  else{

                      return response()->json(['error'=>'No tienes clientes en la ruta'],400);

                  }

            }
            else{

              return response()->json(['error'=>'No tienes ruta para el dia de hoy'],400);

            }

      }
      else{

          return response()->json(['error'=>$valid],400);

      }}
  private function validate_information( $company , $cedis , $warehouses , $vehicles , $routes , $customers , $products ){

    if( !$this->isNull($company) ){

      if( !$this->is_empty($warehouses) ){

        if( !$this->isNull($cedis) ){

          if( !$this->is_empty($vehicles) ){

            if( !$this->is_empty($routes) ){

              if( !$this->is_empty($customers) ){

                if( !$this->is_empty($products) ){

                      return NULL; //EVERYTHING IS OK

                }
                else{

                    return 'No hay productos en tu empresa';

                }

              }
              else{

                return 'No hay clientes en tu empresa';

              }

            }
            else{

              return 'No hay rutas en tu empresa';

            }

          }
          else{

              return 'No hay vehiculos en tu empresa';

          }

        }
        else{

          return 'No se asignado ningun CEDIS a tu empresa';

        }

      }
      else{

        return 'No hay almacenes en tu empresa';

      }

    }
    else{

        return 'No se encontro la empresa en la que laboras';

    }

  }
  private function isNull( $object ){

      return $object == NULL;

  }
  private function is_empty( $object ){

      return count($object) == 0;

  }
  private function getOrders( $customers_ids , $seller ){

    $date = new Carbon();
    $date = $date->subMonths(3)->format('Y-m-d');
    $orders =  Order::join('customers', 'orders.customer_id', 'customers.id')
                            ->join('users','orders.user_id','users.id')
                            ->select('orders.*')
                            ->where('customers.deleted_at',NULL)
                            ->whereIn('customers.id',$customers_ids)
                            ->where('users.id',$seller)
                            ->where('orders.created_at','>=',$date )
                            ->orderBy('orders.id', 'asc')
                            ->get(); //GETTING ORDERS FILTERED BY SELLER AND CUSTOMERS INSIDE A ROUTE AND FROM 3 MONTHS UNTIL TODAY


    $i = 0;
    foreach ( $orders as $order ) { //JOINING ORDERS WITH SALES AND DEVOLUTIONS

        $sales = Sale::where('order_id',$order->id )->where('total','>',0)->get();

        $orders[$i]->sales = $sales;
        $cash_payment = Payment::where('order_id',$order->id)->where('method_payment','CASH')->first();
        $intern_credit = Payment::where('order_id',$order->id)->where('method_payment','INTERN_CREDIT')->first();
        $debit_card = Payment::where('order_id',$order->id)->where('method_payment','DEBIT_CARD')->first();
        $credit_card = Payment::where('order_id',$order->id)->where('method_payment','CREDIT_CARD')->first();
        $transference = Payment::where('order_id',$order->id)->where('method_payment','TRANSFERENCE')->first();
        $orders[$i]->cash_payment =  ( !$this->isNull( $cash_payment ) )?$cash_payment->quantity:0;
        $orders[$i]->intern_credit = ( !$this->isNull( $intern_credit ) )?$intern_credit->quantity:0;
        $orders[$i]->debit_card = ( !$this->isNull( $debit_card ) )?$debit_card->quantity:0;
        $orders[$i]->credit_card = ( !$this->isNull( $credit_card ) )?$credit_card->quantity:0;
        $orders[$i]->transference = ( !$this->isNull( $transference ) )?$transference->quantity:0;

        $devolutions = Sale::where('order_id',$order->id )->where('total','<=',0)->get();
        $orders[$i]->devolutions = $devolutions;
        $i++;

    }

    return $orders;

  }
  private function getProductStock( $products , $warehouse ){

    $i = 0;
    foreach ($products as $product) {

        $product_stock = Stock::where('warehouse_id',$warehouse->id)->where('product_id',$product->product_id)->first();
        if( $product_stock != NULL ){ //VERIFING THE AVAILABILITY OF THE PRODUCTS

            $products[$i]->availability = $product_stock->quantity;

        }
        else{

            $products[$i]->availability = 0;

        }

            $products[$i]->product_name = ucwords(strtolower( $products[$i]->product_name ));

        $i ++;

    }
    return $products;

  }
  private function getProducts( $company ){

    return  Product::where('company_id',$company)->select('products.id AS product_id',
                                                          'products.code AS product_code',
                                                          'products.name AS product_name',
                                                          'products.img AS image_product_path',
                                                          'products.description AS product_description',
                                                          'products.price AS product_price',
                                                          'products.category_id AS product_category_id'
                                                          )->orderBy('product_name', 'asc')->get(); //GETTING ALL THE PRODUCTS OF THE COMPANY

  }
  private function getProductCategories( $company ){

      return Category::where('company_id',$company)->get();

  }
  private function getSectors( $company ){

      return Sector::where('company_id', $company )->get();

  }
  private function getCustomers( $company ){

    return Customer::where('company_id',$company)->get();

  }
  private function getCompany( $company ){

    return Company::find( $company );

  }
  private function getCedis( $cedis ){

    return WareHouse::find($cedis);

  }
  private function getRoutes( $vehicles ){

    return Route::whereIn('vehicle_id',$vehicles)->get();

  }
  private function getVehicles( $company ){

    return Vehicle::where('company_id',$company)->get();

  }
  private function getWareHouses( $company ){

      return WareHouse::where('company_id',$company)->get();

  }
  private function getDayOfWeek(){

    $date = Carbon::now();
    $date = $date->dayOfWeek;

    switch ( $date ){

        case 0:{
          return "domingo";
        }
        case 1:{
          return "lunes";
        }
        case 2:{
          return "martes";
        }
        case 3:{
          return "miercoles";
        }
        case 4:{
          return "jueves";
        }
        case 5:{
          return "viernes";
        }
        case 6:{
          return "sabado";
        }

    }

  }
  private function getUser( Request $request ){

      $user = DB::table('users')->where('id',$request->user()->id)->
                        select('id','name','last_name','img',DB::raw('LOWER(email) as email') ,'phone','address','user_type','company_id','deleted_at as exists')
                        ->first();

            if($user->exists == null){
                $user->exists = "true";
            }
            else{
                $user->exists = "false";
            }
            return $user;

  }
  private function validate_order( $cash_payment, $intern_credit , $debit_card , $credit_card , $transference ,$discount , $total ){

      return ($cash_payment+$intern_credit+$debit_card+$credit_card+$transference+$discount) >= $total;

  }

  //ACTIONS

  public function process_order( Request $request ){

      $data = [];
      $user = $this->getUser($request);
      $vehicles_ids = Vehicle::where('company_id',$user->company_id)->pluck('id'); //GETTING VEHICLES IDS OF THE COMPANY
      $route = Route::whereIn('vehicle_id',$vehicles_ids)->where('user_id',$user->id)->first(); //GETTING MY ROUTE

      if( !$this->is_empty($vehicles_ids) ){

          if( !$this->isNull($route) ){

            $vehicle = Vehicle::find($route->vehicle_id);

            if( !$this->isNull($vehicle) ){

                $warehouse= Warehouse::find($vehicle->warehouse_id);

                $day_of_week = $this->getDayOfWeek();
                $order = json_decode($request->get('order'), true);
                $customer = Customer::find($order['customer_id']);
                $sales = $order['sales'];
                $total = 0;
                $discount = 0;

                foreach ($sales as $sale) { // OBTENINEDO TOTAL DE LA VENTA

                    $total += $sale['total'];
                    $discount += ($sale['discount']/100) * $sale['total'];
                    $product = Product::find($sale['product_id']);
                    $stock = Stock::where('warehouse_id',$warehouse->id)->where('product_id',$sale['product_id'])->first();

                    if( $stock->quantity < $sale['quantity'] ){ //VERIFICANDO QUE EL STOCK SEA SUFICIENTE PARA PODER VENDER LOS PRODUCTOS

                        return response()->json(['error'=>'No se pudo procesar la venta por falta de stock en el producto '.$product->name],400);

                    }

                }

                //Method payments
                $cash_payment = $order['cash_payment'];
                $intern_credit = $order['intern_credit'];
                $debit_card = $order['debit_card'];
                $credit_card = $order['credit_card'];
                $transference = $order['transference'];

                if( $this->validate_order( $cash_payment , $intern_credit , $debit_card , $credit_card , $transference , $discount , $total ) ){

                   $nextId = DB::select("SHOW TABLE STATUS LIKE 'orders'")[0]->Auto_increment;
                   $folio = str_pad($nextId, 5, '0', STR_PAD_LEFT);
                   $vehicles_ids = Vehicle::where('company_id',$user->company_id)->pluck('id'); //GETTING VEHICLES IDS OF THE COMPANIES

                   if(!$this->is_empty($vehicles_ids)){

                     $route = Route::whereIn('vehicle_id',$vehicles_ids)->where('user_id',$user->id)->first(); //GETTING MY ROUTE

                     if( !$this->isNull($route) ){

                       $vehicle = Vehicle::find($route->vehicle_id);
                       if( !$this->isNull($vehicle) ){

                         $warehouse = Warehouse::find($vehicle->warehouse_id);
                         if( !$this->isNull($warehouse) ){


                           $_order = [
                             'folio'=>$folio,
                             'customer_id'=>$order['customer_id'],
                             'user_id'=>$order['user_id'],
                             'customer_payment'=>$order['customer_payment'],
                             'customer_change'=>$order['customer_change']
                           ];

                           $_order = Order::make($_order);
                           $_order->save();
                           $data['order'] = $_order;
                           $data['order']['sales'] = [];

                           $i = 0;
                           foreach ($sales as $sale) {

                                 $sales[$i]['order_id'] = $_order->id;
                                 $sales[$i] = Sale::make($sales[$i]);
                                 //dd($sales[$i]);
                                 $sales[$i]->save();
                                 $stock = Stock::where('warehouse_id',$warehouse->id)->where('product_id',$sales[$i]['product_id'])->first();
                                 $stock->quantity = $stock->quantity - $sale['quantity'];
                                 $stock->save();
                                 $i ++;

                           }

                            $data['order']['sales'] = $sales;

                           if( $intern_credit > 0 ){

                              $wallet = Wallet::where('customer_id',$customer["id"])->first();
                              if( !$this->isNull($wallet) ){ //Si no existe una wallet para un cliente la crea

                                $wallet->credit = ($wallet->credit + $intern_credit);
                                $wallet->save();
                                $data['wallet'] = $wallet;

                                $c_payment = new Payment();
                                $c_payment->quantity = $intern_credit;
                                $c_payment->order_id = $_order->id;
                                $c_payment->method_payment = "INTERN_CREDIT";
                                $c_payment->save();
                                $data['payment']['intern_credit'] = $c_payment;

                              }
                              else{

                                  $wallet = new Wallet();
                                  $wallet->customer_id = $customer["id"];
                                  $wallet->company_id = $user->company_id;
                                  $wallet->credit = $intern_credit;
                                  $wallet->save();
                                  $data['wallet'] = $wallet;

                                  $c_payment = new Payment();
                                  $c_payment->quantity = $intern_credit;
                                  $c_payment->order_id = $_order->id;
                                  $c_payment->method_payment = "INTERN_CREDIT";
                                  $c_payment->save();
                                  $data['payment']['intern_credit'] = $c_payment;

                              }

                           }
                           if( $cash_payment > 0 ){

                              $c_payment = new Payment();
                              $c_payment->quantity = $cash_payment;
                              $c_payment->order_id = $_order->id;
                              $c_payment->method_payment = "CASH";
                              $c_payment->save();
                              $data['payment']['cash'] = $c_payment;

                           }
                           if( $debit_card > 0 ){

                             $d_payment = new Payment();
                             $d_payment->quantity = $debit_card;
                             $d_payment->order_id = $_order->id;
                             $d_payment->method_payment = "DEBIT_CARD";
                             $d_payment->save();
                             $data['payment']['debit_card'] = $d_payment;

                           }
                           if( $credit_card > 0 ){

                             $c_payment = new Payment();
                             $c_payment->quantity = $credit_card;
                             $c_payment->order_id = $_order->id;
                             $c_payment->method_payment = "CREDIT_CARD";
                             $c_payment->save();
                             $data['payment']['credit_card'] = $c_payment;

                           }
                           if( $transference > 0 ){

                             $t_payment = new Payment();
                             $t_payment->quantity = $transference;
                             $t_payment->order_id = $_order->id;
                             $t_payment->method_payment = "TRANSFERENCE";
                             $t_payment->save();
                             $data['payment']['transference'] = $t_payment;

                           }

                           return response()->json($data);

                         }
                         else{

                           return response()->json(['error'=>'El vehiculo no tiene asignado ningun almacen'],400);

                         }

                       }
                       else{

                         return response()->json(['error'=>'No tienes ningun vehiculo asignado'],400);

                       }

                     }
                     else{

                       return response()->json(['error'=>'No tienes una ruta asignada el dia de hoy'],400);

                     }

                   }
                   else{

                     return response()->json(['error'=>'No hay vehiculos en tu empresa'],400);

                   }

                }
                else{

                    return response()->json(['error'=>'Pago rechazado'],400);

                }

            }
            else{

                return response()->json(['error'=>'No tienes ningun vehiculo asignado'],400);

            }
          }
          else{

            return response()->json(['error'=>'No tienes una ruta asignada para dia de hoy'],400);

          }

      }
      else{

          return response()->json(['error'=>'No hay vehiculos en tu empresa'],400);

      }

  }
  public function process_devolution( Request $request ){

    $sales_response = [];
    $sales = json_decode($request->input('sales'), true);
    $errors = $this->validate_devolution($sales);
    if( count( $errors ) == 0 ){

      foreach ($sales as $sale) {

          $order = (int) $sale['order_id'];
          $current_quantity = Sale::where('order_id',$order)->where('product_id',$sale['product_id'])->sum('quantity');
          $product = Product::find($sale['product_id']);
          $sale = Sale::make($sale);
          $sale->save();
          array_push($sales_response,$sale);

      }

      return response()->json($sales_response,201);

    }
    else{

      return response()->json($errors,400);

    }

  }
  public function synchronizeData( Request $request ){

      $seller_id = $request->user()->id;
      $orders = json_decode($request->get('orders'), true)['orders'];
      $vehicles_ids = Vehicle::where('company_id',$request->user()->company_id)->pluck('id'); //GETTING VEHICLES IDS OF THE COMPANIES
      $route = Route::whereIn('vehicle_id',$vehicles_ids)->where('user_id',$seller_id)->first(); //GETTING MY ROUTE
      $vehicle = Vehicle::find($route->vehicle_id);
      $warehouse = Warehouse::find($vehicle->warehouse_id);
      $orders_response = [];
      $orders_response['orders'] = [];
      $id_warehouse = $warehouse->id;

      foreach ( $orders as $order ) {

          $sales = $order['sales'];
          $cash_payment = $order['cash_payment'];
          $intern_credit = $order['intern_credit'];
          $debit_card = $order['debit_card'];
          $credit_card = $order['credit_card'];
          $transference = $order['transference'];
          $customer = Customer::find($order['customer_id']);

          if( $order['synchronized'] ){ //SI LA ORDEN ESTA SINCRONIZADA

            $order = Order::find( $order['id'] );

            if( !$this->isNull($order) ){

              $devolutions_array = [];
              $sales_array = [];

              $i = 0;
              foreach ($sales as $sale) {

                  $devolutions = $sale['devolutions'];
                  $sale = Sale::find($sale['id']);


                  foreach ($devolutions as $devolution) {

                      if( !$devolution['synchronized'] ){ // SI LA DEVOLUCION NO FUE SINCRONIZADA

                        $d = Sale::make($devolution);
                        $d->save();
                        array_push($devolutions_array,$d);

                      }

                  }

                  $sale['devolutions'] = $devolutions_array;
                  array_push($sales_array,$sale);
                  $i ++;
              }


              $order['sales'] = $sales_array;

            }

          }
          else{ // SI LA ORDEN NO ESTA SINCRONIZADA

              $statement  = DB::select("SHOW TABLE STATUS LIKE 'orders'");
              $nextId     = $statement[0]->Auto_increment;
              $folio      = str_pad($nextId, 5, '0', STR_PAD_LEFT);

              $o = Order::make( ['folio'=>$folio,'user_id'=>$order['user_id'],'customer_id'=>$order['customer_id'],'id_order_offline'=>$order['id_order_offline'],'made_at_offline'=>$order['made_at_offline'],'customer_payment'=>$order['customer_payment'],'customer_change'=>$order['customer_change']] );
              $o->save();

              $devolutions_array = [];
              $sales_array = [];

              foreach ($sales as $sale) {

                  $devolutions = $sale['devolutions'];
                  $s = Sale::make($sale);
                  $s->order_id = $o->id;
                  $s->save();

                  // Se busca el producto enlazado a la venta
                  $product_stock = Stock::where('warehouse_id', $id_warehouse)
                            ->where('product_id', $s['product_id'])
                            ->select('stocks.id','stocks.quantity')
                            ->first();

                  // Se obtiene el id del producto
                  $stock_id = $product_stock['id'];
                  $stock_quantity = $product_stock['quantity']; // Obtenemos el stock disponible del producto
                  $current_stock = $stock_quantity - $s['quantity']; // Descontamos las unidades vendidas con el stock
                  Stock::where('id',$stock_id)->update(['quantity' => $current_stock]); // Actualizamos el stock

                  foreach ($devolutions as $devolution) {

                      if( !$devolution['synchronized'] ){ // SI LA DEVOLUCION NO FUE SINCRONIZADA

                        $devolution['order_id'] = $o->id;
                        $d = Sale::make($devolution);
                        $d->save();

                        $current_stock = Stock::where('id',$stock_id)->where('product_id',$devolution['product_id'])->first();
                        Stock::where('id',$stock_id)->where('product_id',$devolution['product_id'])->update(['quantity' => $current_stock->quantity + ($devolution['quantity'] * -1) ]);

                        array_push($devolutions_array,$d);

                      }

                  }

                  $sale['devolutions'] = $devolutions_array;
                  array_push($sales_array,$sale);

              }

              if( $intern_credit > 0 ){

                 $wallet = Wallet::where('customer_id',$customer->id)->first();
                 if( !$this->isNull($wallet) ){ //Si no existe una wallet para un cliente la crea

                   $wallet->credit = ($wallet->credit + $intern_credit);
                   $wallet->save();
                   $data['wallet'] = $wallet;

                   $c_payment = new Payment();
                   $c_payment->quantity = $intern_credit;
                   $c_payment->order_id = $o->id;
                   $c_payment->method_payment = "INTERN_CREDIT";
                   $c_payment->save();
                   $data['payment']['intern_credit'] = $c_payment;

                 }
                 else{

                     $wallet = new Wallet();
                     $wallet->customer_id = $customer->id;
                     $wallet->company_id = $user->company_id;
                     $wallet->credit = $intern_credit;
                     $wallet->save();
                     $data['wallet'] = $wallet;

                     $c_payment = new Payment();
                     $c_payment->quantity = $intern_credit;
                     $c_payment->order_id = $o->id;
                     $c_payment->method_payment = "INTERN_CREDIT";
                     $c_payment->save();
                     $data['payment']['intern_credit'] = $c_payment;

                 }

              }
              if( $cash_payment > 0 ){

                 $c_payment = new Payment();
                 $c_payment->quantity = $cash_payment;
                 $c_payment->order_id = $o->id;
                 $c_payment->method_payment = "CASH";
                 $c_payment->save();
                 $data['payment']['cash'] = $c_payment;

              }
              if( $debit_card > 0 ){

                $d_payment = new Payment();
                $d_payment->quantity = $debit_card;
                $d_payment->order_id = $o->id;
                $d_payment->method_payment = "DEBIT_CARD";
                $d_payment->save();
                $data['payment']['debit_card'] = $d_payment;

              }
              if( $credit_card > 0 ){

                $c_payment = new Payment();
                $c_payment->quantity = $credit_card;
                $c_payment->order_id = $o->id;
                $c_payment->method_payment = "CREDIT_CARD";
                $c_payment->save();
                $data['payment']['credit_card'] = $c_payment;

              }
              if( $transference > 0 ){

                $t_payment = new Payment();
                $t_payment->quantity = $transference;
                $t_payment->order_id = $o->id;
                $t_payment->method_payment = "TRANSFERENCE";
                $t_payment->save();
                $data['payment']['transference'] = $t_payment;

              }

              $order = $o;
              $order['sales'] = $sales_array;

          }
          if( !$this->isNull($order) ){
              array_push($orders_response['orders'],$order);
          }

        }

        return response()->json($orders_response,201);
}
  private function product_category_exists( $id , $product_categories  ){

    foreach ($product_categories as $product_category ) {

        if( $id == $product_category->id ){
          return true;
        }

    }

    return false;

  }
  private function validate_devolution( $sales ){

    $response = [];
    $response['errors'] = [];
    foreach ($sales as $sale) {

        $order = (int) $sale['order_id'];
        $current_quantity = Sale::where('order_id',$order)->where('product_id',$sale['product_id'])->sum('quantity');
        $product = Product::find($sale['product_id']);
        if( $current_quantity < ($sale['quantity']*-1) ){
          array_push($response['errors'],['message'=>'No se pudo devolver el articulo '.$product['name'],'product_availability'=>$current_quantity]);
        }

    }
    return $response['errors'];
  }


}
