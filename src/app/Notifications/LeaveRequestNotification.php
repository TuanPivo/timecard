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

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($leaveRequest)
    {
        $this->leaveRequest = $leaveRequest;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['slack', 'mail'];
    }

    public function toSlack($notifiable)
    {
        $url = url('/admin/leave-requests');
        return (new SlackMessage)
            ->from('New Notification')
            ->content("New request from {$this->leaveRequest->user->name}: {$this->leaveRequest->title}")
            ->attachment(function ($attachment) use ($url) {
                $attachment->title('Attendance Request')->action('Click to view requirements', $url);
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
        $url = url('/admin/leave-requests');
        return (new MailMessage)
            ->from('no-reply@example.com', 'New Notification')  // Đặt địa chỉ và tên người gửi
            ->subject('New Leave Request')  // Chủ đề email
            ->line("New request from {$this->leaveRequest->user->name}: {$this->leaveRequest->title}")  // Nội dung chính
            ->action('Click to view request details', $url)  // Nút để dẫn tới URL trang leave request
            ->line('Thank you for using our application!');  // Lời kết
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
