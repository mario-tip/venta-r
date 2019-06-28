<?php
namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
  use HasApiTokens, Notifiable;
  use SoftDeletes;
  protected $dates = ['deleted_at'];

    protected $fillable  = [
      'name',
      'last_name',
      'img',
      'phone',
      'email',
      'address',
      'password',
      'user_type',
      'company_id',
    ];
    protected $hidden =[
      'password',
      'deleted_at',
      'updated_at',
      'created_at',
    ];

    public function orders(){
      return $this->hasMany(Order::class)->with('sales');
    }
    public function company(){
      return $this->belongsTo(Company::class);
      //IDEA  campo en DB del modelo y que esta en un campo en la tabla usuarios ,
    }
    public function route(){
      return $this->hasone(Route::class);
    }
    // IDEA: get all data of routes
    public function routes(){
      return $this->belongsToMany(Vehicle::class,'routes');
    }


    // IDEA: .......::::::ralaciones y select emma:::::::::::::..............

    public function emmas(){
      return $this->belongsTo(Company::class);

      // ->select(
      //   'email_billing AS billingEmail',
      //   'social_reason AS socialReason',
      //   'page_web AS webPage',
      //   'cedis_id AS cedisId'
      //   'companies.*'
      // );
    }
}
