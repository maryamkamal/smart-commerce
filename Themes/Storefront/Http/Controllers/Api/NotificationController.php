<?php

namespace Themes\Storefront\Http\Controllers\Api;
use Modules\Media\Entities\File;
use DB;
class NotificationController
{
	public function index()
    {
		$notifications = DB::table('notifications')->orderBy('id', 'desc')->get();
		return \response()->json($notifications);
	}
	
	public function sendNotification()
    {
		$firebaseToken = DB::table('mobile_tokens')->whereNotNull('token')->pluck('token')->all();

        $SERVER_API_KEY = "AAAA0ZxJBvk:APA91bEqBxW4Ni3YpNHoZkJq2DTKt42jOj993C-nlymN0uKFBFf-162bnJPHlsA6dv3RunlJO08ZWXPh4Tr_ZqdwbJvsQzKMoWEyjVAsBnD-qch2xlDI5iEsmW3zpfngAzOYcXf8Ek3-";
        /*$logo = File::findOrNew(setting('storefront_header_logo'))->path;
	   if(request()->image){
       $imageName = time().'.'.request()->image->extension();  
       request()->image->move(public_path('storage/media'), $imageName);
	    $image = env('APP_URL').'/storage/media/'.$imageName;
		
	 }
	 else{
		 $imageName=null;
		 $image= $logo;
	 }*/
	 
	
        $data = [

            "registration_ids" =>$firebaseToken ,

            "data" => [

                "title" => request()->title,
                "body" => request()->message, 
                "image" => null, 

            ]

        ];

        $dataString = json_encode($data);

        $headers = [

            'Authorization: key=' . $SERVER_API_KEY,

            'Content-Type: application/json',

        ];

        $ch = curl_init();


        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');

        curl_setopt($ch, CURLOPT_POST, true);

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
  

        $response = curl_exec($ch);
		
       DB::table('notifications')->insert(
      ['title' => request()->title,
      'message' => request()->message,
	  "image" =>null]
     );

      
        return \response()->json(['message' => 'send_successfully']);
  
	}
}