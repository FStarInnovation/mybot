<?php

ini_set('memory_limit', '512M');
set_time_limit(300);

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Product;

echo "Starting products export to CSV...\n";
echo "Total products: " . Product::count() . "\n";

$filename = 'products_export_' . date('Y-m-d_H-i-s') . '.csv';
$filepath = __DIR__ . '/' . $filename;

$file = fopen($filepath, 'w');

// Headers
$headers = [
    'ID', 'Title', 'Slug', 'Brand', 'Description', 'Price', 'Regular Price', 
    'Discount %', 'SKU ID', 'Source Site', 'URL', 'Category ID', 'GTIN',
    'Laboratory', 'Categories', 'Dosage Form', 'Dosage Strength', 
    'Availability', 'Is Available', 'Created At', 'Updated At'
];

fputcsv($file, $headers);

// Get products with chunking to avoid memory issues
$count = 0;
Product::chunk(1000, function($products) use (&$file, &$count) {
    foreach ($products as $product) {
        $data = [
            $product->id,
            $product->title,
            $product->slug,
            $product->brand,
            $product->description,
            $product->price,
            $product->regular_price,
            $product->discount_pct,
            $product->sku_id,
            $product->source_site,
            $product->url,
            $product->category_id,
            $product->gtin,
            $product->laboratory,
            $product->categories,
            $product->dosage_form,
            $product->dosage_strength,
            $product->availability,
            $product->is_available,
            $product->created_at,
            $product->updated_at
        ];
        
        fputcsv($file, $data);
        $count++;
        
        if ($count % 1000 == 0) {
            echo "Exported $count products...\n";
            
            // Clear memory periodically
            if ($count % 5000 == 0) {
                gc_collect_cycles();
            }
        }
    }
});

fclose($file);

echo "Export completed!\n";
echo "File saved: $filename\n";
echo "Total rows exported: $count\n";
