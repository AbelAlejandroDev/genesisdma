<?php
// api/config.php

require_once __DIR__ . '/../vendor/autoload.php';

// Cargar las variables de entorno
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// Definir constantes
define('SMTP_HOST', $_ENV['SMTP_HOST']);
define('SMTP_PORT', $_ENV['SMTP_PORT']);
define('SMTP_USERNAME', $_ENV['SMTP_USERNAME']);
define('SMTP_PASSWORD', $_ENV['SMTP_PASSWORD']);
define('FROM_EMAIL', $_ENV['FROM_EMAIL']);
define('FROM_NAME', $_ENV['FROM_NAME']);
define('FROM_PHONE',$_ENV['FROM_PHONE']);