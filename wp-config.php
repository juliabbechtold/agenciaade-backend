<?php

define('DB_NAME', 'projetos_agenciaade');
define('DB_USER', 'projetos_ade');
define('DB_PASSWORD', 'mitosdaweb');
define('DB_HOST', 'localhost');

define('DB_CHARSET', 'utf8');
define('DB_COLLATE', '');
define('ALLOW_UNFILTERED_UPLOADS', true);

define('AUTH_KEY', 'mitosdaweb@agenciaade');
define('SECURE_AUTH_KEY', 'mitosdaweb@agenciaade');
define('LOGGED_IN_KEY', 'mitosdaweb@agenciaade');
define('NONCE_KEY', 'mitosdaweb@agenciaade');
define('AUTH_SALT', 'mitosdaweb@agenciaade');
define('SECURE_AUTH_SALT', 'mitosdaweb@agenciaade');
define('LOGGED_IN_SALT', 'mitosdaweb@agenciaade');
define('NONCE_SALT', 'mitosdaweb@agenciaade');

$table_prefix = 'wp_';

define('WP_DEBUG', false);
define('WP_POST_REVISIONS', false);

if (!defined('ABSPATH')) {
  define('ABSPATH', dirname(__FILE__) . '/');
}

require_once ABSPATH . 'wp-settings.php';
