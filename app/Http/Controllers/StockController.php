<?php
namespace App\Http\Controllers;
use App\Warehouse;
use App\Stock;
use App\Product;

use Illuminate\Http\Request;

class StockController extends ApiController
{
    public function index(Request $request)
    {
      if ($request->has('warehouse_id')) {
        $ware = Warehouse::findOrFail($request->warehouse_id);

          $sto = Stock::wherewarehouseId($ware->id)->get();

        foreach ($sto as $key => $value) {
          $name = Product::findOrFail($value->product_id);
          $sto[$key]['name'] = $name->name;
        }
        $ware['stock'] = $sto;
        return $ware;
      }
        return $this->showError('se requiere id de almacen');
    }

    public function store(Request $request)
    {
      $rules = [
        'warehouse_id' => 'required|numeric',
        'product_id' => 'required|numeric',
        'quantity'=> 'required|numeric',
      ];
      $this->validate($request, $rules);

      $data = Stock::where('warehouse_id',$request->warehouse_id)
      ->where('product_id', $request->product_id)->get();

      if(count($data)){
          return $this->showMessage(['error' => 'Ya existe el producto en el almacen'],401);
      }

      $datos = $request->all();
      $result = Stock::create($datos);
      return $this->showSave($result);
    }

    public function show(Stock $stock)
    {
      return $stock;
    }

    public function update(Request $request, Stock $stock)
    {
      $rules = [
        'warehouse_id' => 'numeric',
        'product_id' => 'numeric',
        'quantity'=> 'numeric',
      ];
      $this->validate($request, $rules);

      $stock->update($request->all());
      return $this->showSave($stock);
    }

    public function destroy(Stock $stock)
    {
      $stock->delete();
      return $this->showAllSimple($stock);
    }
}
