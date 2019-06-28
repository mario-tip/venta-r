<?php
namespace App\Http\Controllers;
use App\Http\Controllers\ApiController;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use App\Exports\SalesExport;
use App\Warehouse;
use Carbon\Carbon;
use App\Stock;
use App\Order;
use App\Sale;
use App\User;
use Excel;

class OrderController extends ApiController
{
    public function index(Request $request, Order $order)
    {
      $ids = $request->user()->company->id;
      $order =  Order::where('company_id',$ids)->with('user','customer','sales_f')
      // ->where( 'created_at', '>', Carbon::now()->subDays(30))
      ->get();

      if (count($order)) {
        foreach ($order as $key => $value1) {
          $tot = 0;
          foreach ($value1['sales_f'] as $key2 => $value2) {
            $tot = $tot + $value2['total'];
            $order[$key]['total'] = $tot;
          }
        }

        foreach ($order as $key => $value1) {
            $order[$key]['name_user'] = $value1['user']['name'];
            $order[$key]['name_customer'] = $value1['customer']['social_reason'];
        }
        return $order;
      }

        return $this->showError('No se encontraron ventas.');
    }


    public function store(Request $request){
      $rules = [
        'user_id' => 'required|numeric',
        'customer_id' => 'required|numeric',
        'order_id_offline' => 'numeric',
        'made_at_offline' => 'date',
        'customer_payment' => 'double',
        'customer_change' => 'double',
        'discount_id' => 'numeric'
      ];
      $this->validate($request, $rules);

        if ($request->has('orders')) {

            $orders   = $request->input('orders');
            $orders = str_replace("\\","",$orders);
            $orders = json_decode($orders,true);


            for ($i = 0; $i < count($orders); $i++) {

                $_iduser     = $request->user()->id;
                $_idcustomer = $orders[$i]['customer_id'];
                $_paid       = $orders[$i]['paid'];
                $_idorderoff = $orders[$i]['id_order_offline'];
                $_madeatoff  = $orders[$i]['made_at_offline'];
                $_custompay  = $orders[$i]['customer_payment'];
                $_customchan = $orders[$i]['customer_change'];
                $producto    = $orders[$i]['products'];

                $statement   = DB::select("SHOW TABLE STATUS LIKE 'orders'");
                $order       = count($request->user()->company->orders)+1;
                $_folio      = str_pad($order, 5, '0', STR_PAD_LEFT);

                $orders[$i]['folio']= $_folio ;

                $datos       = [

                    // 'folio'            => $_folio,
                    'user_id'          => $_iduser,
                    'customer_id'      => $_idcustomer,
                    'paid'             => $_paid,
                    'id_order_offline' => $_idorderoff,
                    'made_at_offline'  => $_madeatoff,
                    'customer_payment' => $_custompay,
                    'customer_change'  => $_customchan,
                    'company'          => $request->user()->company->id
                ];

                $order = Order::make($datos);
                $order->save();

                $orders[$i]['id'] = $order['id'];
                $orders[$i]['created_at'] = json_decode(json_encode($order['created_at']),true)['date'];

                for ($j = 0; $j < count($producto); $j++) {
                    $_cantidad  = $producto[$j]['quantity'];
                    $_total     =  $producto[$j]['price'] * $_cantidad;
                    if (isset($producto[$j]['refound_observation'])) {//isset -> si la variable existe
                      $_refoud  = $producto[$j]['refound_observation'];

                    }else {
                      $_refoud = "";
                    }
                    $_idproduct = $producto[$j]['id'];

                    $data       = [
                        'quantity'            => $_cantidad,
                        'total'               => $_total,
                        'refound_observation' => $_refoud,
                        'order_id'            => $order['id'],
                        'product_id'          => $_idproduct,
                    ];

                    $get_id_ware = Warehouse::where('user_id',$_iduser)
                                            ->select('warehouses.id')
                                            ->first();//nos trae solo el campo de importancia
                  $id_ware =  $get_id_ware['id'];

                  $busca = Stock::where('id_warehouse', $id_ware)
                            ->where('product_id', $_idproduct)
                            ->select('stocks.id','stocks.quantity')
                            ->first();

                  $stock_cant = $busca['quantity'];
                  $_resta = $stock_cant - $_cantidad;

                  if ($_resta <= 0) {
                    return $this->showError('no hay producto en almacen '.$id_ware);
                  }else
                    Stock::where('id',$busca['id'])
                                ->update(['quantity' => $_resta]);

                    $sale = Sale::make($data);
                    $sale->save();
                }
            }

            return $this->showSave($orders);
        }

    }

    public function show(Order $order){
      $order->sales;
        return $order;
    }
    public function update(Request $request, Order $order)
    {
      $rules = [
        'user_id' => 'numeric',
        'customer_id' => 'numeric',
        'id_order_offline' => 'numeric',
        'made_at_offline' => 'date',
        'customer_payment' => 'double',
        'customer_change' => 'double',
        'discount_id' => 'numeric'
      ];
      $this->validate($request, $rules);

        $order->update($request->all());
        return $this->showSave($order);
    }

    public function destroy(Order $order)
    {
        $order->delete();
        return $order;
    }

    public function filter_date(Request $request){
      $ids = $request->user()->company->drivers->pluck('id');
      $order =  Order::whereIn('user_id',$ids)->with('user','customer','sales');

        if ($request->has('from_date','to_date') ) {
          $start = Carbon::parse($request->from_date)->startOfDay();
          $end = Carbon::parse($request->to_date)->endOfDay();
            $order->whereBetween('created_at', [$start, $end] )->orderBy('updated_at','desc');
        }
          return $order->get();
    }
    public function export(Request $request){
      if ($request->has('from_date','to_date') ) {
        // $start =  Carbon::parse($request->from_date)->startOfDay();
        // $end = Carbon::parse($request->to_date)->endOfDay();
      }
      return (new SalesExport)->download('Ventas.xlsx');
    }

    public function generateOrder(Request $request){



    }
}
