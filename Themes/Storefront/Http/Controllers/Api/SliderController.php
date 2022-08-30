<?php

namespace Themes\Storefront\Http\Controllers\Api;

use Modules\Slider\Entities\Slider;


class SliderController
{
    public function index()
    {
       $all = Slider::all();
       // dd($all);

        return \response()->json($all);
    
    }

    public function slider($id)
    {
        $slider = Slider::find($id);

        return \response()->json($slider);
    }
}