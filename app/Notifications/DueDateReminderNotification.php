<?php

namespace App\Notifications;

use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DueDateReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Transaction $transaction) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'           => 'due_date_reminder',
            'transaction_id' => $this->transaction->id,
            'resource_name'  => $this->transaction->resource->title ?? 'Resource',
            'due_date'       => $this->transaction->due_date,
            'message'        => 'Your borrowed item "' . ($this->transaction->resource->title ?? 'Resource') . '" is due in 24 hours.',
            'url'            => '/transactions',
        ];
    }
}
