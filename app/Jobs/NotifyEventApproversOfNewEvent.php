<?php

namespace App\Jobs;

use Statamic\Facades\User;
use Statamic\Entries\Entry;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use App\Notifications\NewUserEventCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Notification;
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
        Notification::route('mail', env('EVENT_REVIEWER_EMAIL', 'get.creative@suffolklibraries.co.uk'))
            ->notify(new NewUserEventCreated($this->entry));
    }
}
