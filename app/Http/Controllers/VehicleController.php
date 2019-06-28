<?php
namespace App\Http\Controllers;
use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use App\Vehicle;
use App\User;

class VehicleController extends ApiController {

    public function index(Request $request){
      $data = $request->user()->company->vehicles;

      return count($data) ? $data : $this->showError('No existen vehiculos');
    }

    public function store(Request $request){
      $rules = [
        'warehouse_id' => 'required|numeric',
        'name' => 'required',
        'description' => 'required',
        'capacity' => 'required',
        'brand' => 'required',
        'model' => 'required'
      ];
      $request['company_id'] = $request->user()->company->id;
      $this->validate($request, $rules);
      $data = Vehicle::create($request->all());
      return $this->showSave($data);
    }

    public function show(Vehicle $vehicle){
      return $vehicle;
    }

    public function update(Request $request, Vehicle $vehicle){
      $rules = [
        'company_id'=> 'numeric',
        'warehouse_id' => 'numeric'
      ];
      $this->validate($request, $rules);
      $vehicle->update($request->all());
      return $this->showSave($vehicle);
    }

    public function destroy(Vehicle $vehicle){
      $vehicle->delete();
      return $this->showAllSimple($vehicle);
    }

}
