<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Redirect;

class RedirectBasedOnRole
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {

        $user = $event->user;
        // Check user roles and redirect
        if ($user->access_level==1) {
            Redirect::setIntendedUrl(route('examinee.dashboard'));
        } elseif ($user->access_level==5) {
            Redirect::setIntendedUrl(route('examiner.dashboard.index'));
        } elseif ($user->access_level==10) {
            Redirect::setIntendedUrl(route('admin.dashboard.index'));
        }
    }
}
