<?php
namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\Product;
use App\Warehouse;

class Stock extends Model
{
  use SoftDeletes;

    protected $fillable = [
      'warehouse_id',
      'product_id',
      'quantity'
    ];

    protected $dates = ['deleted_at'];

    protected $hidden =[
        'deleted_at',
        'created_at',
        'updated_at',
      ];

  public function product(){
    return $this->belongsTo(Product::class);
  }

  public function warehouse(){
    return $this->belongsTo(Warehouse::class);
  }

  public function namep(){
    return $this->belongsTo(Product::class);
  }

}
