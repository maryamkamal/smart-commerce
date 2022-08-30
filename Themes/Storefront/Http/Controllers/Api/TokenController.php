<?php
namespace Themes\Storefront\Http\Controllers\Api;

use DB;
use Illuminate\Http\Request;

class TokenController
{
	 public function storetokens(Request $request)
    {
		DB::table('mobile_tokens')->insert([
    'token' => $request->token,
]);
	return \response()->json(['message' => 'saved_succeffly']);
	
	}
}