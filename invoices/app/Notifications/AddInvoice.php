<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Invoice;
use App\Models\User;

class AddInvoice extends Notification
{
    use Queueable;

    private $invoices;

    public function __construct(Invoice $invoices )
    {
        $this->invoices = $invoices;

    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable){
        return [
            'id' => $this->invoices->id ,
            'title' => "تم اضافة فاتورة جديدة بواسطة :" ,
            'user'  => $this->invoices->user ,
 
        ];
    }
}
