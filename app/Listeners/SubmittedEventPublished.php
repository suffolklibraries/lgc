<?php

namespace App\Listeners;

use App\Notifications\UserEventPublished;
use Statamic\Facades\Entry;
use Statamic\Events\EntrySaved;
use Statamic\Events\EntrySaving;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class SubmittedEventPublished
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
    public function handle(EntrySaving $event)
    {
        if($event->entry->collection->handle === 'events') {
            $originalEntry = Entry::find($event->entry->id);

            if($originalEntry && !$originalEntry->published && $event->entry->published) {
                if($event->entry->submitter_name && $event->entry->submitter_email_address) {
                    Notification::route('mail', $event->entry->submitter_email_address)
                        ->notify(new UserEventPublished($event->entry->submitter_name, $event->entry));
                }
            }
        }
    }
}
