<?php

namespace App\Notifications;

use App\Models\ContentReport;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Collection;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NewContentReport extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        protected ContentReport $report,
        protected Collection $otherReports
    )
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $entry = $this->report->entry;

        return (new MailMessage)
            ->subject("New Inappropriate Content Report Submitted")
            ->markdown('notifications.new_content_report', [
                'entryTitle' => $entry->title,
                'reason' => $this->report->reason,
                'reportReviewUrl' => route('statamic.cp.runway.edit', [
                    'resource' => 'contentreport',
                    'model' => $this->report->id
                ]),
                'otherReportsCount' => $this->otherReports->count(),
                'allReportsReviewUrl' => route('statamic.cp.runway.index', [
                    'resource' => 'contentreport',
                ])
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
