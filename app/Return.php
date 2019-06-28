<?php
// BUG: no sirve pa nada este modelo
namespace App;

use Illuminate\Database\Eloquent\Model;

class Return extends Model
{
    protected $fillable = [
      'description',
      'user_id',
      'customer_id',
    ];
    protected $hidden =[
      'deleted_at',
      'updated_at'
    ];
}
