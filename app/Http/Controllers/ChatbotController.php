<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ChatbotService;
use Illuminate\Support\Facades\Auth;

class ChatbotController extends Controller
{
    protected $chatbotService;

    // Suntikkan ChatbotService ke dalam Controller
    public function __construct(ChatbotService $chatbotService)
    {
        $this->chatbotService = $chatbotService;
    }

    public function sendMessage(Request $request)
    {
        $request->validate(['message' => 'required|string']);
        
        // Panggil Service untuk memproses obrolan (Lempar teks dan data User)
        $reply = $this->chatbotService->processMessage(
            $request->message, 
            Auth::user() // Akan otomatis bernilai null jika yang nge-chat adalah Tamu
        );

        return response()->json(['reply' => $reply]);
    }
}