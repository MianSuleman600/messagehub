<?php

namespace App\Domain\Messaging\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Domain\Messaging\Models\ChannelAccount;
use Illuminate\Validation\Rule;

enum ChannelType: string
{
    case WHATSAPP   = 'whatsapp';
    case TELEGRAM   = 'telegram';
    case SMS        = 'sms';
    case MESSENGER  = 'messenger';
    case INSTAGRAM  = 'instagram';
    case TIKTOK     = 'tiktok';
    case EMAIL      = 'email';
}

class ChannelSettingsController extends Controller
{
    /**
     * Display all channel accounts and supported types.
     */
    public function index()
    {
        $accounts = ChannelAccount::latest()->get();
        $channelTypes = array_map(fn($c) => $c->value, ChannelType::cases());

        return view('settings.channels', compact('accounts', 'channelTypes'));
    }

    /**
     * Store a new channel account.
     */
    public function store(Request $request)
    {
        $allowedTypes = array_map(fn($c) => $c->value, ChannelType::cases());

        $data = $request->validate([
            'provider'         => ['required', 'string', 'max:50'],
            'name'             => ['required', 'string', 'max:120'],
            'type'             => ['required', Rule::in($allowedTypes)],
            'credentials_json' => ['nullable', 'string'], // allow JSON string
        ]);

        $credentials = [];
        if (!empty($data['credentials_json'])) {
            $decoded = json_decode($data['credentials_json'], true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return back()->withErrors(['credentials_json' => 'Credentials must be valid JSON.'])->withInput();
            }
            $credentials = $decoded;
        }

        ChannelAccount::create([
            'provider'    => $data['provider'],
            'name'        => $data['name'],
            'type'        => $data['type'],
            'external_id' => $credentials['external_id'] ?? null,
            'is_active'   => true,
            'credentials' => $credentials,
        ]);

        return back()->with('status', 'Channel connected.');
    }

    /**
     * Update an existing channel account.
     */
    public function update(Request $request, ChannelAccount $channelAccount)
    {
        $allowedTypes = array_map(fn($c) => $c->value, ChannelType::cases());

        $data = $request->validate([
            'name'             => ['required', 'string', 'max:120'],
            'type'             => ['required', Rule::in($allowedTypes)],
            'is_active'        => ['nullable', 'boolean'],
            'credentials_json' => ['nullable', 'string'],
        ]);

        $credentials = $channelAccount->credentials ?? [];
        if (isset($data['credentials_json'])) {
            $decoded = json_decode($data['credentials_json'], true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return back()->withErrors(['credentials_json' => 'Credentials must be valid JSON.'])->withInput();
            }
            $credentials = $decoded;
        }

        $channelAccount->update([
            'name'        => $data['name'],
            'type'        => $data['type'],
            'is_active'   => $data['is_active'] ?? $channelAccount->is_active,
            'credentials' => $credentials,
        ]);

        return back()->with('status', 'Channel updated.');
    }

    /**
     * Toggle the active status of a channel account.
     */
    public function toggle(ChannelAccount $channelAccount)
    {
        $channelAccount->update(['is_active' => ! $channelAccount->is_active]);
        return back()->with('status', $channelAccount->is_active ? 'Channel activated.' : 'Channel deactivated.');
    }

    /**
     * Delete a channel account.
     */
    public function destroy(ChannelAccount $channelAccount)
    {
        $channelAccount->delete();
        return back()->with('status', 'Channel removed.');
    }
}
