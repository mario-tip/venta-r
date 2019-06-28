<?php
namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
  use SoftDeletes;

  protected $fillable = [
    'vehicle_id',
    'user_id',
    'name',
    // 'day'
  ];

  protected $dates = ['deleted_at'];
  protected $hidden = ['deleted_at'];

  public function vehicle(){
    return $this->belongsTo(Vehicle::class)->select('id','name');
  }
  public function user(){
    return $this->belongsTo(User::class)->select('id','name','last_name');

  }
  public function clients(){
    return $this->hasMany(PivotCustomer::class)->with('customer')->orderBy('updated_at','desc');
  }
}
