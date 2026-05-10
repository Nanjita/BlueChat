<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Throwable;

class ChatController extends Controller
{
    public function index(Request $request)
    {
        $authUser = $request->user();

        $users = User::query()
            ->whereKeyNot($authUser->id)
            ->orderBy('name')
            ->get(['id', 'name', 'email']);

        return view('chat.index', [
            'users' => $users,
        ]);
    }

    public function messages(Request $request, User $user)
    {
        $authUser = $request->user();

        $messages = Message::query()
            ->where(function ($query) use ($authUser, $user) {
                $query
                    ->where('sender_id', $authUser->id)
                    ->where('receiver_id', $user->id);
            })
            ->orWhere(function ($query) use ($authUser, $user) {
                $query
                    ->where('sender_id', $user->id)
                    ->where('receiver_id', $authUser->id);
            })
            ->orderBy('created_at')
            ->get();

        return response()->json([
            'messages' => $messages->map(function (Message $message) {
                return [
                    'id' => $message->id,
                    'sender_id' => $message->sender_id,
                    'receiver_id' => $message->receiver_id,
                    'message' => $message->message,
                    'created_at' => $message->created_at->toISOString(),
                ];
            }),
        ]);
    }

    public function send(Request $request, User $user)
    {
        $authUser = $request->user();

        if ($authUser->id === $user->id) {
            return response()->json([
                'message' => 'Invalid receiver.',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $validated = $request->validate([
            'message' => ['required', 'string', 'max:2000'],
        ]);

        $message = Message::create([
            'sender_id' => $authUser->id,
            'receiver_id' => $user->id,
            'message' => $validated['message'],
        ]);

        $payload = [
            'id' => $message->id,
            'sender_id' => $message->sender_id,
            'receiver_id' => $message->receiver_id,
            'message' => $message->message,
            'created_at' => $message->created_at->toISOString(),
            'sender' => [
                'id' => $authUser->id,
                'name' => $authUser->name,
            ],
        ];

        try {
            broadcast(new MessageSent(
                message: $payload,
                senderId: $authUser->id,
                receiverId: $user->id,
            ))->toOthers();
        } catch (Throwable $e) {
            report($e);
        }

        return response()->json([
            'message' => $payload,
        ]);
    }
}
