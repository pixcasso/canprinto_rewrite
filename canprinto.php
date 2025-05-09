<?php
/**
 * Plugin Name: Canprinto (Rewrite)
 * Description: Fresh skeleton using Composer & PSR-4.
 * Version: 0.1.0
 * Author: Your Name
 * License: GPL-2.0-or-later
 */

declare(strict_types=1);

$autoloader = __DIR__ . '/vendor/autoload.php';
if (! file_exists($autoloader)) {
    add_action('admin_notices', static function () {
        echo '<div class="notice notice-error"><p>Composer autoloader fehlt. Bitte "composer install" ausführen.</p></div>';
    });
    return;
}
require_once $autoloader;

(new Canprinto\Plugin())->run();