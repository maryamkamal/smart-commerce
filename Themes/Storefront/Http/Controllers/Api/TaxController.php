<?php

namespace Themes\Storefront\Http\Controllers\Api;



use Modules\Tax\Entities\TaxClass;



class TaxController
{
    public function index()
    {
        $all = TaxClass::all();
        // dd($all);

        return \response()->json($all);
    }

    public function tax($id)
    {
        $tax = TaxClass::find($id);

        return \response()->json($tax);
    }
}