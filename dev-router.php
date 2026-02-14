<?php
declare(strict_types=1);

$uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$assetUri = $uri;
$assetUri = str_starts_with($assetUri, '/leadstar/') ? substr($assetUri, strlen('/leadstar')) : $assetUri;
$assetUri = $assetUri === '' ? '/' : $assetUri;
$path = rtrim($uri, '/');
if ($path === '') {
    $path = '/';
}

if (str_starts_with($path, '/leadstar/')) {
    $path = substr($path, strlen('/leadstar'));
    $path = $path === '' ? '/' : $path;
}
if ($path === '/leadstar') {
    $path = '/';
}

$full = __DIR__ . $assetUri;
if ($assetUri !== '/' && is_file($full)) {
    return false;
}

$routes = [
    '/' => 'index.php',
    '/pricing' => 'pricing.php',
    '/docs' => 'docs.php',
    '/privacy' => 'privacy.php',
    '/terms' => 'terms.php',
    '/sign' => 'sign.php',
    '/account' => 'account.php',
    '/auth' => 'auth.php',
    '/company' => 'company.php',
    '/products' => 'products.php',
    '/status' => 'status.php',
    '/blog' => 'blog/index.php',
    '/support' => 'support/index.php',
];

if (isset($routes[$path])) {
    require __DIR__ . '/' . $routes[$path];
    exit;
}

http_response_code(404);
require __DIR__ . '/index.php';
