<?php

namespace App\Jobs;

use Statamic\Facades\User;
use App\Models\ContentReport;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use App\Notifications\NewContentReport;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Notification;

class NotifyEventApproversOfContentReport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected ContentReport $report
    )
    {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $otherReports = ContentReport::where('id', '!=', $this->report->id)
            ->where('complete', false)
            ->get();

        Notification::route('mail', env('EVENT_REVIEWER_EMAIL', 'get.creative@suffolklibraries.co.uk'))
            ->notify(new NewContentReport($this->report, $otherReports));
    }
}
