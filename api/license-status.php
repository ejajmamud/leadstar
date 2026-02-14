<?php
declare(strict_types=1);

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: https://apps.ejaj.space');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

require_once '../includes/config.php';
require_once '../includes/security.php';

// Verify authentication
$headers = getallheaders();
$auth = $headers['Authorization'] ?? '';

if (!preg_match('/^Bearer\s+(.+)$/', $auth, $matches)) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$token = $matches[1];

// Verify with Supabase
$ch = curl_init(SUPABASE_URL . '/auth/v1/user');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . $token,
    'apikey: ' . SUPABASE_ANON_KEY
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode !== 200) {
    http_response_code(401);
    echo json_encode(['error' => 'Invalid token']);
    exit;
}

$user = json_decode($response, true);
$userId = $user['id'] ?? '';

// Get license status from database
$pdo = new PDO('sqlite:../data/licensing.db');
$stmt = $pdo->prepare('SELECT plan, expires_at FROM profiles WHERE id = ?');
$stmt->execute([$userId]);
$profile = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$profile) {
    // Create default free profile
    $stmt = $pdo->prepare('INSERT INTO profiles (id, plan) VALUES (?, ?)');
    $stmt->execute([$userId, 'free']);
    $profile = ['plan' => 'free', 'expires_at' => null];
}

// Get today's usage
$today = date('Y-m-d');
$stmt = $pdo->prepare('
    SELECT feature, SUM(quantity) as total 
    FROM usage_logs 
    WHERE user_id = ? AND DATE(consumed_at) = ?
    GROUP BY feature
');
$stmt->execute([$userId, $today]);
$usage = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

echo json_encode([
    'plan' => $profile['plan'],
    'expires_at' => $profile['expires_at'],
    'usage' => [
        'maps_leads' => (int)($usage['maps_leads'] ?? 0),
        'list_extract_rows' => (int)($usage['list_extract_rows'] ?? 0),
        'page_to_pdf' => (int)($usage['page_to_pdf'] ?? 0)
    ],
    'limits' => [
        'maps_leads' => FREE_MAPS_LIMIT,
        'list_extract_rows' => FREE_LIST_LIMIT,
        'page_to_pdf' => FREE_PDF_LIMIT
    ]
]);