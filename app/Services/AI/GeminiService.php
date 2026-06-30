<?php
namespace App\Services\AI;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class GeminiService
{
    protected string $apiKey;
    protected string $model = 'gemini-2.5-flash';

    public function __construct()
    {
        $this->apiKey = config('services.gemini.api_key');
    }

    public function chat(array $payload): Response
    {
        return Http::timeout(20)
            ->post(
                "https://generativelanguage.googleapis.com/v1beta/models/{$this->model}:generateContent?key={$this->apiKey}",
                $payload
            );
    }
}