<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Requests\AiChatRequest;
use App\Services\AI\GeminiService;
use App\Services\AI\PromptBuilderService;
use App\Settings\ClinicSettings;
use App\Models\Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class AiController extends Controller
{
    public function index(ClinicSettings $clinicSettings)
    {
        $services = Service::where('is_active', true)->orderBy('name')->get();

        return view('user.ai.index', compact('services', 'clinicSettings'));
    }

    public function chat(
        AiChatRequest $request,
        GeminiService $gemini,
        ClinicSettings $clinicSettings,
        PromptBuilderService $promptBuilder
    ): JsonResponse {
        $data = $request->validated();

        $systemPrompt = $promptBuilder->build($clinicSettings);
        $payload = $this->buildPayload($systemPrompt, $data['history'] ?? [], $data['message']);

        $response = $gemini->chat($payload);

        if ($response->failed()) {
            Log::error('Gemini API error', [
                'status' => $response->status(),
                'body'   => $response->json(),
            ]);

            return response()->json([
                'reply'       => 'عذراً، حدث خطأ في الاتصال بالمساعد الذكي. حاول مرة أخرى.',
                'booking_url' => null,
            ]);
        }

        $text = data_get(
            $response->json(),
            'candidates.0.content.parts.0.text',
            'عذراً، لم أتمكن من فهم طلبك. حاول مرة أخرى.'
        );

        return response()->json($this->formatReply($text));
    }

    private function buildPayload(string $systemPrompt, array $history, string $message): array
    {
        $contents = collect($history)
            ->map(fn (array $h) => [
                'role'  => $h['role'],
                'parts' => [['text' => $h['content']]],
            ])
            ->push([
                'role'  => 'user',
                'parts' => [['text' => $message]],
            ])
            ->values()
            ->all();

        return [
            'system_instruction' => [
                'parts' => [['text' => $systemPrompt]],
            ],
            'contents' => $contents,
            'generationConfig' => [
                'temperature'     => 0.7,
                'maxOutputTokens' => 512,
            ],
        ];
    }

    private function formatReply(string $text): array
    {
        $bookingUrl = null;

        if (str_contains($text, '[BOOKING_READY]')) {
            $text = str_replace('[BOOKING_READY]', '', $text);
            $bookingUrl = route('user.booking.index');
        }

        return [
            'reply'       => nl2br(trim($text)),
            'booking_url' => $bookingUrl,
        ];
    }
}