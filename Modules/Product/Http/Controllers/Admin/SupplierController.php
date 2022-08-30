<?php

namespace Modules\Product\Http\Controllers\Admin;

use Modules\Product\Entities\Supplier;
use Modules\Admin\Traits\HasCrudActions;

class SupplierController
{
    use HasCrudActions;
   /**
     * Model for the resource.
     *
     * @var string
     */
    protected $model = Supplier::class;

    /**
     * Label of the resource.
     *
     * @var string
     */
    protected $label = 'product::products.supplier';

    /**
     * View path of the resource.
     *
     * @var string
     */
    protected $viewPath = 'product::admin.suppliers';

    /**
     * Form requests for the resource.
     *
     * @var array|string
     */
    // protected $validation = SaveProductRequest::class;
}
