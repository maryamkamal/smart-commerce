<?php

namespace Modules\Product\Entities\Api;

use Modules\Support\Eloquent\TranslationModel;

class ProductTranslation extends TranslationModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	 protected $table = 'product_translations';
    protected $fillable = ['name', 'description', 'short_description'];
}
