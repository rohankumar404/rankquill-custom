<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Portfolio;
use App\Models\Service;
use App\Models\Testimonial;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class TestimonialController extends Controller
{
    public function index(Request $request): Response
    {
        $testimonials = Testimonial::with(['portfolio', 'service'])
            ->latest()
            ->when($request->input('search'), function ($query, $search) {
                $query->where('client_name', 'like', "%{$search}%")
                    ->orWhere('client_company', 'like', "%{$search}%")
                    ->orWhere('testimonial', 'like', "%{$search}%");
            })
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Admin/Testimonials/Index', [
            'testimonials' => $testimonials,
            'portfolios' => Portfolio::all(['id', 'title']),
            'services' => Service::all(['id', 'title']),
            'filters' => $request->only(['search']),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'client_name' => 'required|string|max:255',
            'client_position' => 'nullable|string|max:255',
            'client_company' => 'nullable|string|max:255',
            'testimonial' => 'required|string|max:2000',
            'rating' => 'required|integer|min:1|max:5',
            'portfolio_id' => 'nullable|exists:portfolios,id',
            'service_id' => 'nullable|exists:services,id',
            'is_featured' => 'required|boolean',
            'avatar' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('testimonials', 'public');
            $validated['avatar_path'] = asset('storage/'.$path);
        }

        unset($validated['avatar']);
        Testimonial::create($validated);

        return redirect()->route('admin.testimonials.index')
            ->with('success', 'Testimonial created successfully.');
    }

    public function update(Request $request, Testimonial $testimonial): RedirectResponse
    {
        $validated = $request->validate([
            'client_name' => 'required|string|max:255',
            'client_position' => 'nullable|string|max:255',
            'client_company' => 'nullable|string|max:255',
            'testimonial' => 'required|string|max:2000',
            'rating' => 'required|integer|min:1|max:5',
            'portfolio_id' => 'nullable|exists:portfolios,id',
            'service_id' => 'nullable|exists:services,id',
            'is_featured' => 'required|boolean',
            'avatar' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('avatar')) {
            // Delete old avatar if it exists
            if ($testimonial->avatar_path) {
                $oldPath = str_replace(asset('storage/'), '', $testimonial->avatar_path);
                Storage::disk('public')->delete($oldPath);
            }

            $path = $request->file('avatar')->store('testimonials', 'public');
            $validated['avatar_path'] = asset('storage/'.$path);
        }

        unset($validated['avatar']);
        $testimonial->update($validated);

        return redirect()->route('admin.testimonials.index')
            ->with('success', 'Testimonial updated successfully.');
    }

    public function destroy(Testimonial $testimonial): RedirectResponse
    {
        // Delete avatar file
        if ($testimonial->avatar_path) {
            $oldPath = str_replace(asset('storage/'), '', $testimonial->avatar_path);
            Storage::disk('public')->delete($oldPath);
        }

        $testimonial->delete();

        return redirect()->route('admin.testimonials.index')
            ->with('success', 'Testimonial deleted successfully.');
    }
}
