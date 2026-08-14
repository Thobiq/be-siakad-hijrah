<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$key = env('GEMINI_API_KEY');
$url = 'https://generativelanguage.googleapis.com/v1beta/models?key=' . $key;
$opts = [
    "http" => [
        "method" => "GET",
    ]
];
$context = stream_context_create($opts);

$response = @file_get_contents($url, false, $context);
if ($response === false) {
    echo "Error fetching models\n";
    exit;
}

$data = json_decode($response, true);
foreach ($data['models'] as $model) {
    echo $model['name'] . "\n";
}
