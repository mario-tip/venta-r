<?php
namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\Category;
use App\Stock;
use App\Warehouse;
use App\Company;
use App\Sale;

class Product extends Model
{
  use SoftDeletes;
  protected $dates = ['deleted_at'];
  const PRUDUCTO_DISPONIBLE = 'disponible';
  const PRODUCTO_NO_DISPONIBLE = 'no disponible';

  protected $fillable = [
    'name',
    'description',
    'img',
    'bazaarPrice',
    'expoPrice',
    'barCode',
    'measure',
    'category_id',
    'company_id',
  ];
  protected $hidden =[
    'deleted_at',
    'updated_at',
    'created_at',
    'pivot'
  ];
   // public function category(){
   //   return $this->belongsTo(Category::class,'category_id');
   // }
   public function company(){
     return $this->belongsTo(Company::class,'company_id');
   }
   public function warehouse(){
     return $this->belongsTo(Warehouse::class);
   }
   public function sale(){
     return $this->belongsTo(Sale::class);
   }
   public function stocktaking(){
     return $this->belongsTo(Stock::class,'id','product_id');
   }

   public function category(){
       return $this->belongsTo(category::class);
   }
}
