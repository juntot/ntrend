<?php
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Swift_Mailer;

class SecondaryMailer {

  public static function Send($email, $message, $subject) ///Send($view, array $data, array $params)
  {
      // Backup your default mailer
      $backup = Mail::getSwiftMailer();

      // Setup your gmail mailer
      $transport = \Swift_SmtpTransport::newInstance('mail.smpt2go.com', 465, 'tls');
      $transport->setUsername('ntmitd');
      $transport->setPassword('n0rthr3nd');
      // Any other mailer configuration stuff needed...
      $gmail = new Swift_Mailer($transport);

      // Set the mailer as gmail
      Mail::setSwiftMailer($gmail);

      // Send your message
      // Mail::send($view, $data, function($message) use($params)
      // {
      //     $message->from($params['email'])->to($params['toEmail'])->subject($params['subject']);
      // });
      Mail::to($email)->send(new FormMail($message, $subject));

      // Restore your original mailer
      Mail::setSwiftMailer($backup);

  }

}