<?php

declare(strict_types=1);

// Отдельная БД трекера, чтобы не трогать схему лендинга (sql/schema.sql).
// Лежит вне корня сайта: папка data/ отдаётся веб-сервером как статика.
function trackPdo(): PDO
{
    $dataDir = sys_get_temp_dir() . '/naition-track';
    if (!is_dir($dataDir)) {
        mkdir($dataDir, 0755, true);
    }

    $pdo = new PDO('sqlite:' . $dataDir . '/track.db');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $pdo->exec('PRAGMA journal_mode=WAL');
    $pdo->exec('PRAGMA busy_timeout=3000');
    $pdo->exec(
        'CREATE TABLE IF NOT EXISTS sessions (
            sid TEXT PRIMARY KEY,
            started DATETIME DEFAULT CURRENT_TIMESTAMP,
            ip TEXT,
            headers TEXT
        );
        CREATE TABLE IF NOT EXISTS events (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            sid TEXT NOT NULL,
            n INTEGER,
            t INTEGER,
            type TEXT,
            data TEXT,
            received DATETIME DEFAULT CURRENT_TIMESTAMP
        );
        CREATE INDEX IF NOT EXISTS events_sid ON events (sid, n);'
    );

    return $pdo;
}
