<?php
namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;


class Vehicle extends Model
{
  use SoftDeletes;
  protected $fillable = [
    'warehouse_id',
    'company_id',
    'name',
    'description',
    'capacity',
    'type',
    'brand',
    'model'
  ];
  protected $dates=['deleted_at'];
  protected $hidden = ['deleted_at'];


  public function warehouse(){
    return $this->belongsTo(Warehouse::class)->with('stock');
  }

  public function company(){
    return $this->belongsTo(Company::class);
  }

  public function routes(){
    return $this->hasMany(Route::class);
  }

}
