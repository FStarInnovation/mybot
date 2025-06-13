<?php

namespace App\Services;

use Illuminate\Support\Collection;

class PromptBuilderService
{
    /**
     * JSON-манифест доступных инструментов (MCP endpoints)
     * Используется в system prompt перед отправкой в RunPod LLM.
     */
    protected array $toolsManifest;

    public function __construct()
    {
        $this->toolsManifest = [
            [
                'name' => 'search_products',
                'description' => 'Search for products by query string.',
                'parameters' => [
                    'type' => 'object',
                    'properties' => [
                        'query' => ['type' => 'string', 'description' => 'Search query to find relevant products'],
                        'limit' => ['type' => 'integer', 'description' => 'Number of results to return', 'default' => 5],
                    ],
                    'required' => ['query'],
                ],
            ],
            [
                'name' => 'crawl_single_page',
                'description' => 'Crawl a single web page and return its content.',
                'parameters' => [
                    'type' => 'object',
                    'properties' => [
                        'url' => ['type' => 'string', 'description' => 'URL of the page to crawl'],
                    ],
                    'required' => ['url'],
                ],
            ],
        ];
    }

    /**
     * Построить system message для LLM, включая контекст и манифест инструментов.
     *
     * @param Collection $longTermContext Коллекция релевантных фрагментов long-term memory
     * @return string Содержимое system prompt
     */
    public function buildSystemContent(Collection $longTermContext): string
    {
        $basePrompt = "Ты дружелюбный русскоязычный ассистент. Отвечай кратко и по существу.";

        $contextText = '';
        if ($longTermContext->isNotEmpty()) {
            $lines = $longTermContext->map(fn($item) => "- {$item['content']}")->implode("\n");
            $contextText = "\n\nКонтекст из предыдущих обсуждений:\n{$lines}";
        }

        $manifestJson = json_encode($this->toolsManifest, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

        return $basePrompt . $contextText . "\n\nДоступные инструменты для выполнения:\n" . $manifestJson;
    }

    /**
     * Вернуть массив описания инструментов для function_call.
     *
     * @return array
     */
    public function getToolsManifest(): array
    {
        return $this->toolsManifest;
    }
}
