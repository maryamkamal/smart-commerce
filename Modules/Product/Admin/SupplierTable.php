<?php

namespace Modules\Product\Admin;

use Modules\Product\Entities\Supplier;
use Modules\Admin\Ui\AdminTable;

class SupplierTable extends AdminTable
{
    /**
     * Make table response for the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function make()
    {
        return $this->newTable();
            // ->addColumn('logo', function (Brand $brand) {
            //     return view('admin::partials.table.image', [
            //         'file' => $brand->logo,
            //     ]);
            // });
    }
}
