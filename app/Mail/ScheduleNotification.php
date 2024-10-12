<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Modules\Registration\Models\Registration;

class ScheduleNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $registration;

    public function __construct(Registration $registration)
    {
        $this->registration = $registration;
    }

    public function build()
    {
        return $this->subject('Vaccination Schedule Notification')
            ->view('emails.schedule_notification');
    }
}

