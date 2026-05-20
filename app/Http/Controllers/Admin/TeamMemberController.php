<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TeamMember;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class TeamMemberController extends Controller
{
    public function index(Request $request): Response
    {
        $members = TeamMember::orderBy('order')
            ->when($request->input('search'), function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('position', 'like', "%{$search}%");
            })
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Admin/TeamMembers/Index', [
            'members' => $members,
            'filters' => $request->only(['search']),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'bio' => 'nullable|string|max:1000',
            'order' => 'required|integer|min:0',
            'is_active' => 'required|boolean',
            'avatar' => 'nullable|image|max:2048',
            'social_links' => 'nullable|array',
            'social_links.*.platform' => 'required|string|max:50',
            'social_links.*.url' => 'required|url|max:255',
        ]);

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('team', 'public');
            $validated['avatar_path'] = asset('storage/'.$path);
        }

        unset($validated['avatar']);
        TeamMember::create($validated);

        return redirect()->route('admin.team-members.index')
            ->with('success', 'Team member added successfully.');
    }

    public function update(Request $request, TeamMember $teamMember): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'bio' => 'nullable|string|max:1000',
            'order' => 'required|integer|min:0',
            'is_active' => 'required|boolean',
            'avatar' => 'nullable|image|max:2048',
            'social_links' => 'nullable|array',
            'social_links.*.platform' => 'required|string|max:50',
            'social_links.*.url' => 'required|url|max:255',
        ]);

        if ($request->hasFile('avatar')) {
            // Delete old avatar if it exists
            if ($teamMember->avatar_path) {
                $oldPath = str_replace(asset('storage/'), '', $teamMember->avatar_path);
                Storage::disk('public')->delete($oldPath);
            }

            $path = $request->file('avatar')->store('team', 'public');
            $validated['avatar_path'] = asset('storage/'.$path);
        }

        unset($validated['avatar']);
        $teamMember->update($validated);

        return redirect()->route('admin.team-members.index')
            ->with('success', 'Team member updated successfully.');
    }

    public function destroy(TeamMember $teamMember): RedirectResponse
    {
        // Delete avatar file
        if ($teamMember->avatar_path) {
            $oldPath = str_replace(asset('storage/'), '', $teamMember->avatar_path);
            Storage::disk('public')->delete($oldPath);
        }

        $teamMember->delete();

        return redirect()->route('admin.team-members.index')
            ->with('success', 'Team member deleted successfully.');
    }
}
