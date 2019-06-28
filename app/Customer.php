<?php
namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\Warehouse;
use App\Company;
use App\Balance;
use App\Sector;
use App\Order;


class Customer extends Model
{
  use SoftDeletes;
  protected $dates = ['deleted_at'];
  protected $fillable = [
    'code',
    'social_reason',
    'phone',
    'email',
    'street',
    'colony',
    'city',
    'state',
    'cp',
    'rfc',
    'external_number',
    'internal_number',
    'warehouse_id',
    'sector_id',
    'company_id',
    'order_number'
    ];
    protected $hidden =[
      'company_id',
      'deleted_at',
      'updated_at',
      'created_at'
    ];

    public function sector(){
      return $this->belongsTo(Sector::class);
    }
    public function warehouse(){
      return $this->belongsTo(Warehouse::class);
    }
    public function balance(){
      return $this->belongsTo(Balance::class);
    }
    public function company(){
      return $this->belongsTo(Company::class);
    }
    public function order(){
      return $this->belongsToMany(Order::class);
    }
}
