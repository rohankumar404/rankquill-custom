<?php

namespace App\Http\Controllers;

use App\Models\NewsletterSubscriber;
use App\Services\NewsletterService;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PublicNewsletterController extends Controller
{
    protected NewsletterService $newsletterService;

    public function __construct(NewsletterService $newsletterService)
    {
        $this->newsletterService = $newsletterService;
    }

    /**
     * Subscribe a new visitor to the newsletter.
     */
    public function subscribe(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email|max:255',
        ]);

        $subscriber = NewsletterSubscriber::where('email', $request->email)->first();

        if ($subscriber) {
            if ($subscriber->status === 'active') {
                return response()->json([
                    'success' => false,
                    'errors' => [
                        'email' => ['This email address is already subscribed.'],
                    ],
                ], 422);
            }

            // Resubscribe existing contact
            $subscriber->update([
                'status' => 'active',
                'token' => $subscriber->token ?? Str::random(32),
                'subscribed_at' => now(),
                'unsubscribed_at' => null,
            ]);
        } else {
            // Create new subscriber
            $subscriber = NewsletterSubscriber::create([
                'email' => $request->email,
                'token' => Str::random(32),
                'status' => 'active',
                'subscribed_at' => now(),
            ]);
        }

        // Sync with integrations (Mailchimp/Brevo)
        try {
            $this->newsletterService->sync($subscriber, false);
        } catch (\Exception $e) {
            report($e); // Log the error but don't crash the UI
        }

        return response()->json([
            'success' => true,
            'message' => 'Thank you! You have successfully subscribed to our newsletter.',
        ]);
    }

    /**
     * Render the unsubscribe form or process the unsubscribe action.
     */
    public function unsubscribe(Request $request): Response
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required|string',
        ]);

        $subscriber = NewsletterSubscriber::where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (! $subscriber) {
            // If the subscriber or token is invalid, render the view showing an error message
            return Inertia::render('Public/Newsletter/Unsubscribe', [
                'success' => false,
                'message' => 'Invalid email address or validation token. Please contact support.',
            ]);
        }

        // Unsubscribe the user
        if ($subscriber->status !== 'unsubscribed') {
            $subscriber->update([
                'status' => 'unsubscribed',
                'unsubscribed_at' => now(),
            ]);

            // Sync unsubscribe to integrations
            try {
                $this->newsletterService->sync($subscriber, true);
            } catch (\Exception $e) {
                report($e);
            }
        }

        return Inertia::render('Public/Newsletter/Unsubscribe', [
            'success' => true,
            'email' => $subscriber->email,
            'message' => 'You have been successfully unsubscribed from our mailing list.',
        ]);
    }
}
