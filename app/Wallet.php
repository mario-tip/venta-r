<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
  use SoftDeletes;
    protected $fillable = [
      'customer_id',
      'company_id',
      'credit'
    ];

    protected $hidden =[
      'deleted_at',
      'updated_at',
      'created_at'
    ];

    public function customer(){
      return $this->belongsTo(Customer::class);
    }

    public function company(){
      return $this->belongsTo(Company::class);
    }

}
