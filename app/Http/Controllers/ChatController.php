<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        
        // Retrieve all chats involving the current user
        $chats = Chat::where('sender_id', $userId)
            ->orWhere('receiver_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        $rooms = [];
        foreach ($chats as $chat) {
            $otherUserId = $chat->sender_id == $userId ? $chat->receiver_id : $chat->sender_id;
            if (!isset($rooms[$otherUserId])) {
                $rooms[$otherUserId] = [
                    'user' => User::find($otherUserId),
                    'last_message' => $chat->message,
                    'time' => $chat->created_at,
                ];
            }
        }

        return view('chat.index', compact('rooms'));
    }

    public function show($userId, Request $request)
    {
        $otherUser = User::findOrFail($userId);
        $myId = Auth::id();
        
        // Find chats between myId and otherUser
        $messages = Chat::where(function($q) use ($myId, $userId) {
            $q->where('sender_id', $myId)->where('receiver_id', $userId);
        })->orWhere(function($q) use ($myId, $userId) {
            $q->where('sender_id', $userId)->where('receiver_id', $myId);
        })->orderBy('created_at', 'asc')->get();

        // Check if there is an item context
        $product = null;
        if ($request->has('product_id')) {
            $product = Product::find($request->product_id);
        }

        return view('chat.room', compact('otherUser', 'messages', 'product'));
    }

    public function send(Request $request, $userId)
    {
        $request->validate([
            'message' => 'required|string',
            'product_id' => 'nullable|exists:products,id',
        ]);

        Chat::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $userId,
            'product_id' => $request->product_id,
            'message' => $request->message,
        ]);

        return redirect()->route('chat.show', ['userId' => $userId, 'product_id' => $request->product_id]);
    }
}
