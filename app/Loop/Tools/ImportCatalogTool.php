<?php

namespace App\Loop\Tools;

use Kirschbaum\Loop\Contracts\Tool;
use Kirschbaum\Loop\Concerns\Makeable;
use Prism\Prism\Tool as PrismTool;

use App\Jobs\ImportFarmacityCatalog;

/**
 * MCP Tool to enqueue import of Farmacity product catalog from their sitemap.
 */
class ImportCatalogTool implements Tool
{
        use Makeable;

    /**
     * Return Prism Tool definition
     */
    public function build(): PrismTool
    {
        return app(PrismTool::class)
            ->as($this->getName())
            ->for('Fetch Farmacity product sitemaps and enqueue parsing jobs')
            ->withNumberParameter('limit', 'Max PDP URLs to import', required: false)
            ->using(function (?int $limit = null) {
                \App\Jobs\ImportFarmacityCatalog::dispatch($limit);
                return 'Queued Farmacity catalog import job' . ($limit ? ' with limit: ' . $limit : '');
            });
    }

    public function getName(): string
    {
        return 'importCatalog';
    }



    

    
}
