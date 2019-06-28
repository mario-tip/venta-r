<?php
namespace App\Http\Controllers;
use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use App\Warehouse;
use App\Product;
use App\Order;
use App\Stock;
use App\Sale;

class SaleController extends ApiController
{
    public function index(Request $request)
    {
        $sales = Sale::join('products','sales.product_id','=','products.id')
                  ->select('sales.id AS sale_id',
                  'sales.quantity','sales.total',
                  'sales.order_id','sales.created_at',
                  'products.name AS product_name')
                  ->get();

        if(count($sales)){
          return $this->showAllSimple($sales);
        }
        else{
            return $this->showError("No hay ventas en la empresa");
        }
    }

    public function store(Request $request)
    {
      $rules = [
        'quantity' => 'required|numeric',
        'total' => 'required|numeric',
        'order_id' => 'required|numeric',
        'product_id' => 'required|numeric',
      ];
      $this->validate($request,$rules);

      if ($request->has('productos')) {
        $result = [];
        $productos = json_decode($request->input('productos'),true);

        foreach ($productos as $producto) {

          $order_id = $producto['order_id'];
          $product_id = $producto['product_id'];
          $quantity = $producto['quantity'];
          $total = $producto['total'];
          $refound_observation = $producto['refound_observation'];

          $sale = [
            'order_id'=> $order_id,
            'product_id'=> $product_id,
            'quantity' => $quantity,
            'total' => $total,
            'refound_observation' => $refound_observation
          ];

          $sale = Sale::make($sale);
          $sale->save();
          array_push($result,$sale);

        }
        return $this->showAllSimple($result,201);
      }else {
        return $this->showAllSimple('Se requieren parametros',400);
       }
    }

    public function show(Sale $sale)
    {
        return $this->showAllSimple($sale);
    }

    public function update(Request $request, Sale $sale)
    {
      $rules = [
        'quantity' => 'numeric', 'total' => 'numeric',
        'order_id' => 'numeric', 'product_id' => 'numeric',
      ];
      $this->validate($request,$rules);

      $sale->update($request->all());
      return $this->showSave($sale);
    }

    public function destroy(Sale $sale)
    {
        $sale->delete();
        return $this->showAllSimple($sale);
    }

    public function refound(Request $request){
      if ($request->has('orders')) {

          $orders   = $request->input('orders');
          $orders = json_decode($orders,true);

          for ($i = 0; $i < count($orders); $i++) {

              $producto    = $orders[$i]['products'];

              for ($j = 0; $j < count($producto); $j++) {
                  $_cantidad  = $producto[$j]['quantity'];
                  $_total     =  $producto[$j]['price'] * $_cantidad;
                  $_refoud  = $producto[$j]['refound_observation'];
                  $_idproduct = $producto[$j]['id'];

                  $data       = [
                      'quantity'            => $_cantidad,
                      'total'               => $_total,
                      'refound_observation' => $_refoud,
                      'order_id'            => $orders[$i]['id'],
                      'product_id'          => $_idproduct,
                  ];

                  $orders[$i]['user_id'];
                  $sale = Sale::make($data);
                  $sale->save($data);

                  $get_id_ware = Warehouse::where('user_id',$orders[$i]['user_id'])
                                          ->select('warehouses.id')
                                          ->first();//nos trae solo el campo de importancia
                $id_ware =  $get_id_ware['id'];

                $busca = Stock::where('warehouse_id', $id_ware)
                          ->where('product_id', $_idproduct)
                          ->select('stocks.id','stocks.quantity')
                          ->first();

                $stock_cant = $busca['quantity'];
                $_resta = $stock_cant - $_cantidad;

                if ($_resta < 0) {
                  return $this->showError('se acabo este producto del almacen '.$id_ware);
                }else
                  Stock::where('id',$busca['id'])
                              ->update(['quantity' => $_resta]);
              }
          }

          return $this->showSave($orders);
      }
    }

    public function test(Request $request)
    {
      $result = [];
      $var = json_decode($request->all()['productos'],true);
      for ($i=0; $i < count($var); $i++) {
        $idOrder = $var[$i]['order_id'];
        $idProduct = $var[$i]['product_id'];
        $quantity = $var[$i]['quantity'];
        $total = $var[$i]['total'];

        $sale = [
          'order_id'=> $idOrder,
          'product_id'=> $idProduct,
          'quantity' => $quantity,
          'total' => $total,
          'refound_observation' => null
        ];
        $sale = Sale::make($sale);
        $sale->save();
        $result[$i] = $sale;
      }
      return $this->showAllSimple($result);
    }
    public function getRefound(Request $request)
    {

      // $request->user()->company;
      // FIXME: retornar solo los de la empresa
      $sales = Sale::join('products','sales.product_id','=','products.id')
                ->join('orders', 'sales.order_id', '=', 'orders.id')
                ->where('total','<',0)
                ->select('sales.id AS sale_id',
                'sales.quantity',
                'sales.total',
                'sales.refound_observation',
                'products.name AS product_name',
                'orders.folio')
                ->get();
      return $this->showAllSimple($sales);
    }
}
