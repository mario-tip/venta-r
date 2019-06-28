<?php
namespace App;
use Illuminate\Database\Eloquent\Builder;
use App\Filters\OrderFilter;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
  use SoftDeletes;
  protected $dates = ['deleted_at'];
  protected $fillable = [
    'folio',
    'user_id',
    'customer_id',
    'id_order_offline',
    'made_at_offline',
    'customer_payment',
    'customer_change',
    'discount_id',
    'company_id',
    'orderType',
    'order_number'
  ];
  protected $hidden =[
    'deleted_at',
    'updated_at'
  ];
  public function delivery(){
    return $this->belongsTo(Delivery::class);
  }
  public function sales_f(){
    return $this->hasMany(Sale::class)->with('product');
  }
  public function payment(){
    return $this->belongsTo(Payment::class);
  }
  public function user(){//sin imagen
    return $this->belongsTo(User::class)->select('id','name','last_name');
  }
  public function customer(){
    return $this->belongsTo(Customer::class)->select('id','social_reason','code','email','colony','phone');
  }

  public function sale(){
    return $this->hasMany(Sale::class);
  }

  public function scopeFilter(Builder $builder, $request)
    {
        return (new OrderFilter($request))->filter($builder);
    }

    public function sales(){
      return $this->hasMany(Sale::class)->select('id','order_id','quantity','total','product_id AS productId','created_at AS createdAt', 'type_finish AS typeFinish');
    }

    public function salecat()
    {
        return $this->hasMany(Sale::class)->with('productcat');
    }
}
