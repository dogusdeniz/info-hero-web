<?php

namespace App\Http\Controllers;

use App\Libraries\LLama;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $prompt = $request->query('prompt');

        // validate prompt
        if (empty($prompt)) {
            // return error response with stream
            return response()->stream(function () {
                echo json_encode([
                    'error' => 'Prompt is required',
                ]);
            });
        }

        return LLama::completion($request->input('prompt'), stream: true);
    }
}
