<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inquiry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class InquiryController extends Controller
{
    /**
     * Display a listing of captured inquiries.
     */
    public function index(Request $request): InertiaResponse
    {
        $status = $request->input('status');

        $inquiries = Inquiry::latest()
            ->when($status, function ($query, $st) {
                $query->where('status', $st);
            })
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Admin/Inquiries', [
            'inquiries' => $inquiries,
            'filters' => $request->only(['status']),
        ]);
    }

    /**
     * Update the status of an inquiry.
     */
    public function update(Request $request, Inquiry $inquiry): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:new,processing,resolved',
        ]);

        $inquiry->update([
            'status' => $validated['status'],
        ]);

        return redirect()->back()->with('success', 'Inquiry status updated successfully.');
    }

    /**
     * Delete an inquiry.
     */
    public function destroy(Inquiry $inquiry): RedirectResponse
    {
        $inquiry->delete();

        return redirect()->back()->with('success', 'Inquiry deleted successfully.');
    }

    /**
     * Export all inquiries as a CSV document.
     */
    public function exportCsv(): StreamedResponse
    {
        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="inquiries_export_'.now()->format('Y-m-d').'.csv"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $columns = ['ID', 'Name', 'Email', 'Phone', 'Company', 'Subject', 'Message', 'Status', 'Source', 'Created At'];

        $callback = function () use ($columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            Inquiry::chunk(100, function ($inquiries) use ($file) {
                foreach ($inquiries as $inquiry) {
                    fputcsv($file, [
                        $inquiry->id,
                        $inquiry->name,
                        $inquiry->email,
                        $inquiry->phone,
                        $inquiry->company,
                        $inquiry->subject,
                        $inquiry->message,
                        $inquiry->status,
                        $inquiry->source,
                        $inquiry->created_at ? $inquiry->created_at->toDateTimeString() : '',
                    ]);
                }
            });

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
