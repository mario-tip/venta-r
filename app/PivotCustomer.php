<?php
namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class PivotCustomer extends Model
{
  use SoftDeletes;
  protected $fillable = [
    'route_id',
    'customer_id'
    ];
    protected $dates = ['deleted_at'];
    protected $hidden =[
      'deleted_at',
      'updated_at'
    ];

    public function route(){
      return $this->belongsTo(Route::class);
    }

    public function customer(){
      return $this->belongsTo(Customer::class)->select('id','name','last_name');
    }
}
