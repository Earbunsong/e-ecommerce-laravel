<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    echo "Testing R2 Public Upload...\n\n";

    // Test 1: Upload with public visibility
    echo "1. Uploading file with public visibility...\n";
    Storage::disk('s3')->put('test-public.txt', 'Hello R2 Public!', 'public');
    echo "   ✓ Upload successful\n";

    $url = Storage::disk('s3')->url('test-public.txt');
    echo "   URL: " . $url . "\n\n";

    // Test 2: Check public access
    echo "2. Testing public access (waiting 2 seconds)...\n";
    sleep(2);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    echo "   HTTP Status: " . $httpCode . "\n";
    if ($httpCode === 200) {
        echo "   ✓ SUCCESS! File is publicly accessible\n";
        echo "   Response: " . $response . "\n";
    } else {
        echo "   ✗ File returns " . $httpCode . "\n";
        echo "   This means the R2.dev domain might not be fully active yet\n";
    }

    // Cleanup
    echo "\n3. Cleaning up...\n";
    Storage::disk('s3')->delete('test-public.txt');
    echo "   ✓ Test file deleted\n";

} catch (Exception $e) {
    echo "\n✗ ERROR: " . $e->getMessage() . "\n";
}
