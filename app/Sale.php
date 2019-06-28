<?php
namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
  use SoftDeletes;
  protected $dates = ['deleted_at'];
    protected $fillable=[
      'quantity',
      'total',
      'type_finish',
      'order_id',
      'product_id',
      'discount'
    ];
    protected $hidden =[
        'deleted_at',
        'updated_at',
    ];

    public function order(){
      return $this->belongsTo(Order::class);
    }
    public function product(){
      return $this->belongsTo(Product::class,'product_id')->select('id','name','bazaarPrice','expoPrice','description','barCode');
    }

    public function productcat()
    {
        return $this->belongsTo(Product::class,'product_id')->with('category');
    }
}
