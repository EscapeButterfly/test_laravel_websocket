<?php

namespace App\Http\Controllers;

use App\Chat\DTO\MessageData;
use App\Chat\Services\ChatService;
use App\Http\Requests\SendMessageRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class ChatController extends Controller
{
    public function __construct(
        private readonly ChatService $chatService
    )
    {
        $this->middleware(['auth', 'verified']);
    }

    public function sendMessage(SendMessageRequest $request): void
    {
        $messageData = MessageData::fromArray([
            'sender_id'   => auth()->id(),
            'receiver_id' => $request->receiver_id,
            'name'        => auth()->user()->name,
            'message'     => $request->message
        ]);
        $this->chatService->sendMessage($messageData);
    }

    public function chatPage(): View
    {
        $this->chatService->setUserOnline();
        return view('chat.chat');
    }

    public function getDialogMessages($senderId): JsonResponse
    {
        return response()->json($this->chatService->getDialogMessages($senderId));
    }
}
