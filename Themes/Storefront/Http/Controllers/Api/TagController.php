<?php

namespace Themes\Storefront\Http\Controllers\Api;



use Modules\Tag\Entities\Tag;



class TagController
{
    public function index()
    {
        $all = Tag::all();
        // dd($all);

        return \response()->json($all);
    }

    public function tag($id)
    {
        $tag = Tag::find($id);

        return \response()->json($tag);
    }
}