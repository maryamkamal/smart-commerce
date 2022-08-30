<?php

namespace Themes\Storefront\Http\Controllers\Api;



use Illuminate\Http\Request;

use Modules\Review\Entities\Review;
use Modules\User\Entities\User;
use Modules\Product\Entities\Product;

use Modules\Review\Http\Requests\StoreReviewRequest;





class ReviewController

{

    public function index()

    {

        $reviews = Review::all();

        return \response()->json($reviews);

    }

    

    public function review($id)

    {

        

        $review = Review::find($id);

        return \response()->json($review);

    }



    /**

     * Store a newly created resource in storage.

     *

     * @param int $productId

     * @param \Modules\Review\Http\Requests\StoreReviewRequest $request

     * @return \Illuminate\Http\Response

     */

    public function store($productId,Request $request)

    {

        if (! setting('reviews_enabled')) {

            return response()->json(['message' => 'reviews_disabled']);

        }

        // return response()->json([$productId,$request->all()]);

          $review= new Review;
		  
       if($request->api_token){
	   
       $user = User::where('api_token',$request->api_token)->first();
		$reviewer_id=$user->id;
		$reviewer_name=$user->first_name;
		//dd($reviewer_name);
	   }
		else{
			$reviewer_id=null;
		    $reviewer_name = $request->reviewer_name;
		}
		
             
		   
                 $review->product_id = $productId;
                $review->reviewer_id  = $reviewer_id; // need to be auth key
                $review->rating  = $request->rating;

                $review->reviewer_name = $reviewer_name;

               $review->comment = $request->comment;

                 $review->is_approved = setting('auto_approve_reviews', 0);

           
			
          $review->save();


            return response()->json(['message' => 'review stored ']);

    }

}