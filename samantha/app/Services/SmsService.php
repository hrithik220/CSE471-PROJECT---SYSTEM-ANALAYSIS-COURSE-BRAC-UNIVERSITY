<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * SmsService
 * Wraps the SMS Notification API.
 * Configure SMS_API_KEY and SMS_API_URL in your .env file.
 *
 * Compatible with: Twilio, BulkSMS, SSL Wireless, or any REST SMS gateway.
 */
class SmsService
{
    protected string $apiKey;
    protected string $apiUrl;
    protected string $senderId;

    public function __construct()
    {
        $this->apiKey   = config('services.sms.key');
        $this->apiUrl   = config('services.sms.url');
        $this->senderId = config('services.sms.sender_id', 'CampusShare');
    }

    /**
     * Send an SMS to a phone number.
     *
     * @param  string  $phone    Recipient phone (e.g. +8801XXXXXXXXX)
     * @param  string  $message  Message body (max ~160 chars for single SMS)
     * @return bool
     */
    public function send(string $phone, string $message): bool
    {
        if (app()->isLocal()) {
            // In local/dev environment, just log the SMS instead of sending
            Log::info('[SmsService] SMS to ' . $phone . ': ' . $message);
            return true;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type'  => 'application/json',
            ])->post($this->apiUrl, [
                'to'        => $phone,
                'from'      => $this->senderId,
                'message'   => $message,
            ]);

            if ($response->successful()) {
                Log::info('[SmsService] SMS sent to ' . $phone);
                return true;
            }

            Log::warning('[SmsService] SMS failed for ' . $phone . ': ' . $response->body());
            return false;

        } catch (\Exception $e) {
            Log::error('[SmsService] Exception: ' . $e->getMessage());
            return false;
        }
    }
}
