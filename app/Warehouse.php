<?php
namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
  use SoftDeletes;
  protected $fillable = [
    'name',
    'description',
    'type_warehouse',
    'company_id'
    ];
    protected $dates = ['deleted_at'];
    protected $hidden = ['deleted_at'];

    public function vehicle(){
      return $this->hasOne(Vehicle::class);
    }

    public function stock(){
      return $this->hasMany(Stock::class);
    }

    public function stock_free(){
      return $this->hasMany(Stock::class)->with('product');
    }

    public function stock_product(){
      return $this->hasMany(Stock::class)->orderBy('updated_at','desc'); //->with('product');
    }

}
