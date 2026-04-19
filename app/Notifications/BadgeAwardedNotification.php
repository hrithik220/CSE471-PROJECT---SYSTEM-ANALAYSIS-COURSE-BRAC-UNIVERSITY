<?php

namespace App\Notifications;

use App\Models\Badge;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BadgeAwardedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public readonly Badge $badge
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("🏆 You've Earned the '{$this->badge->name}' Badge!")
            ->greeting("Congratulations, {$notifiable->name}!")
            ->line("You've earned a new badge for your community contributions!")
            ->line("{$this->badge->icon} **{$this->badge->name}**")
            ->line($this->badge->description)
            ->action('View My Profile', url('/profile'))
            ->salutation('Keep up the amazing work! - ShareCommunity Team');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'    => 'badge_awarded',
            'badge'   => $this->badge->name,
            'icon'    => $this->badge->icon,
            'message' => "You earned the '{$this->badge->name}' badge! {$this->badge->icon}",
        ];
    }
}
