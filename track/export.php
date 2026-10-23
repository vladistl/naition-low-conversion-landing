<?php

declare(strict_types=1);

require_once __DIR__ . '/db.php';

// Выгрузка сырых событий трекера: track/export.php?key=...&since=<event id>
header('Content-Type: application/json; charset=utf-8');

// В репозитории только sha256 токена; сам токен хранится локально у владельца.
const TOKEN_SHA256 = '55d7d46f1f399a549ce278f4217b0983b99eed12eae89c94f7fed94c7c6d7f27';

if (!hash_equals(TOKEN_SHA256, hash('sha256', (string) ($_GET['key'] ?? '')))) {
    http_response_code(403);
    echo '{"ok":false}';
    exit;
}

$since = (int) ($_GET['since'] ?? 0);
$pdo = trackPdo();

$events = $pdo->prepare('SELECT id, sid, n, t, type, data, received FROM events WHERE id > ? ORDER BY id LIMIT 50000');
$events->execute([$since]);

echo json_encode([
    'sessions' => $pdo->query('SELECT * FROM sessions ORDER BY started')->fetchAll(),
    'events' => $events->fetchAll(),
], JSON_UNESCAPED_UNICODE);
