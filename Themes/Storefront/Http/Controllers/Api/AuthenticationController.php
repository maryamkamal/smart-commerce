<?php
namespace Themes\Storefront\Http\Controllers\Api;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Modules\User\Mail\Welcome;
use Modules\User\Entities\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Modules\User\Mail\ResetPasswordEmail;
use Modules\User\Contracts\Authentication;
use Modules\User\Http\Controllers\AuthController;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
//require base_path().'/PHPMailer/src/PHPMailer.php';
//require  base_path().'/PHPMailer/src/SMTP.php';

class AuthenticationController extends AuthController
{

    protected $auth;

    public function __construct(Authentication $auth)
    {
        $this->auth = $auth;

    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        if($validator->fails()){
            return \response()->json($validator->messages());
        }

        $find = User::where('email',$request->email)->first();

        if(!$find){
            return \response()->json(['message' => 'wrong_email']); 
        }

        if (!Hash::check($request->password, $find->password)) {
            return \response()->json(['message' => 'wrong_password']);
        }
        $find->last_login = Carbon::now();
        $find->save();
        return \response()->json(['message' => 'success','api_token' => $find->api_token]);

    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'privacy_policy' => 'accepted',
        ]);

        if($validator->fails()){
            return \response()->json($validator->messages());
        }

        $registeredUser = $this->auth->registerAndActivate([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => $request->password,
        ]);


        parent::assignCustomerRole($registeredUser);

        $get = User::find($registeredUser->id);

        $string = $get->first_name . $get->id . Str::random();
        $api_token = Hash::make($string);
        $get->api_token = $api_token;
        $get->save();

        
        return \response()->json(['message' => 'success','api_token' => $get->api_token]);

       
        
    }

   /* public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'email' => 'required|email',
        ]);

        if($validator->fails()){
            return \response()->json($validator->messages());
        }

        $user = User::where('email', $request->email)->first();

        if (is_null($user)) {
            return \response()->json(['message' => 'not_found']);
        }

        $code = $this->auth->createReminderCode($user);


            return \response()->json(['message' => 'email_sent','url' => route('resetpassword',[$user->email,$code])]);
    }*/

    
    
    public function sociallogin(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'email' => 'required|email',
    
        ]);

        if($validator->fails()){
            return \response()->json($validator->messages());
        }

        $user = User::where('email',$request->email)->first();
        if($user){
        $user->last_login = Carbon::now();
        $user->save();
        return \response()->json(['message' => 'success','api_token' => $user->api_token]);
        }
        else{
         $validator = Validator::make($request->all(),[
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users',

        ]);

        if($validator->fails()){
            return \response()->json($validator->messages());
        }
         
        $registeredUser = $this->auth->registerAndActivate([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'gender' => $request->gender,
            'password' => str_random(),
            
        ]);

        parent::assignCustomerRole($registeredUser);
       
        $user= User::find($registeredUser->id);
        $string = $user->first_name . $user->id . str_random();
        $api_token = Hash::make($string);
        $user->api_token = $api_token;
        $user->save();
         return \response()->json(['message' => 'success','api_token' => $user->api_token]);
        }

    }
    
    
    public function resetcode(Request $request)
    {
    $user = User::where('email', $request->email)->first();

        if (is_null($user)) {
            return \response()->json(['message' => 'user_not_found']);
        }

        $code = mt_rand(100000, 999999);
        $user->reset_code = $code;
        $user->save();
         $mail = new PHPMailer(true);

                try {
                    //Server settings
                    // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
                    $mail->isSMTP();                                            // Send using SMTP
                    $mail->Host       = env('MAIL_HOST');                    // Set the SMTP server to send through
                    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
                    $mail->Username   = env('MAIL_USERNAME');                     // SMTP username
                    $mail->Password   = env('MAIL_PASSWORD');                               // SMTP password
                    $mail->SMTPSecure = env('MAIL_ENCRYPTION');         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
                    $mail->Port       = env('MAIL_PORT');                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

                    //Recipients
                    $mail->setFrom(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
                    $mail->addAddress($request->email);     // Add a recipient

                    // Add attachments

                    // Content
                    $mail->isHTML(true);
                    $mail->Subject =  "resert Password code ";
                    $mail->Body    = "Your resert Password code is : ".$code;
                    $mail->send();
                } catch (Exception $e) {
                    die($e->getMessage());
                }
         return \response()->json(['message' => 'send_successfully']);
    }
     
    public function postReset(Request $request)
    {
     $user = User::where('reset_code', $request->resetcode)->first();

        if (is_null($user)) {
            return \response()->json(['message' => 'incorrect_code']);
        }

        $user->password = Hash::make(request()->get('password'));
        $user->save();
         return \response()->json(['message' => 'password_updated','api_token' => $user->api_token]);
    }
    
      public function adminlogin(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        if($validator->fails()){
            return \response()->json($validator->messages());
        }

        $user = User::where('email',$request->email)->first();

        if(!$user){
            return \response()->json(['message' => 'wrong_email']); 
        }
       if($user->hasRoleId(1)){
        if (!Hash::check($request->password, $user->password)) {
            return \response()->json(['message' => 'wrong_password']);
        }
        $user ->last_login = Carbon::now();
        $user ->save();
        return \response()->json(['message' => 'success','api_token' => $user ->api_token]);
       }

    }
}