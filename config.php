<?php
/**
 * Hasnet ICT Solution - Central Configuration
 * Copy this file and adjust values for your environment.
 */

// ─── Environment ─────────────────────────────────────────────────────────────
define('APP_ENV', getenv('APP_ENV') ?: 'local'); // 'local' or 'production'
define('APP_URL',  getenv('APP_URL')  ?: 'http://localhost/hasnet-website');
define('APP_NAME', 'Hasnet ICT Solution');

// ─── Database ─────────────────────────────────────────────────────────────────
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_NAME', getenv('DB_NAME') ?: 'hasnet_db');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: '');
define('DB_CHARSET', 'utf8mb4');

// ─── Admin ────────────────────────────────────────────────────────────────────
define('ADMIN_PATH', __DIR__ . '/admin');
define('UPLOADS_PATH', __DIR__ . '/uploads');
define('UPLOADS_URL', APP_URL . '/uploads');

// ─── Session ──────────────────────────────────────────────────────────────────
define('SESSION_NAME', 'hasnet_admin');
define('SESSION_LIFETIME', 3600 * 8); // 8 hours

// ─── Security ─────────────────────────────────────────────────────────────────
define('HASH_ALGO', PASSWORD_BCRYPT);

// ─── Timezone ─────────────────────────────────────────────────────────────────
date_default_timezone_set('Africa/Dar_es_Salaam');
