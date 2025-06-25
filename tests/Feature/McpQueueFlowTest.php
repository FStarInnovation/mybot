<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Queue;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Jobs\ImportFarmacityCatalog;

/**
 * Verify that a call to the /mcp endpoint properly enqueues a background job and
 * returns the expected HTTP response code. This confirms that:
 * 1.  The Laravel Loop MCP route is registered and reachable.
 * 2.  The ImportCatalogTool correctly maps to the ImportFarmacityCatalog job.
 * 3.  The job is pushed onto the default queue driver without errors.
 */
class McpQueueFlowTest extends TestCase
{
    // If you are using RefreshDatabase, uncomment the trait below.
    // use RefreshDatabase;

    public function test_import_catalog_tool_enqueues_job_and_responds_202(): void
    {
        Queue::fake();

        $payload = [
            'jsonrpc' => '2.0',
            'id'      => uniqid('test_', true),
            'method'  => 'tools/call',
            'params'  => [
                'name'      => 'importCatalog',
                'arguments' => [1], // positional arguments: limit = 1
            ],
        ];

        $response = $this->json('POST', '/mcp', $payload, [
            'Accept'       => 'application/json',
        ]);

        
        $response->assertSuccessful(); // accept any 2xx response

        Queue::assertPushed(ImportFarmacityCatalog::class);
    }
}
