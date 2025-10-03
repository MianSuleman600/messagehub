<?php

namespace App\Domain\Messaging\Controllers;

use App\Domain\Messaging\Actions\AssignConversation;
use App\Domain\Messaging\Models\Conversation;
use App\Domain\Users\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ConversationController extends Controller
{
    /**
     * Assign a conversation to a staff member.
     */
    public function assign(Request $request, Conversation $conversation, AssignConversation $assign)
    {
        $request->validate([
            'user_id' => ['nullable', 'exists:users,id'],
        ]);

        $this->authorize('view', $conversation);

        $assignee = $request->filled('user_id') 
            ? User::find($request->input('user_id')) 
            : null;

        $assign->handle($conversation, $assignee, $request->user());

        return back()->with('status', 'Conversation assignment updated.');
    }

    /**
     * Update the status of a conversation.
     */
    public function updateStatus(Request $request, Conversation $conversation)
    {
        $request->validate([
            'status' => ['required', 'in:open,pending,closed'],
        ]);

        $this->authorize('view', $conversation);

        $conversation->status = $request->input('status');
        $conversation->save();

        return back()->with('status', 'Conversation status updated.');
    }
}
