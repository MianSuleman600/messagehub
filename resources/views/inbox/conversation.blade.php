@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto text-gray-100">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
        <h2 class="text-2xl font-semibold tracking-tight">Conversation</h2>
        <div class="flex flex-wrap items-center gap-3 text-sm">
            <div class="text-gray-400">#{{ $conversation->id }} · {{ ucfirst($conversation->channel) }}</div>

            {{-- Assignment form (Admin only) --}}
            @role('Admin')
            <form method="POST" action="{{ route('inbox.conversation.assign', $conversation) }}" class="flex items-center gap-2">
                @csrf
                <select name="user_id" class="px-3 py-2 rounded-lg bg-gray-900/80 border border-gray-800 text-gray-100 focus:ring-2 focus:ring-blue-500/40 focus:border-blue-600">
                    <option value="">Unassigned</option>
                    @foreach(\App\Domain\Users\Models\User::orderBy('name')->get(['id','name']) as $u)
                        <option value="{{ $u->id }}" @selected($conversation->assigned_to === $u->id)>{{ $u->name }}</option>
                    @endforeach
                </select>
                <button class="px-3 py-2 rounded-lg bg-gray-800 hover:bg-gray-700 text-gray-100 transition-colors" type="submit">Assign</button>
            </form
            >@endrole

            {{-- Status update form --}}
            <form method="POST" action="{{ route('inbox.conversation.status', $conversation) }}" class="flex items-center gap-2">
                @csrf
                @method('PATCH')
                <select name="status" class="px-3 py-2 rounded-lg bg-gray-900/80 border border-gray-800 text-gray-100 focus:ring-2 focus:ring-blue-500/40 focus:border-blue-600">
                    @foreach(['open','pending','closed'] as $st)
                        <option value="{{ $st }}" @selected($conversation->status === $st)>{{ ucfirst($st) }}</option>
                    @endforeach
                </select>
                <button class="px-3 py-2 rounded-lg bg-gray-800 hover:bg-gray-700 text-gray-100 transition-colors" type="submit">Update</button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Messages & reply --}}
        <div class="bg-gray-950/60 border border-gray-800 rounded-2xl p-5 shadow-lg flex flex-col">
            <div class="flex justify-between items-start">
                <div>
                    <strong class="text-white">{{ $conversation->contact->name ?? 'Unknown' }}</strong>
                    <div class="text-gray-400 text-sm">Assigned: {{ $conversation->assignee?->name ?? 'Unassigned' }}</div>
                </div>
                <span class="px-2 py-1 bg-gray-900/60 border border-gray-800 text-gray-200 rounded-full text-xs">{{ ucfirst($conversation->status) }}</span>
            </div>

            {{-- Message thread --}}
            <div class="mt-4 overflow-y-auto flex-1 space-y-3 pr-1" id="thread" style="max-height:60vh;">
                @foreach($messages as $msg)
                    <div class="flex {{ $msg->direction === 'outbound' ? 'justify-end' : 'justify-start' }}">
                        <div class="p-3 rounded-2xl max-w-[75%] shadow-sm {{ $msg->direction === 'outbound' ? 'bg-green-600 text-white' : 'bg-gray-800 text-gray-100' }}">
                            <div class="text-gray-200/70 text-xs mb-1">
                                {{ $msg->direction === 'outbound' ? 'You' : ($conversation->contact->name ?? 'Contact') }}
                                · {{ $msg->created_at->format('d M, H:i') }}
                            </div>
                            <div>{!! nl2br(e($msg->body)) !!}</div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Reply form --}}
            @can('reply', $conversation)
            <form method="POST" action="{{ route('inbox.message.store', $conversation) }}" enctype="multipart/form-data" class="mt-4 flex flex-col gap-3">
                @csrf
                <textarea name="body" rows="3" placeholder="Write a reply..." required
                          class="w-full bg-gray-900/80 border border-gray-800 rounded-lg px-3 py-2 text-gray-200 placeholder-gray-500 focus:ring-2 focus:ring-blue-500/40 focus:border-blue-600"></textarea>
                @error('body')<div class="text-red-400 text-sm">{{ $message }}</div>@enderror

                {{-- Schedule message --}}
                <div class="flex flex-wrap items-center justify-between gap-2">
                    <div class="flex items-center gap-2 text-sm">
                        <label class="text-gray-400">Schedule</label>
                        <input class="px-3 py-2 rounded-lg bg-gray-900/80 border border-gray-800 text-gray-100 focus:ring-2 focus:ring-blue-500/40 focus:border-blue-600"
                               type="datetime-local" name="send_at">
                    </div>
                    <button class="px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-500 text-white font-semibold transition-colors" type="submit">Send</button>
                </div>

                {{-- Attachments --}}
                <div class="flex flex-col">
                    <input type="file" name="attachments[]" multiple class="text-gray-300 text-sm file:mr-3 file:rounded-md file:border-0 file:bg-gray-800 file:text-gray-200 file:px-3 file:py-2 hover:file:bg-gray-700"/>
                    <span class="text-gray-400 text-xs mt-1">Max 20MB each</span>
                </div>
            </form>
            @else
            <div class="text-gray-400 mt-2">You cannot reply to this conversation (not assigned to you).</div>
            @endcan
        </div>

        {{-- Contact info --}}
        <div class="bg-gray-950/60 border border-gray-800 rounded-2xl p-5 shadow-lg">
            <h3 class="text-lg font-semibold mb-2 text-white">Contact</h3>
            <div>
                <div><strong class="text-white">{{ $conversation->contact->name ?? 'Unknown' }}</strong></div>
                <div class="text-gray-400 text-sm">
                    {{ $conversation->contact->email ?? '—' }}
                    {{ $conversation->contact->phone ? '· '.$conversation->contact->phone : '' }}
                </div>
            </div>

            <div class="mt-4">
                <div class="text-gray-400 text-sm">Channel</div>
                <div class="text-gray-200">{{ ucfirst($conversation->channel) }}</div>
            </div>

            <div class="mt-4">
                <div class="text-gray-400 text-sm">Last message</div>
                <div class="text-gray-200">{{ optional($conversation->last_message_at)->diffForHumans() ?: '—' }}</div>
            </div>
        </div>
    </div>

    <script>
    const el = document.getElementById('thread');
    if (el) el.scrollTop = el.scrollHeight;
    </script>
</div>
@endsection