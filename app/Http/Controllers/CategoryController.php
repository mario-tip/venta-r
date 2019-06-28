<?php
namespace App\Http\Controllers;
use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use App\Category;
use App\Product;
use App\User;

class CategoryController extends ApiController
{

    public function index(Request $request)
    {
      $data = $request->user()->company->category;
      $value = count($data) ? $data
      : $this->showError('Aun no hay categorias en la empresa.');
      return $value;
    }

    public function store(Request $request)
    {
      $rules = [
        'name' => 'required',
        'description' => 'required',
      ];
      $this->validate($request, $rules);
      $request['name'] = ucwords(strtolower($request->input('name')));
      $request['company_id'] = $request->user()->company->id;
      $categoria = Category::create($request->all());
      return $this->showSave(['data'=>$categoria]);
    }

    public function show(Category $category)
    {
        return $this->showAllSimple($category);
    }

    public function update(Request $request, Category $category)
    {
      if($request->has('name')){
        $request['name'] = ucwords(strtolower($request->input('name')));
      }
      $category->update($request->all());
      return $this->showSave($category);
    }

    public function destroy(Category $category)
    {
      $category->delete();
      return $this->showAllSimple($category);
    }
}
