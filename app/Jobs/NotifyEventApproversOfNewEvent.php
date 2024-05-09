<?php

namespace App\Jobs;

use App\Notifications\NewUserEventCreated;
use Statamic\Facades\User;
use Statamic\Entries\Entry;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class NotifyEventApproversOfNewEvent implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected Entry $entry
    )
    {

    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        User::all()
            ->filter(function($user) {
                if(in_array('event_approver', $user->roles)) {
                    return $user;
                }
            })
            ->values()
            ->each(function($user) {
                $user->notify(new NewUserEventCreated($this->entry));
            });
    }
}
