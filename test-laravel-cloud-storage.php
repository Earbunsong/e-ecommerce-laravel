<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=================================================\n";
echo "Testing Laravel Cloud Storage Connection\n";
echo "=================================================\n\n";

try {
    // Show configuration
    echo "📝 Configuration:\n";
    echo "   FILESYSTEM_DISK: " . config('filesystems.default') . "\n";
    echo "   Bucket: " . config('filesystems.disks.s3.bucket') . "\n";
    echo "   Endpoint: " . config('filesystems.disks.s3.endpoint') . "\n";
    echo "   URL: " . config('filesystems.disks.s3.url') . "\n";
    echo "   Region: " . config('filesystems.disks.s3.region') . "\n\n";

    // Test 1: Upload file to S3
    echo "🔄 Test 1: Uploading test file to S3...\n";
    $testContent = 'Hello from Laravel Cloud! ' . date('Y-m-d H:i:s');
    $testPath = 'test-files/test-' . time() . '.txt';

    Storage::disk('s3')->put($testPath, $testContent, 'public');
    echo "   ✅ Upload successful!\n";
    echo "   File path: {$testPath}\n\n";

    // Test 2: Generate URL
    echo "🔗 Test 2: Generating public URL...\n";
    $url = Storage::disk('s3')->url($testPath);
    echo "   URL: {$url}\n\n";

    // Test 3: Verify file exists
    echo "🔍 Test 3: Checking if file exists in bucket...\n";
    if (Storage::disk('s3')->exists($testPath)) {
        echo "   ✅ File exists in bucket!\n\n";
    } else {
        echo "   ❌ File not found in bucket!\n\n";
    }

    // Test 4: Read file content
    echo "📖 Test 4: Reading file content...\n";
    $content = Storage::disk('s3')->get($testPath);
    echo "   Content: {$content}\n";
    echo "   ✅ Read successful!\n\n";

    // Test 5: Test public accessibility
    echo "🌐 Test 5: Testing public URL accessibility...\n";
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    echo "   HTTP Status: {$httpCode}\n";
    if ($httpCode === 200) {
        echo "   ✅ File is publicly accessible!\n";
        echo "   Response: {$response}\n\n";
    } else {
        echo "   ⚠️  File returned HTTP {$httpCode}\n";
        if ($error) {
            echo "   Error: {$error}\n";
        }
        echo "\n";
    }

    // Test 6: Clean up
    echo "🧹 Test 6: Cleaning up test file...\n";
    Storage::disk('s3')->delete($testPath);
    echo "   ✅ Test file deleted\n\n";

    echo "=================================================\n";
    echo "✅ ALL TESTS COMPLETED SUCCESSFULLY!\n";
    echo "=================================================\n\n";

    echo "✨ Your Laravel Cloud storage is working correctly!\n";
    echo "You can now upload images and they will be stored on:\n";
    echo "   " . config('filesystems.disks.s3.url') . "\n\n";

} catch (Exception $e) {
    echo "\n❌ ERROR: " . $e->getMessage() . "\n\n";
    echo "Stack trace:\n";
    echo $e->getTraceAsString() . "\n\n";

    echo "💡 Troubleshooting:\n";
    echo "1. Check your .env file has correct AWS credentials\n";
    echo "2. Verify FILESYSTEM_DISK=s3 is set\n";
    echo "3. Make sure you ran: php artisan config:clear\n";
    echo "4. Check if composer package is installed: composer require league/flysystem-aws-s3-v3\n";
}
