<?php

namespace Modules\Page\Http\Controllers;
use Artisan;
class HomeController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
           Artisan::call('cache:clear');
			Artisan::call('route:clear');
			Artisan::call('view:clear');
			
        return view('public.home.index');
    }
}
