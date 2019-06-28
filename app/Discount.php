<?php
namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
  use SoftDeletes;
  protected $fillable = [
    'porcent',
    'description',
    'company_id'
  ];
  protected $dates = ['deleted_at'];

  public function company()
  {
    return $this->belongsTo(Company::class);
  }
}
