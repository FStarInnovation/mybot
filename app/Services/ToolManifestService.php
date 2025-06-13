<?php

namespace App\Services;

class ToolManifestService
{
    /**
     * Получить манифест доступных инструментов
     */
    public function getToolsManifest(): array
    {
        return [
            [
                'type' => 'function',
                'function' => [
                    'name' => 'search_products',
                    'description' => 'Поиск товаров в базе Farmacity',
                    'parameters' => [
                        'type' => 'object',
                        'properties' => [
                            'query' => [
                                'type' => 'string',
                                'description' => 'Поисковый запрос'
                            ],
                            'limit' => [
                                'type' => 'integer',
                                'default' => 10
                            ]
                        ],
                        'required' => ['query']
                    ]
                ]
            ],
            [
                'type' => 'function',
                'function' => [
                    'name' => 'crawl_single_page',
                    'description' => 'Извлечение данных со страницы',
                    'parameters' => [
                        'type' => 'object',
                        'properties' => [
                            'url' => [
                                'type' => 'string',
                                'description' => 'URL страницы'
                            ]
                        ],
                        'required' => ['url']
                    ]
                ]
            ]
        ];
    }
}
