<?php

namespace App\Listeners;

use App\Mail\userRegister;
use App\Events\AfterCreateAcount;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendMail
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  AfterCreateAcount  $event
     * @return void
     */
    public function handle(AfterCreateAcount $event)
    {
        Mail::to($event->user->email)->send(new userRegister($event->user));
    }
}
