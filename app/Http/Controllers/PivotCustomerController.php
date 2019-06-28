<?php
namespace App\Http\Controllers;
use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use App\PivotCustomer;
use App\Vehicle;
use App\Route;

class PivotCustomerController extends ApiController
{
    public function index(Request $request){
      $car_ids = Vehicle
      ::where('company_id',$request->user()->company->id)
      ->pluck('id');// IDEA: regresa [1,2,3]
      $rutas = Route::whereIn('vehicle_id', $car_ids)->with('clients')->get();
      return count($rutas) ? $rutas : $this->showError('No hay rutas');
    }

    public function store(Request $request){
      $rules = [
        'route_id' => 'required|numeric',
        'customer_id' => 'required|numeric'
      ];
      $this->validate($request, $rules);
      return $this->showSave(PivotCustomer::create($request->all()));
    }

    public function show($id){
      $data = PivotCustomer::findOrFail($id);
      $data->route;
      $data->customer;
      return $data;
    }

    public function update(Request $request, $id){
      $rules = [
        'route_id' => 'numeric',
        'customer' => 'numeric'
      ];
      $this->validate($request, $rules);
      $data = PivotCustomer::findOrFail($id);
      $data->update($request->all());
      return $this->showSave($data);
    }

    public function destroy($id){
      $data = PivotCustomer::findOrFail($id);
      $data->delete();
      return $data;
    }
}
