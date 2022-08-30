<?php

namespace Modules\Account\Http\Controllers;

class AccountWishlistController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		$wishlist = auth()->user()
            ->wishlist()
            ->latest()
            ->paginate(20);

        return view('public.account.wishlist.index', compact('wishlist'));
		
    }
}
