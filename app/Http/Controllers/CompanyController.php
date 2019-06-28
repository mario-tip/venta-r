<?php

namespace App\Http\Controllers;

use App\Company;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class CompanyController extends ApiController
{
    public function index(Request $request){
      $data = Company::all();
      return $data;
    }

    public function store(Request $request){
      $rules = [
        'name' => 'required',
        'description'=>'required',
        'address'=>'required',
        'phone'=>'required',
        'rfc'=>'required',
        'email_billing'=>'required|email',
        'page_web'=>'required',
        'id_cedis'=> 'required|numeric'
      ];
      $this->validate($request, $rules);
      // IDEA: vamos a crear una cedis por empresa
      // se requiere campos en el front
      $data_c = $request->all();
      $company = Company::create($data_c);
      return $this->showSave( $company);
    }

    public function show(Company $company){
      return $company;
    }

    public function update(Company $company, Request $request){
      $rules = ['email_billing'=>'email'];
      $this->validate($request, $rules);
      $company->update($request->all());
      return $this->showSave($company);
    }


    public function destroy(Company $company){
      $company->delete();
      return $company;
    }
}
