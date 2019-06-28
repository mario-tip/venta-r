<?php
namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\Order;

class Delivery extends Model
{
  use SoftDeletes;
  protected $dates = ['deleted_at'];
    protected $fillable = [
      'id_order',
      'description',
    ];
    protected $hidden =[
      'deleted_at',
      'created_at',
      'updated_at'
    ];
    public function order(){
      return $this->belongsTo(Order::class);
    }
}
