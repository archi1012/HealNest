@extends('layouts.app')
@section('title', 'Messages – HealNest')
@section('page-title', 'Messages')

@section('content')
<div class="grid lg:grid-cols-3 gap-6 h-full min-h-[680px]">
    <aside class="bg-white rounded-2xl shadow-sm border border-tan/20 p-4 overflow-y-auto">
        <div class="mb-4">
            <h3 class="font-heading text-forest font-semibold text-lg">Conversations</h3>
            <p class="text-xs text-gray-500 mt-1">Message a counselor or reply to existing chats.</p>
        </div>

        <div class="space-y-2">
            @forelse($threads as $thread)
                     <a href="{{ route('messages.index', ['with' => $thread['partner']->_id]) }}"
                         class="block rounded-xl border px-3 py-3 transition-colors {{ (string) $thread['partner']->_id === $activePartnerId ? 'border-midgreen bg-cream shadow-sm ring-1 ring-midgreen/20' : 'border-tan/20 hover:border-midgreen/40 hover:bg-cream/50' }}">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 rounded-full bg-midgreen flex items-center justify-center text-white font-bold shrink-0">
                            {{ strtoupper(substr($thread['partner']->name, 0, 1)) }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="flex items-start justify-between gap-2">
                                <div class="min-w-0">
                                    <p class="font-medium text-forest truncate">{{ $thread['partner']->name }}</p>
                                    <p class="text-xs text-gray-500">{{ ucfirst($thread['partner']->role) }}</p>
                                </div>
                                @if(($thread['unread_count'] ?? 0) > 0)
                                    <span class="text-xs bg-red-500 text-white rounded-full px-2 py-0.5 shrink-0">{{ $thread['unread_count'] }}</span>
                                @endif
                            </div>
                            <p class="text-sm text-gray-600 mt-2 max-h-10 overflow-hidden">{{ \Illuminate\Support\Str::limit($thread['latest_message']->body ?? '', 70) }}</p>
                        </div>
                    </div>
                </a>
            @empty
                <div class="text-center py-10 text-gray-400 text-sm">
                    No conversations yet.
                </div>
            @endforelse
        </div>
    </aside>

    <section class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-tan/20 flex flex-col overflow-hidden">
        @if($activePartner)
            <div class="border-b border-tan/20 px-6 py-4 flex items-center justify-between gap-4 bg-white sticky top-0 z-10">
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-full bg-midgreen flex items-center justify-center text-white font-bold">
                        {{ strtoupper(substr($activePartner->name, 0, 1)) }}
                    </div>
                    <div>
                        <h3 class="font-heading text-forest font-semibold text-lg">{{ $activePartner->name }}</h3>
                        <p class="text-xs text-gray-500">{{ ucfirst($activePartner->role) }}</p>
                    </div>
                </div>
                <a href="{{ route('messages.index') }}" class="text-sm text-midgreen hover:underline">Clear selection</a>
            </div>

            <div class="flex-1 overflow-y-auto p-6 space-y-4 bg-cream/40">
                @forelse($messages as $message)
                    <div class="flex {{ (string) $message->sender_id === (string) auth()->id() ? 'justify-end' : 'justify-start' }}">
                        <div class="flex items-end gap-2 max-w-[82%] {{ (string) $message->sender_id === (string) auth()->id() ? 'flex-row-reverse' : '' }}">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-bold shrink-0 {{ (string) $message->sender_id === (string) auth()->id() ? 'bg-forest' : 'bg-midgreen' }}">
                                {{ (string) $message->sender_id === (string) auth()->id() ? 'Me' : strtoupper(substr($activePartner->name, 0, 1)) }}
                            </div>
                            <div class="rounded-2xl px-4 py-3 shadow-sm {{ (string) $message->sender_id === (string) auth()->id() ? 'bg-forest text-white rounded-br-md' : 'bg-white text-gray-700 border border-tan/20 rounded-bl-md' }}">
                                <p class="text-sm leading-6 whitespace-pre-wrap">{{ $message->body }}</p>
                                <div class="mt-2 flex items-center justify-between gap-3 text-[11px] {{ (string) $message->sender_id === (string) auth()->id() ? 'text-white/70' : 'text-gray-400' }}">
                                    <span>{{ optional($message->created_at)->format('M d, h:i A') }}</span>
                                    @if((string) $message->sender_id === (string) auth()->id())
                                        <span>{{ $message->read_at ? 'Seen' : 'Sent' }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-gray-400 text-sm py-16">
                        <p class="text-3xl mb-2">💬</p>
                        <p class="font-medium text-gray-500">Start the conversation below.</p>
                        <p class="mt-1 text-xs">You can keep this chat focused on one issue at a time.</p>
                    </div>
                @endforelse
            </div>

            <form method="POST" action="{{ route('messages.store') }}" class="border-t border-tan/20 p-4 space-y-3 bg-white sticky bottom-0">
                @csrf
                <input type="hidden" name="recipient_id" value="{{ $activePartnerId }}">
                <div class="rounded-2xl border border-tan/30 bg-cream p-3">
                    <textarea name="body" rows="3" required placeholder="Type your message..."
                              class="w-full bg-transparent border-0 focus:ring-0 p-0 text-sm resize-none placeholder:text-gray-400"></textarea>
                    <div class="flex items-center justify-between gap-3 mt-3">
                        <p class="text-[11px] text-gray-500">Press send when you're ready.</p>
                        <button type="submit" class="bg-forest text-white px-5 py-2.5 rounded-lg text-sm font-semibold hover:bg-midgreen transition-colors shadow-sm">
                            Send Message
                        </button>
                    </div>
                </div>
            </form>
        @else
            <div class="flex-1 flex items-center justify-center p-8 text-center">
                <div>
                    <p class="text-4xl mb-3">🌿</p>
                    <h3 class="font-heading text-forest text-xl font-semibold">No active conversation</h3>
                    <p class="text-gray-500 mt-2">Choose a counselor from the left to begin chatting.</p>
                    <p class="text-xs text-gray-400 mt-2">Your messages stay organized by conversation.</p>
                </div>
            </div>
        @endif
    </section>
</div>
@endsection