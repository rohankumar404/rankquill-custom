<?php

namespace App\Services;

use App\Models\NewsletterSubscriber;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NewsletterService
{
    /**
     * Synchronize a subscriber to Mailchimp if enabled.
     */
    public function syncToMailchimp(NewsletterSubscriber $subscriber, bool $unsubscribe = false): bool
    {
        $enabled = setting('mailchimp_enabled');
        $apiKey = setting('mailchimp_api_key');
        $listId = setting('mailchimp_list_id');

        if ($enabled !== 'true' && $enabled !== '1' && $enabled !== true) {
            return false;
        }

        if (empty($apiKey) || empty($listId)) {
            Log::warning('Mailchimp integration enabled but API Key or List ID is empty.');

            return false;
        }

        // Parse datacenter prefix from API key (e.g. abcde123-us20 -> us20)
        $dc = 'us1';
        if (str_contains($apiKey, '-')) {
            $parts = explode('-', $apiKey);
            $dc = end($parts);
        }

        $emailHash = md5(strtolower($subscriber->email));
        $url = "https://{$dc}.api.mailchimp.com/3.0/lists/{$listId}/members/{$emailHash}";

        $status = $unsubscribe ? 'unsubscribed' : 'subscribed';

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Basic '.base64_encode('user:'.$apiKey),
                'Content-Type' => 'application/json',
            ])->put($url, [
                'email_address' => $subscriber->email,
                'status_if_new' => $status,
                'status' => $status,
            ]);

            if ($response->failed()) {
                Log::error('Mailchimp API Sync failed: '.$response->body());

                return false;
            }

            return true;
        } catch (\Throwable $e) {
            Log::error('Mailchimp API Sync Exception: '.$e->getMessage());

            return false;
        }
    }

    /**
     * Synchronize a subscriber to Brevo (Sendinblue) if enabled.
     */
    public function syncToBrevo(NewsletterSubscriber $subscriber, bool $unsubscribe = false): bool
    {
        $enabled = setting('brevo_enabled');
        $apiKey = setting('brevo_api_key');
        $listId = setting('brevo_list_id');

        if ($enabled !== 'true' && $enabled !== '1' && $enabled !== true) {
            return false;
        }

        if (empty($apiKey)) {
            Log::warning('Brevo integration enabled but API Key is empty.');

            return false;
        }

        try {
            if ($unsubscribe) {
                // To unsubscribe, we blacklist the contact in Brevo
                $encodedEmail = urlencode($subscriber->email);
                $url = "https://api.brevo.com/v3/contacts/{$encodedEmail}";

                $response = Http::withHeaders([
                    'api-key' => $apiKey,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ])->put($url, [
                    'emailBlacklisted' => true,
                ]);
            } else {
                // Otherwise, create or update contact and associate with the list ID
                $url = 'https://api.brevo.com/v3/contacts';

                $payload = [
                    'email' => $subscriber->email,
                    'updateEnabled' => true,
                ];

                if (! empty($listId)) {
                    $payload['listIds'] = [(int) $listId];
                }

                $response = Http::withHeaders([
                    'api-key' => $apiKey,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ])->post($url, $payload);
            }

            if ($response->failed()) {
                Log::error('Brevo API Sync failed: '.$response->body());

                return false;
            }

            return true;
        } catch (\Throwable $e) {
            Log::error('Brevo API Sync Exception: '.$e->getMessage());

            return false;
        }
    }

    /**
     * Process both Mailchimp and Brevo synchronization for a contact.
     */
    public function sync(NewsletterSubscriber $subscriber, bool $unsubscribe = false): void
    {
        $this->syncToMailchimp($subscriber, $unsubscribe);
        $this->syncToBrevo($subscriber, $unsubscribe);
    }
}
