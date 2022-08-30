<?php
namespace Themes\Storefront\Http\Controllers\Api;

use Modules\Page\Entities\Page;



class PageController
{
    public function index()
    {
        $pages = Page::all();
        return \response()->json($pages);
    }
    
    public function page($id)
    {
        
        $page = Page::find($id);
        return \response()->json($page);
    }
}