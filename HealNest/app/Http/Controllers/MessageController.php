<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function index(Request $request)
    {
        $currentUser = Auth::user();
        $currentUserId = (string) $currentUser->_id;
        $contacts = $this->contactsFor($currentUser);
        $activePartnerId = (string) $request->query('with', $contacts->first()?->_id ?? '');
        $activePartner = $activePartnerId !== '' ? User::find($activePartnerId) : null;

        $threads = $this->buildThreads($currentUserId);
        $messages = collect();

        if ($activePartner) {
            $messages = Message::where(function ($query) use ($currentUserId, $activePartnerId) {
                $query->where('sender_id', $currentUserId)->where('recipient_id', $activePartnerId);
            })->orWhere(function ($query) use ($currentUserId, $activePartnerId) {
                $query->where('sender_id', $activePartnerId)->where('recipient_id', $currentUserId);
            })->orderBy('created_at')->get();

            Message::where('sender_id', $activePartnerId)
                ->where('recipient_id', $currentUserId)
                ->whereNull('read_at')
                ->update(['read_at' => now()]);
        }

        return view('messages.index', [
            'contacts' => $contacts,
            'threads' => $threads,
            'activePartner' => $activePartner,
            'activePartnerId' => $activePartnerId,
            'messages' => $messages,
        ]);
    }

    public function store(Request $request)
    {
        $currentUser = Auth::user();

        $validated = $request->validate([
            'recipient_id' => ['required', 'string'],
            'body' => ['required', 'string', 'max:2000'],
        ]);

        $recipient = User::findOrFail($validated['recipient_id']);
        $allowedRecipients = $this->contactsFor($currentUser)->pluck('_id')->map(fn ($id) => (string) $id)->all();

        if (! in_array((string) $recipient->_id, $allowedRecipients, true)) {
            return back()->withErrors(['recipient_id' => 'That recipient is not available for messaging.']);
        }

        Message::create([
            'sender_id' => (string) $currentUser->_id,
            'recipient_id' => (string) $recipient->_id,
            'body' => $validated['body'],
            'read_at' => null,
        ]);

        return redirect()->route('messages.index', ['with' => (string) $recipient->_id])->with('success', 'Message sent.');
    }

    protected function contactsFor(User $currentUser)
    {
        if ($currentUser->isCounselor() || $currentUser->isAdmin()) {
            return User::whereIn('role', ['user', 'parent'])->orderBy('name')->get();
        }

        return User::where('role', 'counselor')->orderBy('name')->get();
    }

    protected function buildThreads(string $currentUserId)
    {
        $messages = Message::where('sender_id', $currentUserId)
            ->orWhere('recipient_id', $currentUserId)
            ->orderBy('created_at', 'desc')
            ->get();

        return $messages->groupBy(function (Message $message) use ($currentUserId) {
            return $message->sender_id === $currentUserId ? $message->recipient_id : $message->sender_id;
        })->map(function ($group, $partnerId) use ($currentUserId) {
            $partner = User::find($partnerId);

            return [
                'partner' => $partner,
                'latest_message' => $group->first(),
                'unread_count' => $group->where('recipient_id', $currentUserId)->whereNull('read_at')->count(),
            ];
        })->filter(fn ($thread) => $thread['partner'] !== null)->sortByDesc(fn ($thread) => optional($thread['latest_message']->created_at)->timestamp ?? 0)->values();
    }
}