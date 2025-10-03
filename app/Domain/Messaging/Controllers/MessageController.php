<?php

namespace App\Domain\Messaging\Controllers;

use App\Domain\Messaging\Jobs\SendMessageJob;
use App\Domain\Messaging\Models\Conversation;
use App\Domain\Messaging\Models\Message;
use App\Domain\Messaging\Models\ScheduledMessage;
use App\Domain\Messaging\Requests\SendMessageRequest;
use Illuminate\Support\Facades\DB;

class MessageController
{
    /**
     * Store a new outbound message in a conversation
     */
    public function store(SendMessageRequest $request, Conversation $conversation)
    {
        $this->authorize('reply', $conversation);

        $user = $request->user();

        // Handle scheduled messages
        if ($request->filled('send_at')) {
            ScheduledMessage::create([
                'conversation_id' => $conversation->id,
                'contact_id' => $conversation->contact_id,
                'user_id' => $user->id,
                'channel' => $conversation->channel,
                'body' => $request->string('body'),
                'send_at' => \Carbon\Carbon::parse($request->input('send_at')),
                'status' => 'scheduled',
                'meta' => [],
            ]);

            return back()->with('status', 'Message scheduled.');
        }

        // Immediate message creation within transaction
        $message = DB::transaction(function () use ($request, $conversation, $user) {
            $msg = Message::create([
                'conversation_id' => $conversation->id,
                'contact_id' => $conversation->contact_id,
                'user_id' => $user->id,
                'channel' => $conversation->channel,
                'direction' => 'outbound',
                'body' => $request->string('body'),
                'status' => 'queued',
                'meta' => [],
            ]);

            // Handle attachments
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store("attachments/{$conversation->id}", 'public');

                    $msg->meta = array_merge($msg->meta ?? [], [
                        'attachments' => array_merge($msg->meta['attachments'] ?? [], [[
                            'path' => $path,
                            'name' => $file->getClientOriginalName(),
                            'mime' => $file->getMimeType(),
                            'size' => $file->getSize(),
                        ]]),
                    ]);
                    $msg->save();
                }
            }

            // Update conversation timestamp & status
            $conversation->forceFill([
                'last_message_at' => now(),
                'status' => 'open',
            ])->save();

            return $msg;
        });

        // Queue the outbound sending job
        dispatch(new SendMessageJob($message->id))->onQueue('outbound');

        return redirect()
            ->route('inbox.conversation', $conversation)
            ->with('status', 'Message queued for delivery.');
    }
}
