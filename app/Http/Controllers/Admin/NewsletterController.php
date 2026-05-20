<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use App\Services\NewsletterService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class NewsletterController extends Controller
{
    protected NewsletterService $newsletterService;

    public function __construct(NewsletterService $newsletterService)
    {
        $this->newsletterService = $newsletterService;
    }

    public function index(Request $request): Response
    {
        $query = NewsletterSubscriber::query();

        // Search filter
        if ($request->filled('search')) {
            $query->where('email', 'like', '%'.$request->search.'%');
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $subscribers = $query->latest()->paginate(20)->withQueryString();

        // Calculate KPI Counts
        $totalSubscribers = NewsletterSubscriber::count();
        $activeCount = NewsletterSubscriber::where('status', 'active')->count();
        $unsubscribedCount = NewsletterSubscriber::where('status', 'unsubscribed')->count();
        $pendingCount = NewsletterSubscriber::where('status', 'pending')->count();

        // Compile 30 days signup analytics trends (with missing date padding)
        $trends = NewsletterSubscriber::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get()
            ->pluck('count', 'date')
            ->toArray();

        $recentTrends = [];
        for ($i = 29; $i >= 0; $i--) {
            $day = now()->subDays($i);
            $formattedDate = $day->format('Y-m-d');
            $recentTrends[] = [
                'date' => $formattedDate,
                'label' => $day->format('M d'),
                'count' => $trends[$formattedDate] ?? 0,
            ];
        }

        return Inertia::render('Admin/Newsletter', [
            'subscribers' => $subscribers,
            'filters' => $request->only(['search', 'status']),
            'stats' => [
                'total' => $totalSubscribers,
                'active' => $activeCount,
                'unsubscribed' => $unsubscribedCount,
                'pending' => $pendingCount,
            ],
            'recent_trends' => $recentTrends,
        ]);
    }

    public function destroy(NewsletterSubscriber $newsletter): RedirectResponse
    {
        // Sync unsubscribe before deletion
        $this->newsletterService->sync($newsletter, true);

        $newsletter->delete();

        return redirect()->route('admin.newsletter.index')
            ->with('success', 'Subscriber removed successfully.');
    }

    public function export(): StreamedResponse
    {
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="newsletter_subscribers_'.date('Y-m-d').'.csv"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');

            // Add UTF-8 BOM for proper Excel encoding
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Write Headers
            fputcsv($file, ['ID', 'Email', 'Status', 'Subscribed At', 'Unsubscribed At', 'Created At']);

            NewsletterSubscriber::chunk(100, function ($subscribers) use ($file) {
                foreach ($subscribers as $sub) {
                    fputcsv($file, [
                        $sub->id,
                        $sub->email,
                        $sub->status,
                        $sub->subscribed_at?->toDateTimeString() ?? 'N/A',
                        $sub->unsubscribed_at?->toDateTimeString() ?? 'N/A',
                        $sub->created_at?->toDateTimeString(),
                    ]);
                }
            });

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
