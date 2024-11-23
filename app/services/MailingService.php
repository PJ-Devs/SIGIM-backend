<?php

namespace App\Services;

use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class MailingService
{
  /**
   * Send an email to a specific address given a Mailable instance.
   * 
   * @param string $to The email address to send the email to.
   * @param Mailable $mailable The Mailable instance to send.
   * @return void
   * 
   * Note: Ensure that the provided email address is valid and the 
   * Mailable class is correctly configured for the email content.
   */
  public function sendEmail(string $to, Mailable $mailable): void
  {
    Mail::to($to)->send($mailable);
  }
}
