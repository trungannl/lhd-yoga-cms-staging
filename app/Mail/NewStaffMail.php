<?php

namespace App\Mail;

use App\Models\Staff;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewStaffMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $staff, $password;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Staff $staff, $password)
    {
        $this->staff = $staff;
        $this->password = $password;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Welcome to Ahas')
            ->view('emails.new_staff', [
                'staff' => $this->staff,
                'password' => $this->password
            ]);
    }
}
