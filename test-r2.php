<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    echo "Testing R2 Connection...\n";
    echo "Bucket: " . config('filesystems.disks.s3.bucket') . "\n";
    echo "Endpoint: " . config('filesystems.disks.s3.endpoint') . "\n";
    echo "Region: " . config('filesystems.disks.s3.region') . "\n";
    echo "\n";

    // Test upload
    echo "Uploading test file...\n";
    Storage::disk('s3')->put('test.txt', 'Hello from R2!');
    echo "✓ Upload successful\n\n";

    // Test URL generation
    $url = Storage::disk('s3')->url('test.txt');
    echo "Generated URL: " . $url . "\n\n";

    // Test file exists
    if (Storage::disk('s3')->exists('test.txt')) {
        echo "✓ File exists in R2\n\n";
    }

    // Test public access
    echo "Testing public access...\n";
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    echo "HTTP Status: " . $httpCode . "\n";
    if ($httpCode === 200) {
        echo "✓ File is publicly accessible!\n";
        echo "Response: " . $response . "\n";
    } else {
        echo "✗ File is NOT publicly accessible\n";
    }

    // Cleanup
    echo "\nCleaning up...\n";
    Storage::disk('s3')->delete('test.txt');
    echo "✓ Test file deleted\n";

    echo "\n✓ ALL TESTS PASSED!\n";

} catch (Exception $e) {
    echo "\n✗ ERROR: " . $e->getMessage() . "\n";
    echo "\nFull error:\n";
    echo $e->getTraceAsString() . "\n";
}
