<?php

namespace App\Notifications;

use Statamic\Entries\Entry;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class EventReminder extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        protected Entry $entry
    )
    {}

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
        if ($this->entry->created_by) {
            $name = $this->entry->created_by->email;
        } else {
            $name = $this->entry->submitter_name;
        }

        $message = (new MailMessage)
            ->subject(__('":eventName" is coming soon', [
                'eventName' => $this->entry->title
            ]))
            ->greeting(__("Hi :name,", [
                'name' => $name
            ]))
            ->line(__('Your event ":eventName" (on :date) is coming soon!', [
                'eventName' => $this->entry->title,
                'date' => $this->entry->start_date->format('l jS M Y')
            ]));

        if($this->entry->created_by) {
            $message
                ->line("Have your plans changed? No problem! Click the button below to update or cancel the event.")
                ->action('See your event', url(route('user.dashboard.my-events.edit', $this->entry->id)));
        } else {
            $message
                ->line("Have your plans changed? No problem! Contact us at Get.Creative@suffolklibraries.co.uk.");
        }

        return $message;
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
