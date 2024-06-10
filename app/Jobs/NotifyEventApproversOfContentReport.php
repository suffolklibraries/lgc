<?php

namespace App\Jobs;

use App\Models\ContentReport;
use App\Notifications\NewContentReport;
use Statamic\Facades\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

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

        User::all()
            ->filter(function($user) {
                if(in_array('event_approver', $user->roles)) {
                    return $user;
                }
            })
            ->values()
            ->each(function($user) use ($otherReports) {
                $user->notify(new NewContentReport($this->report, $otherReports));
            });
    }
}
