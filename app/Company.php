<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
  use SoftDeletes;
  protected $dates = ['deleted_at'];
  protected $fillable = [
    'c_name',
    'description',
    'address',
    'phone',
    'rfc',
    'email_billing',
    'page_web',
    'cedis_id'
  ];
  protected $hidden =[
    'pivot',
    'deleted_at',
    'created_at',
    'updated_at'
  ];
  public function products_code(){
    return $this->hasMany(Product::class)->orderBy('updated_at','desc')->select('barCode');
  }

  public function products(){
    return $this->hasMany(Product::class)->orderBy('updated_at','desc');
  }

  public function customers(){
    return $this->hasMany(Customer::class)->orderBy('updated_at','desc')->with('sector');
  }

  public function clientes(){
    return $this->hasMany(Customer::class)->orderBy('name','asc')->select('id','name');
  }

  public  function users(){
    return $this->hasMany(User::class)->orderBy('updated_at','desc');
  }

  public function drivers(){
    return $this->hasMany(User::class)->where('user_type','DRIVER')->select('id','name','last_name');
  }

  public function category(){
    return $this->hasMany(Category::class)->orderBy('updated_at','desc');
  }

  public function warehouses(){
    return $this->hasMany(Warehouse::class)->orderBy('updated_at','desc');//->with('stock');
  }

  public function sale(){
    return $this->belongsToMany(Sale::class);
  }

  public function vehicles(){
    return $this->hasMany(Vehicle::class)->orderBy('updated_at','desc')->with('warehouse');
  }

  public function vehicle_piv_route(){
    return $this->belongsToMany(PivotCustomer::class,'routes');
  }

  public function wallets(){
    return $this->hasMany(Wallet::class)->where('credit','>',0)->orderBy('updated_at','desc')->with('customer');
  }

  public function wareRoute(){
    return $this->hasMany(Warehouse::class)->where('type_warehouse',1)->select('id','name');
  }

  public function discounts()
  {
    return $this->hasMany(Discount::class);
  }

  public function orders(){
    return $this->hasMany(Order::class)->with('sale');
  }

  public function sectors(){
    return $this->hasMany(Sector::class);
  }

  public function custom(){
    return $this->hasMany(Customer::class)->orderBy('updated_at','socialReason');
  }

  // IDEA: todas las peticiones de emma CamelCase relaciones y parseos

  public function customers_emma(){
    return $this->hasMany(Customer::class)
    ->select(
      'id',
      'social_reason AS socialReason',
      'phone',
      'email',
      'street',
      'colony',
      'city',
      'state',
      'cp',
      'code',
      'sector_id AS sectorId',
      'external_number AS externalNumber',
      'internal_number AS internalNumber',
      'created_at AS createdAt',
      'company_id AS companyId'
    )->orderBy('social_reason','asc');
  }

  public function stock_emma(){
    return $this->hasmany(Stock::class)->with('products_emma');
  }

  public function products_emma(){
    return $this->hasMany(Product::class)
    ->orderBy('name', 'asc')
    ->select(
      'id',
      'name',
      'description',
      'img',
      'bazaarPrice',
      'expoPrice',
      'barCode',
      'category_id AS categoryId'
    )
    ->with('stocktaking');
    ;
  }

  public function warehouse_emma(){
    return $this->hasmany(Warehouse::class)
    ->select('id','name');
  }

  public  function users_emma(){
    return $this->hasMany(User::class)
    ->select(
      'users.*',
      'last_name AS lastName',
      'user_type AS userType',
      'company_id AS companyId'
    );
  }

  public function orders_emma(){
    return $this->hasMany(Order::class)->select(
      'id',
      'folio',
      'user_id AS userId',
      'customer_id AS customerId',
      'created_at AS createdAt'
    )->with('sales_emma');
  }




}
