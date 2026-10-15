<?php

declare(strict_types=1);

/**
 * Front Controller — Ardena AI Scratch 3 Root Entry Point
 * 
 * Titik masuk utama aplikasi untuk server web Laragon (http://ardenaai.test/).
 */

require_once __DIR__ . '/backend/src/bootstrap.php';
require_once __DIR__ . '/backend/src/routes.php';

use Ardena\Core\Router;

Router::dispatch();
