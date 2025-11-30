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
        // System prompts are now defined and managed on the LLM backend (RunPod).
        // Here we only serialize long-term context (if any) as a plain text block,
        // without adding any extra instructions.

        if ($longTermContext->isNotEmpty()) {
            return $longTermContext
                ->map(fn($item) => "- {$item['content']}")
                ->implode("\n");
        }

        return '';
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
