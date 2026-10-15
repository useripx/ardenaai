<?php

declare(strict_types=1);

/**
 * Public Front Controller — Ardena AI Scratch 3
 * 
 * Melayani request yang masuk langsung ke subfolder backend/public/.
 */

require_once dirname(__DIR__) . '/src/bootstrap.php';
require_once dirname(__DIR__) . '/src/routes.php';

use Ardena\Core\Router;

Router::dispatch();
