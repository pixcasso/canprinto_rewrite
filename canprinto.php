<?php
/**
 * Plugin Name:  Canprinto (Rewrite Skeleton)
 * Description:  Minimaler Start – Composer & PSR-4 bereit.
 * Version:      0.1.0
 * Author:       Dein Name
 * License:      GPL-2.0-or-later
 */

declare(strict_types=1);

$autoloader = __DIR__ . '/vendor/autoload.php';
if (! file_exists($autoloader)) {
    add_action('admin_notices', static function () {
        echo '<div class="notice notice-error"><p>Canprinto: Composer-Autoloader fehlt. Bitte "composer install" ausführen.</p></div>';
    });
    return;
}

require_once $autoloader;

(new Canprinto\Plugin())->run();
