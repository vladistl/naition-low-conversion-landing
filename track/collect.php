<?php

declare(strict_types=1);

require_once __DIR__ . '/db.php';

header('Content-Type: application/json; charset=utf-8');

$payload = json_decode((string) file_get_contents('php://input'), true);
if (!is_array($payload) || !isset($payload['sid'], $payload['events']) || !is_array($payload['events'])) {
    http_response_code(400);
    echo '{"ok":false}';
    exit;
}

$sid = substr((string) $payload['sid'], 0, 40);
$headers = [];
foreach (['HTTP_USER_AGENT', 'HTTP_ACCEPT_LANGUAGE', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_REAL_IP', 'HTTP_SEC_CH_UA', 'HTTP_SEC_CH_UA_PLATFORM', 'HTTP_REFERER'] as $key) {
    if (isset($_SERVER[$key])) {
        $headers[$key] = $_SERVER[$key];
    }
}

try {
    $pdo = trackPdo();
    $pdo->beginTransaction();
    $pdo->prepare('INSERT OR IGNORE INTO sessions (sid, ip, headers) VALUES (?, ?, ?)')
        ->execute([$sid, $_SERVER['REMOTE_ADDR'] ?? null, json_encode($headers, JSON_UNESCAPED_UNICODE)]);
    $stmt = $pdo->prepare('INSERT INTO events (sid, n, t, type, data) VALUES (?, ?, ?, ?, ?)');
    foreach (array_slice($payload['events'], 0, 500) as $e) {
        $stmt->execute([
            $sid,
            (int) ($e['n'] ?? 0),
            (int) ($e['t'] ?? 0),
            substr((string) ($e['type'] ?? ''), 0, 20),
            json_encode($e['data'] ?? null, JSON_UNESCAPED_UNICODE),
        ]);
    }
    $pdo->commit();
    echo '{"ok":true}';
} catch (Throwable $e) {
    http_response_code(500);
    echo '{"ok":false}';
}
