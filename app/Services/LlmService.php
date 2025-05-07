public function complete(string $prompt): string
{
    $response = Http::timeout(30)->post(config('services.llm.endpoint'), [
        'model' => config('services.llm.model', 'mistral'),
        'prompt' => $prompt,
        'temperature' => (float) config('services.llm.temp', 0.7),
        'max_tokens' => (int) config('services.llm.max_tokens', 200),
    ]);

    $json = $response->json();

    if (!isset($json['content'])) {
        logger()->error('LLM response missing content', ['response' => $json]);
        throw new \RuntimeException('LLM did not return content.');
    }

    return $json['content'];
}