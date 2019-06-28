<?php
namespace App\Http\Controllers;
use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use App\Wallet;

class WalletController extends ApiController
{
    public function index(Request $request){
      $wallet = $request->user()->company->wallets;
      $value = $wallet ? $wallet : $this->showError('No hay registro de creditos');
      return $value;
    }

    public function store(Request $request){
      $rules = [
        'customer_id' => 'required|numeric',
        'company_id' => 'required|numeric',
        'credit' => 'required|numeric'
      ];
      $this->validate($request, $rules);
      $request['company_id'] = $request->user()->company->id;
      $data = Wallet::create($request->all());
      return $this->showSave($data);
    }

    public function show(Wallet $wallet){
      $wallet->customer;
      return $wallet;
    }

    public function update(Request $request, Wallet $wallet){
      $rules = [
        'customer_id' => 'numeric',
        'credit' => 'numeric'
      ];
      $this->validate($request, $rules);
      $wallet->update($request->all());
      return $this->showSave($wallet);
    }

    public function destroy(Wallet $wallet){
     $wallet->delete();
     return $wallet;
    }
}
