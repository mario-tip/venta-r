<?php
namespace App\Http\Controllers;
use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use App\Customer;
use App\Company;
use App\Wallet;
use App\User;
use DB;

class CustomerController extends ApiController
{
    public function index(Request $request){
      $data = $request->user()->company->customers;
      return count($data)? $data : $this->showError('No existen clientes');
    }

    public function store(Request $request){
      $rules = [
        'social_reason' => 'required',
        'email' => 'email',
        'cp' => 'required|numeric',
        'external_number' => 'required',
        'sector_id' => 'required|numeric'
      ];
      $this->validate($request, $rules);
      $request['company_id'] = $request->user()->company->id;
      $statement   = DB::select("SHOW TABLE STATUS LIKE 'orders'");
                $nextId      = $statement[0]->Auto_increment;
                $_folio      = str_pad($nextId, 5, '0', STR_PAD_LEFT);

      
      $request['code'] = $_folio;

      $campos = $request->all();
      $clientes = Customer::create($campos);
      $datacredit['company_id'] = $request->user()->company->id;
      $datacredit['credit'] = 0;
      $datacredit['customer_id'] = $clientes->id;

      $wallet = Wallet::make($datacredit);
      $wallet->save();
      //idea parseo para el elmmma¿¿¿
      $clientes['socialReason'] = $clientes->social_reason;
      $clientes['sectorId'] = $clientes->sector_id;
      return $this->showSave($clientes);
    }

    public function show(Customer $customer){
       return $customer;
    }

    public function update(Customer $customer, Request $request){
      $rules = [
        'email' => 'email',
        'company_id' => 'numeric',
        'sector_id' => 'numeric',
        'latitude' => 'numeric',
        'longitude' => 'numeric'
      ];
      $this->validate($request, $rules);
	      $customer->update($request->all());
	      return $this->showSave($customer);
    }

    public function destroy(Customer $customer){
        $customer->delete();
        return $customer;
    }

    public function getClientes(Request $request)
    {
      $data = $request->user()->company->clientes;
      return count($data)? $data : $this->showError('No existen clientes');
    }

    public function getCustomer(Request $request){
      if ($request->has('code')) {
        $dato = $request->all()['code'];
        $cliente = Customer::where('code',$dato)->first();
        if (count ($cliente)) {
          return $this->showAllSimple($cliente);
        }else{
          return $this->showAllSimple(['error' => "Cliente no encontrado"]);
        }

      }else {
        }
        return $this->showAllSimple(['error'=>'Se require codigo para encontrar cliente'],400);

    }


    public function getCustomerByJSON(Request $request){

      //Agregado por Emmanuel Martinez Rodriguez
      if($request->has('customers')){

          $customers = json_decode($request->input("customers"),true);
          $r = [];
          $i = 0;
          foreach ($customers as $customer ) {

            $customer_id = $customer['id'];
            $customer = Customer::where('id',$customer_id)->get();
            $r[$i] = $customer[0];
            $i++;

          }

          return $this->showAllSimple($r);

      }
      else{
        return $this->showAllSimple("Peticion no valida");
      }
    }

    public function getCustomersByCompanyID(Request $request){
      $idCompany = $request->input('company_id');
      $company = Company::find($idCompany);
      if (count($company)) {
        $customer = Customer::where('company_id',$idCompany)->get();
        if (count($customer)) {
          return $this->showAllSimple($customer);
        }else {
        return $this->showAllSimple('no existen clientes para esta empresa');
    }

      }
      else {
        return $this->showAllSimple('error empresa inexistente');
      }
    }

}
