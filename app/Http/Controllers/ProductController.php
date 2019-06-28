<?php
namespace App\Http\Controllers;
use Intervention\Image\ImageManagerStatic as Image;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Product;
use File;
use DB;

class ProductController extends ApiController
{
    public function index(Request $request)
    {
      $pro = Product::join('categories','products.category_id','=','categories.id')
      ->where('products.company_id',$request->user()->company->id)
      ->select('categories.name AS product_category','products.*')
      ->orderBy('updated_at', 'DESC')
      ->get();
      if(count($pro)){
        return $this->showAllSimple($pro);
      }else{
        return $this->showError('No existen productos');
      }
    }

    public function store(Request $request)
    {

      $rules = [
        'name' => 'required',
        'description' => 'required',
        'bazaarPrice' => 'required|numeric',
        'expoPrice' => 'required|numeric',
        'barCode' => 'required',
        'category_id' => 'required|numeric',
        'img_file' => 'image | mimes:jpeg,jpg,png',
      ];
      $this->validate($request, $rules);

      $list_code = $request->user()->company->products_code;

      foreach ($list_code as $key => $value) {
        if ($value['barCode'] == $request->barCode) {
          return $this->showError('Código ya utilizado,</br>ingrese uno diferente');
        }
      }

      $request['name'] = ucwords(strtolower($request->input('name')));

      $request['company_id'] = $request->user()->company->id;

      $statement = DB::select("SHOW TABLE STATUS LIKE 'products'");
      $nextId = $statement[0]->Auto_increment;

      $file = $request->file('img_file');
      $filename = "";
      $filename = $nextId.".".$file->getClientOriginalExtension();
      $path = 'img/products/' . $filename;

      $folder = public_path().'/img/products';

      if(!file_exists($folder)){
          mkdir($folder, 0777, true);
        }

      Image::make($file->getRealPath())->resize(200, null, function ($constraint) { $constraint->aspectRatio(); })->save($path);

      $img = file_get_contents(public_path($path));
      $base64 = base64_encode($img);
      $request['img'] = $base64;

      $todo = Product::create($request->all());
        return $this->showSave($todo);
    }

    public function show(Product $product)
    {
        return $this->showAllSimple(['data' => $product]);
    }

    public function update(Request $request, Product $product)
    {
      $rules = [
        'price' => 'numeric',
        'category_id' => 'numeric',
      ];
      $this->validate($request, $rules);

      if($request->has('name')){
        $product->name = ucwords(strtolower($request->input('name')));
      }

        if ($request->hasFile('img_file')) {

          $file = $request->file('img_file');
          $filename = "";
          $filename = $product->id.".".$file->getClientOriginalExtension();
          $path = 'img/products/' . $filename;
          Image::make($file->getRealPath())->resize(200, null, function ($constraint) { $constraint->aspectRatio(); })->save($path);
          $product->update($request->all());

          $img = file_get_contents(public_path($path));
          $base64 = base64_encode($img);

          Product::where('id', $product->id)->update(['img' => $base64]);
        }

          $input = $request->except('img_file');
          $product->update($request->all());
          return $this->showSave(['data' => $product]);
    }
    public function destroy(Product $product)
    {
        $product->delete();
        return $this->showAllSimple(['data' => $product]);
    }

    public function getProductsByJson(Request $request)
    {
      if($request->has('products')){

          $products = json_decode($request->input("products"),true);
          $r = [];
          $i = 0;
          foreach ($products as $product ) {

            $product_id = $product['id'];
            $product = Product::where('id',$product_id)->get();
            $r[$i] = $product[0];
            $i++;

          }

          return $this->showAllSimple($r);

      }
      else{
        return $this->showError("peticion no valida");
      }
    }

    public function getProducts(){
      $data = Product::all('id','barcode');
      return $data;
    }

    public function Saveimg(Request $request){

      if ($request->hasFile('img_file')) {

        $file = $request->file('img_file');
        $filename = "";
        $filename = $product->id.".".$file->getClientOriginalExtension();
        $path = 'img/products/' . $filename;
        Image::make($file->getRealPath())->resize(200, null, function ($constraint) { $constraint->aspectRatio(); })->save($path);
        $product->update($request->all());

        $img = file_get_contents(public_path($path));
        $base64 = base64_encode($img);

        Product::where('id', $product->id)->update(['img' => $base64]);
      }

      return $request->all();
    }
}
