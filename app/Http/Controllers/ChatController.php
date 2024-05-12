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
        // get prompt, contextFilter from body
        $prompt = $request->get('prompt');
        $contextFilter = $request->get('contextFilter');

        // validate prompt
        if (empty($prompt)) {
            // return error response with stream
            return response()->stream(function () {
                echo json_encode([
                    'error' => 'Prompt is required',
                ]);
            });
        }

        $systemPrompt = "Siz bir müşteri destek temsilcisisiniz, direktifleri takip ederek ve soruları yanıtlayarak posterlere yardımcı oluyorsunuz.

        Aşağıdaki adımları izleyerek yanıtınızı oluşturun:

        1. Gönderiyi özyinelemeli olarak daha küçük sorulara/direktiflere ayırın

        2. Her bir atomik soru/yönerge için:

        2a. Konuşma geçmişi ışığında bağlamdan en ilgili bilgiyi seçin

        3. Seçilen bilgileri kullanarak, kısalığı/detayları poster sahibinin uzmanlığına göre uyarlanmış bir taslak yanıt oluşturun

        4. Taslak yanıttan yinelenen içeriği kaldırın

        5. Doğruluğu ve uygunluğu artırmak için ayarladıktan sonra nihai yanıtınızı oluşturun

        6. Şimdi sadece son yanıtınızı gösterin! Herhangi bir açıklama veya detay vermeyin

        BAĞLAM:

        {context}";

        return LLama::completion($request->input('prompt'), $systemPrompt, true, $contextFilter, false, stream: true);
    }
}
