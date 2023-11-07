<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InvoicePaid extends Notification
{
    use Queueable;
    private $invoice_id ;
    /**
     * Create a new notification instance.
     */
    public function __construct($invoice_id)
    {
        $this->invoice_id = $invoice_id ;
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
//tls
    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $url = 'http:127.0.0.1:8000/InvoicesDetails/'. $this->invoice_id ;
        return (new MailMessage)
                    ->greeting('مرحبا بك')
                    ->subject('اضافة فاتورة جديدة')
                    ->line('اضافة فاتورة جديدة')
                    ->action('عرض الفاتورة', $url)
                    ->line('شكرا لاستخدامك اسلام سوفت');
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
