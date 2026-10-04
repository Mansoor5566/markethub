<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Listing;
use App\Models\Message;
use App\Http\Requests\SendMessageRequest;

class MessageController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        // Get latest message per conversation
        $conversations = Message::with(['sender', 'receiver', 'listing'])
            ->where('sender_id', $userId)
            ->orWhere('receiver_id', $userId)
            ->latest()
            ->get()
            ->unique(function ($msg) use ($userId) {
                $otherId = $msg->sender_id === $userId ? $msg->receiver_id : $msg->sender_id;
                return $otherId . '_' . $msg->listing_id;
            });

        return view('messages.index', compact('conversations'));
    }

    public function show(User $user, Listing $listing)
    {
        $userId = auth()->id();

        // Mark received messages as read
        Message::where('sender_id', $user->id)
            ->where('receiver_id', $userId)
            ->where('listing_id', $listing->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $messages = Message::where('listing_id', $listing->id)
            ->where(function ($q) use ($userId, $user) {
                $q->where(function ($q2) use ($userId, $user) {
                    $q2->where('sender_id', $userId)->where('receiver_id', $user->id);
                })->orWhere(function ($q2) use ($userId, $user) {
                    $q2->where('sender_id', $user->id)->where('receiver_id', $userId);
                });
            })
            ->oldest()
            ->get();

        $otherUser = $user;
        return view('messages.show', compact('messages', 'otherUser', 'listing'));
    }

    public function send(SendMessageRequest $request, User $user, Listing $listing)
    {
        // Cannot message yourself
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot message yourself.');
        }

        Message::create([
            'sender_id'   => auth()->id(),
            'receiver_id' => $user->id,
            'listing_id'  => $listing->id,
            'body'        => $request->body,
            'is_read'     => false,
        ]);

        return back()->with('success', 'Message sent.');
    }
}