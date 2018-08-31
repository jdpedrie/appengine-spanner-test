<?php

include __DIR__ .'/vendor/autoload.php';

use Google\Cloud\Spanner\SpannerClient;
use Google\Cloud\Spanner\Session\CacheSessionPool;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

$cache = new FilesystemAdapter(); # GAE doesn't support semaphore
$sessionPool = new CacheSessionPool($cache);
$spanner = new SpannerClient([
    'authCache' => $cache,
]);
$database = $spanner->connect(
    'spanner-repro',
    'spanner-repro',
    [
        'sessionPool' => $sessionPool
    ]
);

$results = $database->execute('SELECT "Hello Spanner World" as test');
foreach ($results as $row) {
    print($row['test'] . PHP_EOL);
}

$database->close();
