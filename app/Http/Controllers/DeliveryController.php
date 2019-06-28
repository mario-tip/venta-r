<?php

namespace App\Http\Controllers;

use App\Delivery;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class DeliveryController extends ApiController
{
    public function index(Request $request)
    {
      $data = Delivery::all();
        return $this->showAllSimple($data);
    }

    public function store(Request $request)
    {
      $rules = [
        'order_id' => 'required|numeric',
        'description' => 'required'
      ];
      $this->validate($request, $rules);

      $data = $request->all();
      $entrega = Delivery::create($data);
      return $this->showAllSimple($entrega, 201);
    }

    public function show(Delivery $delivery)
    {
        return $this->showAllSimple($delivery);
    }

    public function update(Delivery $delivery, Request $request)
    {
      $rule = ['order_id' => 'numeric'];
      $this->validate($request, $rule);

      $delivery->update($request->all());
      return $this->showAllSimple( $delivery, 201);
    }

    public function destroy(Delivery $delivery)
    {
      $delivery->delete();
      return $this->showAllSimple($delivery);
    }
}
