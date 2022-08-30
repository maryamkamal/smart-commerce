<?php

namespace Modules\Product\Entities\Api;

use Modules\Support\Eloquent\TranslationModel;

class CategoryTranslation extends TranslationModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];
}
