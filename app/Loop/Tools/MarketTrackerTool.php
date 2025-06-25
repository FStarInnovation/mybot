<?php

namespace App\Loop\Tools;

use Kirschbaum\Loop\Contracts\Tool;
use Kirschbaum\Loop\Concerns\Makeable;
use Prism\Prism\Tool as PrismTool;
use App\Jobs\TrackCompetitorPrices;

/**
 * MCP Tool to track competitor prices and analyze market data.
 * This tool enables price monitoring, trend analysis, and competitive intelligence.
 */
class MarketTrackerTool implements Tool
{
    use Makeable;

    /**
     * Return Prism Tool definition
     */
    public function build(): PrismTool
    {
        return app(PrismTool::class)
            ->as($this->getName())
            ->for('Monitor competitor prices and analyze market data')
            ->withStringParameter('competitor', 'Target competitor name (e.g. farmacity, drsimi)', required: true)
            ->withStringParameter('category', 'Product category to track', required: false)
            ->withBooleanParameter('historical', 'Include historical price analysis', required: false, defaultValue: false)
            ->using(function (string $competitor, ?string $category = null, bool $historical = false) {
                // Track competitor prices and dispatch job
                TrackCompetitorPrices::dispatch($competitor, $category, $historical);
                
                return "Started price tracking for {$competitor}" . 
                       ($category ? " in category: {$category}" : "") . 
                       ($historical ? " with historical analysis" : "");
            });
    }

    public function getName(): string
    {
        return 'marketTracker';
    }
}
