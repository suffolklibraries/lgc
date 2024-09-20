<?php

namespace App\Jobs;

use Statamic\Facades\Entry;
use Illuminate\Bus\Queueable;
use App\Notifications\EventReminder;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Notification;
use Statamic\Entries\Entry as EntryInstance;

class SendEventReminders implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $events  = Entry::query()
            ->where("collection", "events")
            ->whereIn("status", ["published", "scheduled"])
            ->whereDate("start_date", now()->addDays(5))
            ->get()
            ->each(function(EntryInstance $entry) {
                if ($entry->created_by) {
                    $email = $entry->created_by->email;
                } else {
                    $email = $entry->submitter_email_address;
                }
                Notification::route("mail", $email)
                    ->notify(new EventReminder($entry));
            });
    }
}
