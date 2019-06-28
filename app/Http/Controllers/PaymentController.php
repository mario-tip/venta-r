<?php
namespace App\Http\Controllers;
use App\Payment;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class PaymentController extends ApiController
{
    public function index()
    {
      $data = Payment::all();
      return $this->showAllSimple($data);
    }

    public function store(Request $request)
    {
      $rules = [
        'quantity' => 'required|numeric',
        'order_id' => 'required|numeric'
      ];
      $this->validate($request,$rules);

      $data = $request->all();
      $pago= Payment::create($data);
      return $this->showAllSimple(['data'=> $pago],201);
    }

    public function show(Payment $payment)
    {
      return $this->showAllSimple(['data'=> $payment]);
    }

    public function update(Request $request, Payment $payment)
    {
      $rules = [
        'quantity' => 'numeric',
        'order_id' => 'numeric'
      ];
      $this->validate($request,$rules);

      $payment->update($request->all());
      return $this->showAllSimple(['data'=> $payment],201);
    }

    public function destroy(Payment $payment)
    {
      $payment->delete();
      return $this->showAllSimple(['data'=> $payment]);
    }
}
