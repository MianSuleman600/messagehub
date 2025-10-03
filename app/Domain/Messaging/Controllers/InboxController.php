<?php

namespace App\Domain\Messaging\Controllers;

use App\Domain\Messaging\Models\Conversation;
use App\Domain\Messaging\Queries\MessageQueries;
use Illuminate\Http\Request;

class InboxController
{
    /**
     * Display a list of conversations for the authenticated user
     */
    public function index(Request $request)
    {
        $filters = $request->only(['channel', 'status', 'assignee', 'q']);
        $conversations = MessageQueries::conversationIndex($request->user(), $filters);

        $channels = ['whatsapp', 'messenger', 'instagram', 'email', 'sms'];
        $statuses = ['open', 'pending', 'closed'];
        $assignees = ['all', 'me', 'unassigned'];

        return view('inbox.index', compact(
            'conversations',
            'filters',
            'channels',
            'statuses',
            'assignees'
        ));
    }

    /**
     * Display a single conversation and its messages
     */
    public function show(Request $request, Conversation $conversation)
    {
        $this->authorize('view', $conversation);

        $messages = MessageQueries::conversationThread($conversation);

        return view('inbox.conversation', [
            'conversation' => $conversation->load('contact:id,name,handle,email', 'assignee:id,name'),
            'messages' => $messages,
        ]);
    }
}
