<?php

namespace Modules\Order\Http\Controllers\Admin;

use Modules\Order\Entities\Order;
use Illuminate\Support\Facades\Mail;
use Modules\Order\Mail\OrderStatusChanged;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require base_path().'/PHPMailer/src/PHPMailer.php';
require  base_path().'/PHPMailer/src/SMTP.php';
class OrderStatusController
{
    /**
     * Update the specified resource in storage.
     *
     * @param \Modules\Order\Entities\Order $request
     * @return \Illuminate\Http\Response
     */
    public function update(Order $order)
    {
        $order->update(['status' => request('status')]);

        $message = trans('order::messages.status_updated');

        if (setting('order_status_email')) {
           /* Mail::to($order->customer_email)
                ->send(new OrderStatusChanged($order));*/
		$body =new OrderStatusChanged($order);
		
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
                    $mail->addAddress($order->customer_email);     // Add a recipient

                    // Add attachments

                    // Content
                    $mail->isHTML(true);
                    $mail->Subject =  "Order Status";
                    $mail->Body    = $body->text;
                    $mail->send();
                } catch (Exception $e) {
                    die($e->getMessage());
                }
        }

        return $message;
    }
}
