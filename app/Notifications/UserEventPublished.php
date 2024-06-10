<?php

namespace App\Notifications;

use Statamic\Entries\Entry;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Statamic\Facades\GlobalSet;

class UserEventPublished extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        protected string $name,
        protected Entry $entry
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
        return (new MailMessage)
            ->subject("Your Event has been approved!")
            ->greeting(__("Hi :name", [
                'name' => $this->name
            ]))
            ->line(__('Your event ":eventName" (on :date) has been approved, and is published!', [
                'eventName' => $this->entry->title,
                'date' => $this->entry->start_date->format('l jS M Y')
            ]))
            ->action('See your event', $this->entry->permalink)
            ->line(__('Something wrong? Contact us at :email', [
                'email' => GlobalSet::findByHandle("global")->inDefaultSite()->get("listing_email") ?? 'get.creative@suffolklibraries.co.uk'
            ]));
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
