<?php

namespace App\Jobs;

use Statamic\Facades\Entry;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class UnpublishPassedEvents implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    { }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        Entry::query()
            ->where('collection', 'events')
            ->where('published', true)
            ->where('end_date', '<', now())
            ->get()
            ->each(function($entry) {
                $entry->set('published', false);
                $entry->save();
            });

    }
}
