<?php
namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Cedis extends Model
{
  use softDeletes;

  protected $fillable = [
    'company_id',
    'name',
    'capacity'
  ];

  protected $dates = ['deleted_at'];
  protected $hidden = ['deleted_at'];

  public function company(){
    return $this->belongsTo(Company::class,'company_id');
  }
}
