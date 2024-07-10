<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\SlackMessage;

class LeaveRequestNotification extends Notification
{
    use Queueable;

    protected $leaveRequest;
    protected $formattedStartDate;
    protected $formattedEndDate;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($leaveRequest, $formattedStartDate, $formattedEndDate)
    {
        $this->leaveRequest = $leaveRequest;
        $this->formattedStartDate = $formattedStartDate;
        $this->formattedEndDate = $formattedEndDate;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        // return ['mail'];
        return ['slack'];
    }

    public function toSlack($notifiable)
    {
        $url = url('/admin/leave-requests');

        return (new SlackMessage)
            ->from('New Notification')
            ->content("New leave request from {$this->leaveRequest->user->name}: {$this->leaveRequest->reason}")
            ->attachment(function ($attachment) use ($url) {
                $attachment
                    // ->title('Leave Details')
                    // ->fields([
                    //     'Start' => $this->formattedStartDate,
                    //     'End' => $this->formattedEndDate,
                    // ])
                    ->action('Click to view requirements', $url);
            });
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
