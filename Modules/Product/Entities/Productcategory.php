<?php
namespace Modules\Product\Entities;


use Modules\Support\Eloquent\Model;
use Modules\Product\Entities\Product;
use Modules\Category\Entities\Category;

class Productcategory extends Model
{
	  protected $table = ['product_categories'];
	  
	   public function categories()
    {
        return $this->hasMany(Category::class);
    }
	
	public function products()
    {
        return $this->hasMany(Product::class);
    }
}