<?php

namespace App\Integrations\OAuth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Integrations\Channels\WhatsApp\WhatsAppService;
use App\Domain\Messaging\Models\ChannelAccount;

class WhatsAppConnectController extends Controller
{
    public function __construct(protected WhatsAppService $wa) {}

    /**
     * Display available WhatsApp Business Account (WABA) phone numbers.
     * Admin-only view to attach numbers as channels.
     */
    public function index()
    {
        $numbers = $this->wa->listPhoneNumbers(); // Fetch numbers via service
        $connected = ChannelAccount::where('type', 'whatsapp')
            ->pluck('external_id')
            ->all();

        return view('settings.connect-whatsapp', [
            'numbers'   => $numbers,
            'connected' => $connected,
        ]);
    }

    /**
     * Attach a WABA phone number to the system as a ChannelAccount.
     */
    public function connect(Request $request)
    {
        $validated = $request->validate([
            'phone_number_id' => ['required', 'string'],
        ]);

        $details = $this->wa->numberDetails($validated['phone_number_id']);
        if (!($details['id'] ?? null)) {
            return back()->withErrors(['whatsapp' => 'Invalid phone number ID.']);
        }

        $name = 'WhatsApp ' . ($details['display_phone_number'] ?? 'Number');

        ChannelAccount::updateOrCreate(
            ['type' => 'whatsapp', 'external_id' => $details['id']],
            [
                'name'        => $name,
                'is_active'   => true,
                'credentials' => [
                    'provider'        => 'meta',
                    'token_source'    => 'env', // token used from environment; not stored
                    'waba_id'         => config('services.whatsapp.business_id'),
                    'phone_number_id' => $details['id'],
                    'display_phone'   => $details['display_phone_number'] ?? null,
                    'verified_name'   => $details['verified_name'] ?? null,
                ],
            ]
        );

        return redirect()->route('settings.channels')
            ->with('status', 'WhatsApp number connected successfully.');
    }
}
