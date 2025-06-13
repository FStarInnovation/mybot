<?php

namespace App\Services;

use Illuminate\Support\Collection;

class PromptBuilderService
{
    /**
     * Построить system message для LLM, включая контекст и манифест инструментов.
     *
     * @param Collection $longTermContext Коллекция релевантных фрагментов long-term memory
     * @return string Содержимое system prompt
     */
    public function __construct() {}

    public function buildSystemContent(Collection $longTermContext): string
    {
        // Берём актуальный системный промт из конфигурации (испанский JSON-schema).
        $basePrompt = config('llm.system_prompt');

        $contextText = '';
        if ($longTermContext->isNotEmpty()) {
            $lines = $longTermContext->map(fn($item) => "- {$item['content']}")->implode("\n");
            $contextText = "\n\nКонтекст из предыдущих обсуждений:\n{$lines}";
        }

        return $basePrompt . $contextText;
    }

    /**
     * Вернуть массив описания инструментов для function_call.
     *
     * @return array
     */
    // Метод оставлен для обратной совместимости, но манифест переехал в ToolManifestService
    public function getToolsManifest(): array
    {
        return app(\App\Services\ToolManifestService::class)->getToolsManifest();
    }
}
