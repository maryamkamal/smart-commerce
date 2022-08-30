<?php

namespace Modules\Product\Entities;

use Modules\Support\Eloquent\Model;
use Modules\Product\Admin\SupplierTable;
use Illuminate\Support\Facades\Cache;
use Modules\Support\Eloquent\Translatable;

class Supplier extends Model
{
    use Translatable;

    protected $table = 'suppliers';

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['translations'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['supplier_name'];

    /**
     * The attributes that are translatable.
     *
     * @var array
     */
    public $translatedAttributes = ['name'];

    
    /**
     * Get supplier list.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function list()
    {
        return Cache::tags('suppliers')->rememberForever(md5('suppliers.list:' . locale()), function () {
            return self::all()->sortBy('id')->pluck('supplier_name', 'id');
        });
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function table()
    {
        return new SupplierTable($this->newQuery());
    }

    public function searchTable()
    {
        return 'supplier_translations';
    }

    public function searchKey()
    {
        return 'supplier_id';
    }

    public function searchColumns()
    {
        return ['name'];
    }
}