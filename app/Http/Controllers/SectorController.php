<?php
namespace App\Http\Controllers;
use App\Sector;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class SectorController extends ApiController
{
    public function index(Request $request)
    {
        $_sector = Sector::where('company_id', $request->user()->company->id)->get();
        if (count($_sector)) {
          return $this->showAllSimple($_sector);
      }
      return $this->showError('no exiten sectores en la compania');

    }

    public function store(Request $request)
    {
      $request['company_id'] = $request->user()->company->id;
      $rules = [
        'name' => 'required',
        'description' => 'required',
        'company_id'=> 'required|numeric'
      ];
      $this->validate($request, $rules);
      $data = $request->all();
      $data =  Sector::create($data);
      return $this->showAllSimple($data);
    }

    public function show($id, Sector $sector)
    {
      $sector = Sector::findOrFail($id);
      return $this->showAllSimple($sector);
    }

    public function update($id, Request $request, Sector $sector)
    {
      $sector = Sector::findOrFail($id);
      $sector->update($request->all());
      // example $sector password = $request->get('model');
      return $this->showAllSimple($sector,201);
    }

    public function destroy($id, Sector $sector)
    {
      $sector = Sector::findOrFail($id);
      $sector->delete();
      return $this->showAllSimple($sector);
    }
}
