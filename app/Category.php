<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\Company;
use App\Product;
use App\User;

class Category extends Model{

  use SoftDeletes;
    protected $fillable = [
      'company_id',
      'name',
      'description',
    ];

    protected $dates = ['deleted_at'];

    protected $hidden =[
      'deleted_at',
      'created_at',
      'updated_at',
    ];

    public function company(){
      return $this->belongsTo(Company::class,'company_id');
    }
    public function product(){
      return $this->hasMany(Product::class);
    }
}
