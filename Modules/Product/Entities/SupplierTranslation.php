<?php

namespace Modules\Product\Entities;

use Modules\Support\Eloquent\TranslationModel;

class SupplierTranslation extends TranslationModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];
}
