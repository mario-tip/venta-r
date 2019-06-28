<?php
namespace App\Http\Controllers;
use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use App\Discount;

class DiscountController extends ApiController

{

    public function index(Request $request){
      $desc = $request->user()->company->discounts;
      $value = count($desc) ? $desc : $this->showError('No hay registro de descuentos');
      return $value;
    }

    public function store(Request $request){
      $rules = [
        '',
      ];
    }

    public function show(Discount $discount){
      return $discount;
    }

    public function update(Request $request, Discount $discount){
        //
    }

    public function destroy(Discount $discount){
        //
    }
}
