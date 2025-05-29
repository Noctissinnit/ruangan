<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;
use App\Mail\InvitationMail;
use App\Models\Booking;
use App\Models\User;

class ProcessBookingInvitationMail implements ShouldQueue
{
    use Queueable;
    
    private Booking $booking;
    private User $user;

    /**
     * Create a new job instance.
     */
    public function __construct(Booking $booking, User $user)
    {
        $this->booking = $booking;
        $this->user = $user;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->user)->send(new InvitationMail($this->booking, $this->user));
    }
}
