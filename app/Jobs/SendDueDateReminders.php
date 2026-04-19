<?php

namespace App\Jobs;

use App\Models\Transaction;
use App\Models\BorrowReminder;
use App\Notifications\DueDateReminderNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendDueDateReminders implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $tomorrow    = now()->addDay()->startOfHour();
        $tomorrowEnd = now()->addDay()->endOfHour();

        $transactions = Transaction::with(['borrower', 'resource'])
            ->where('status', 'active')
            ->whereBetween('due_date', [$tomorrow, $tomorrowEnd])
            ->whereDoesntHave('reminders', function ($q) {
                $q->where('status', 'sent');
            })
            ->get();

        foreach ($transactions as $transaction) {
            try {
                $transaction->borrower->notify(new DueDateReminderNotification($transaction));

                BorrowReminder::updateOrCreate(
                    ['transaction_id' => $transaction->id, 'user_id' => $transaction->borrower_id],
                    [
                        'due_date'         => $transaction->due_date,
                        'status'           => 'sent',
                        'reminder_sent_at' => now(),
                    ]
                );

                Log::info("Reminder sent for transaction #{$transaction->id} to user #{$transaction->borrower_id}");
            } catch (\Exception $e) {
                Log::error("Failed to send reminder for transaction #{$transaction->id}: " . $e->getMessage());

                BorrowReminder::updateOrCreate(
                    ['transaction_id' => $transaction->id, 'user_id' => $transaction->borrower_id],
                    ['due_date' => $transaction->due_date, 'status' => 'failed']
                );
            }
        }
    }
}
