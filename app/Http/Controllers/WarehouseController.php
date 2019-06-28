<?php
namespace App\Http\Controllers;
use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use App\Warehouse;

class WarehouseController extends ApiController
{
    public function index(Request $request)
    {
      $ware =  $request->user()->company->warehouses;
      $value = $ware ? $ware : $this->showError('No hay almacenes en la empresa.');
      return $value;
    }

    public function store(Request $request)
    {
      $rules = [
        'name' => 'required',
        'description' => 'required',
        'type_warehouse' => 'required|numeric'
      ];
      $this->validate($request, $rules);
      $request['company_id'] = $request->user()->company->id;
      $data = Warehouse::create($request->all());
      return $this->showSave($data);
    }

    public function show(Warehouse $warehouse)
    {
      $data = $warehouse->stock;
        return $warehouse;
    }

    public function update(Warehouse $warehouse, Request $request)
    {
      $rules = [
        'company_id'=> 'numeric',
        'type_warehouse' => 'numeric'
      ];
      $this->validate($request, $rules);
      $warehouse->update($request->all());
      return $this->showSave($warehouse);
    }

    public function destroy(Warehouse $warehouse)
    {
      $warehouse->delete();
      return $this->showAllSimple($warehouse);
    }

    public function WarehousesinRoute(Request $request)
    {
      $ware =  $request->user()->company->wareRoute;
      $value = $ware ? $ware : $this->showError('No hay almacenes en la empresa.');
      return $value;
    }
}
