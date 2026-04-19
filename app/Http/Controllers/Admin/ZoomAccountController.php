<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ZoomAccount;
use App\Models\ZoomMeeting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ZoomAccountController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', ZoomAccount::class);

        $accounts = ZoomAccount::query()
            ->orderByDesc('is_default')
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('admin.settings.zoom-accounts.index', compact('accounts'));
    }

    public function create(): View
    {
        $this->authorize('create', ZoomAccount::class);

        return view('admin.settings.zoom-accounts.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', ZoomAccount::class);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'account_id' => ['required', 'string', 'max:255'],
            'client_id' => ['required', 'string', 'max:255'],
            'client_secret' => ['required', 'string'],
            'host_user_id' => ['required', 'string', 'max:255'],
            'timezone' => ['nullable', 'string', 'max:64'],
            'waiting_room' => ['sometimes', 'boolean'],
            'is_default' => ['sometimes', 'boolean'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        DB::transaction(function () use ($validated, $request) {
            $isDefault = $request->boolean('is_default');
            if ($isDefault) {
                ZoomAccount::query()->update(['is_default' => false]);
            }

            ZoomAccount::query()->create([
                'name' => $validated['name'],
                'account_id' => $validated['account_id'],
                'client_id' => $validated['client_id'],
                'client_secret' => $validated['client_secret'],
                'host_user_id' => $validated['host_user_id'],
                'timezone' => $validated['timezone'] ?? 'Asia/Colombo',
                'waiting_room' => $request->boolean('waiting_room', true),
                'is_default' => $isDefault,
                'is_active' => $request->boolean('is_active', true),
            ]);
        });

        return redirect()
            ->route('admin.settings.zoom-accounts.index')
            ->with('status', __('Zoom account created.'));
    }

    public function edit(ZoomAccount $zoomAccount): View
    {
        $this->authorize('update', $zoomAccount);

        return view('admin.settings.zoom-accounts.edit', compact('zoomAccount'));
    }

    public function update(Request $request, ZoomAccount $zoomAccount): RedirectResponse
    {
        $this->authorize('update', $zoomAccount);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'account_id' => ['required', 'string', 'max:255'],
            'client_id' => ['required', 'string', 'max:255'],
            'client_secret' => ['nullable', 'string'],
            'host_user_id' => ['required', 'string', 'max:255'],
            'timezone' => ['nullable', 'string', 'max:64'],
            'waiting_room' => ['sometimes', 'boolean'],
            'is_default' => ['sometimes', 'boolean'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        if (array_key_exists('client_secret', $validated) && $validated['client_secret'] === '') {
            unset($validated['client_secret']);
        }

        DB::transaction(function () use ($validated, $request, $zoomAccount) {
            if ($request->boolean('is_default')) {
                ZoomAccount::query()->whereKeyNot($zoomAccount->id)->update(['is_default' => false]);
            }

            $payload = [
                'name' => $validated['name'],
                'account_id' => $validated['account_id'],
                'client_id' => $validated['client_id'],
                'host_user_id' => $validated['host_user_id'],
                'timezone' => $validated['timezone'] ?? $zoomAccount->timezone,
                'waiting_room' => $request->boolean('waiting_room', $zoomAccount->waiting_room),
                'is_default' => $request->boolean('is_default', $zoomAccount->is_default),
                'is_active' => $request->boolean('is_active', $zoomAccount->is_active),
            ];
            if (isset($validated['client_secret'])) {
                $payload['client_secret'] = $validated['client_secret'];
            }
            $zoomAccount->update($payload);
        });

        return redirect()
            ->route('admin.settings.zoom-accounts.index')
            ->with('status', __('Zoom account updated.'));
    }

    public function destroy(ZoomAccount $zoomAccount): RedirectResponse
    {
        $this->authorize('delete', $zoomAccount);

        if (ZoomMeeting::query()->where('zoom_account_id', $zoomAccount->id)->exists()) {
            return redirect()
                ->route('admin.settings.zoom-accounts.index')
                ->with('warning', __('Cannot delete a Zoom account that is linked to meetings.'));
        }

        $zoomAccount->delete();

        return redirect()
            ->route('admin.settings.zoom-accounts.index')
            ->with('status', __('Zoom account deleted.'));
    }

    public function makeDefault(ZoomAccount $zoomAccount): RedirectResponse
    {
        $this->authorize('update', $zoomAccount);

        DB::transaction(function () use ($zoomAccount) {
            ZoomAccount::query()->whereKeyNot($zoomAccount->id)->update(['is_default' => false]);
            $zoomAccount->update([
                'is_default' => true,
                'is_active' => true,
            ]);
        });

        return back()->with('status', __('Default Zoom account updated.'));
    }

    public function activate(ZoomAccount $zoomAccount): RedirectResponse
    {
        $this->authorize('update', $zoomAccount);
        $zoomAccount->update(['is_active' => true]);

        return back()->with('status', __('Zoom account activated.'));
    }

    public function deactivate(ZoomAccount $zoomAccount): RedirectResponse
    {
        $this->authorize('update', $zoomAccount);
        $zoomAccount->update(['is_active' => false]);

        return back()->with('status', __('Zoom account deactivated.'));
    }
}
