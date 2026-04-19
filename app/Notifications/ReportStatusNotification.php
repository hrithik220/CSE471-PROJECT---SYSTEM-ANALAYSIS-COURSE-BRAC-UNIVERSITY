<?php

namespace App\Notifications;

use App\Models\ResourceReport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReportStatusNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public readonly ResourceReport $report,
        public readonly string $recipientType // 'reporter' or 'borrower'
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        if ($this->recipientType === 'reporter') {
            return (new MailMessage)
                ->subject('✅ Your Report Has Been Reviewed')
                ->greeting("Hello, {$notifiable->name}!")
                ->line("Your report regarding '{$this->report->resource->title}' has been updated.")
                ->line("**Status:** " . ucfirst(str_replace('_', ' ', $this->report->status)))
                ->line($this->report->admin_notes ? "**Admin Notes:** {$this->report->admin_notes}" : '')
                ->action('View Report', url("/reports/{$this->report->id}"))
                ->salutation('Thanks, The ShareCommunity Team');
        }

        return (new MailMessage)
            ->subject('⚠️ A Report Has Been Filed Against You')
            ->greeting("Hello, {$notifiable->name}!")
            ->line("A report has been filed regarding a borrowed item.")
            ->line("**Item:** {$this->report->resource->title}")
            ->line("**Report Type:** " . ucfirst($this->report->report_type))
            ->line("**Penalty Applied:** " . ucfirst($this->report->penalty_applied))
            ->line("Please contact admin if you believe this is a mistake.")
            ->action('View Details', url("/reports/{$this->report->id}"))
            ->salutation('ShareCommunity Team');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'        => 'report_status',
            'report_id'   => $this->report->id,
            'resource'    => $this->report->resource->title,
            'status'      => $this->report->status,
            'penalty'     => $this->report->penalty_applied,
            'message'     => $this->recipientType === 'reporter'
                ? "Your report has been updated to: {$this->report->status}"
                : "A report was filed against you for: {$this->report->resource->title}",
        ];
    }
}
