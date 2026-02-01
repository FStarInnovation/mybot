<?php

ini_set('memory_limit', '512M');
set_time_limit(300);

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Product;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

echo "Starting products export...\n";
echo "Total products: " . Product::count() . "\n";

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Headers
$headers = [
    'ID', 'Title', 'Slug', 'Brand', 'Description', 'Price', 'Regular Price', 
    'Discount %', 'SKU ID', 'Source Site', 'URL', 'Category ID', 'GTIN',
    'Laboratory', 'Categories', 'Dosage Form', 'Dosage Strength', 
    'Availability', 'Is Available', 'Created At', 'Updated At'
];

$sheet->fromArray($headers, null, 'A1');

// Style header row
$headerStyle = $sheet->getStyle('A1:' . $sheet->getHighestColumn() . '1');
$headerStyle->getFont()->setBold(true);
$headerStyle->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
$headerStyle->getFill()->getStartColor()->setRGB('E0E0E0');

// Get products with chunking to avoid memory issues
$row = 2;
Product::chunk(500, function($products) use (&$sheet, &$row) {
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
        
        $sheet->fromArray($data, null, 'A' . $row);
        $row++;
        
        if ($row % 500 == 0) {
            echo "Exported " . ($row - 2) . " products...\n";
            
            // Clear memory periodically
            if ($row % 5000 == 0) {
                gc_collect_cycles();
            }
        }
    }
});

// Auto-size columns
foreach (range('A', $sheet->getHighestColumn()) as $column) {
    $sheet->getColumnDimension($column)->setAutoSize(true);
}

$filename = 'products_export_' . date('Y-m-d_H-i-s') . '.xlsx';
$filepath = __DIR__ . '/' . $filename;

$writer = new Xlsx($spreadsheet);
$writer->save($filepath);

echo "Export completed!\n";
echo "File saved: $filename\n";
echo "Total rows exported: " . ($row - 2) . "\n";
