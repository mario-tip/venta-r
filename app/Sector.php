<?php
namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\Customer;

class Sector extends Model
{
  use SoftDeletes;

  protected $fillable = [
    'name',
    'description',
    'company_id'
  ];

  protected $dates = ['deleted_at'];

  protected $hidden =[
      'deleted_at',
      'created_at',
      'updated_at',
    ];

  public function customer(){
    return $this->belongsToMany(Customer::class);
  }
}
