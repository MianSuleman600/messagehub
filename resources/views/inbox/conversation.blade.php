@extends('layouts.app')

@section('content')
<div class="flex justify-between items-center mb-4">
    <h2 class="text-2xl font-bold">Conversation</h2>
    <div class="flex items-center space-x-4">
        <div class="text-gray-400">#{{ $conversation->id }} · {{ ucfirst($conversation->channel) }}</div>

        {{-- Assignment form (Admin only) --}}
        @role('Admin')
        <form method="POST" action="{{ route('inbox.conversation.assign', $conversation) }}" class="flex items-center space-x-2">
            @csrf
            <select name="user_id" class="input">
                <option value="">Unassigned</option>
                @foreach(\App\Domain\Users\Models\User::orderBy('name')->get(['id','name']) as $u)
                    <option value="{{ $u->id }}" @selected($conversation->assigned_to === $u->id)>{{ $u->name }}</option>
                @endforeach
            </select>
            <button class="btn secondary" type="submit">Assign</button>
        </form>
        @endrole

        {{-- Status update form --}}
        <form method="POST" action="{{ route('inbox.conversation.status', $conversation) }}" class="flex items-center space-x-2">
            @csrf
            @method('PATCH')
            <select name="status" class="input">
                @foreach(['open','pending','closed'] as $st)
                    <option value="{{ $st }}" @selected($conversation->status === $st)>{{ ucfirst($st) }}</option>
                @endforeach
            </select>
            <button class="btn secondary" type="submit">Update</button>
        </form>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    {{-- Messages & reply --}}
    <div class="bg-gray-800 p-4 rounded-lg shadow-md flex flex-col">
        <div class="flex justify-between items-start">
            <div>
                <strong>{{ $conversation->contact->name ?? 'Unknown' }}</strong>
                <div class="text-gray-400 text-sm">Assigned: {{ $conversation->assignee?->name ?? 'Unassigned' }}</div>
            </div>
            <span class="px-2 py-1 bg-gray-700 text-gray-200 rounded-full text-sm">{{ ucfirst($conversation->status) }}</span>
        </div>

        {{-- Message thread --}}
        <div class="mt-4 overflow-y-auto flex-1 space-y-3" id="thread" style="max-height:60vh;">
            @foreach($messages as $msg)
                <div class="flex {{ $msg->direction === 'outbound' ? 'justify-end' : 'justify-start' }}">
                    <div class="p-3 rounded-xl max-w-[75%] {{ $msg->direction === 'outbound' ? 'bg-green-700 text-white' : 'bg-gray-700 text-gray-100' }}">
                        <div class="text-gray-400 text-xs mb-1">
                            {{ $msg->direction === 'outbound' ? 'You' : ($conversation->contact->name ?? 'Contact') }} · {{ $msg->created_at->format('d M, H:i') }}
                        </div>
                        <div>{!! nl2br(e($msg->body)) !!}</div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Reply form --}}
        @can('reply', $conversation)
        <form method="POST" action="{{ route('inbox.message.store', $conversation) }}" enctype="multipart/form-data" class="mt-4 flex flex-col gap-2">
            @csrf
            <textarea name="body" rows="3" placeholder="Write a reply..." required class="input"></textarea>
            @error('body')<div class="text-red-400 text-sm">{{ $message }}</div>@enderror

            {{-- Schedule message --}}
            <div class="row mt-2" style="justify-content:space-between">
                <div class="row" style="gap:.5rem">
                    <label class="muted text-sm">Schedule</label>
                    <input class="input" type="datetime-local" name="send_at">
                </div>
                <button class="btn" type="submit">Send</button>
            </div>

            {{-- Attachments --}}
            <div class="flex flex-col mt-2">
                <input type="file" name="attachments[]" multiple class="text-gray-300 text-sm"/>
                <span class="text-gray-400 text-xs mt-1">Max 20MB each</span>
            </div>
        </form>
        @else
        <div class="text-gray-400 mt-2">You cannot reply to this conversation (not assigned to you).</div>
        @endcan
    </div>

    {{-- Contact info --}}
    <div class="bg-gray-800 p-4 rounded-lg shadow-md">
        <h3 class="text-lg font-semibold mb-2">Contact</h3>
        <div>
            <div><strong>{{ $conversation->contact->name ?? 'Unknown' }}</strong></div>
            <div class="text-gray-400 text-sm">
                {{ $conversation->contact->email ?? '—' }}
                {{ $conversation->contact->phone ? '· '.$conversation->contact->phone : '' }}
            </div>
        </div>

        <div class="mt-4">
            <div class="text-gray-400 text-sm">Channel</div>
            <div>{{ ucfirst($conversation->channel) }}</div>
        </div>

        <div class="mt-4">
            <div class="text-gray-400 text-sm">Last message</div>
            <div>{{ optional($conversation->last_message_at)->diffForHumans() ?: '—' }}</div>
        </div>
    </div>
</div>

<script>
const el = document.getElementById('thread');
if (el) el.scrollTop = el.scrollHeight;
</script>
@endsection
