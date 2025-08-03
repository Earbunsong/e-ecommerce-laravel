<?php

// Set memory limit for Vercel
ini_set('memory_limit', '1024M');
ini_set('max_execution_time', '10');

// Change to application root
chdir(dirname(__DIR__));

// Auto-loader
require __DIR__ . '/../vendor/autoload.php';

// Bootstrap Laravel application
$app = require_once __DIR__ . '/../bootstrap/app.php';

// Handle the request
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$response->send();

$kernel->terminate($request, $response);
