<?php
namespace App\Http\Controllers;
use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use App\Vehicle;
use App\Route;

class RouteController extends ApiController{

    public function index(Request $request){

      $car_ids = Vehicle
      ::where('company_id',$request->user()->company->id)
      ->pluck('id');// IDEA: regresa [1,2,3]
      $rutas = Route::whereIn('vehicle_id', $car_ids)->with('user','vehicle')->orderBy('updated_at','desc')->get();
      return count($rutas) ? $rutas : $this->showError('No hay rutas');
    }

    public function store(Request $request){
      $rules = [
        'vehicle_id' => 'required|numeric',
        'user_id' => 'required|numeric',
        'name' => 'required',
        // 'day' => 'required|numeric'
      ];
      $this->validate($request, $rules);
      $response = Route::create($request->all());
      return $this->showSave($response);
    }

    public function show(Route $route){
      return $route;
    }

    public function update(Request $request, Route $route){
      $rules = ['vehicle_id' => 'numeric',
      'user_id' => 'numeric'
      ];
      $this->validate($request, $rules);
      $route->update($request->all());
      return $this->showSave($route);
    }

    public function destroy(Route $route){
      $route->delete();
      return $route;
    }

    public function getClients($id){
      $data = Route::findOrFail($id);
      $data->clients;
      return $data;
    }

    public function GetProducts(Request $request){
      // IDEA: solo los choferes que tengan asignados una ruta
      // podran ver sus rutas  $user = $request->user()->route;
      $products = $request->user()->route->vehicle->warehouse->stock;
      // return $request->user()->route;
      return  $products ? $products : $this->showError('No hay prouctos');
    }

}
