<?php
require 'vendor/autoload.php';

$loader = new \Canprinto\Blueprint\Loader(
    __DIR__ . '/assets/blueprint/schema.json'
);

// sticker_kisscut_freiform.json anpassen, falls du ihn anders genannt hast
$bp = $loader->fromFile(
    __DIR__ . '/assets/blueprint/sticker_kisscut_freiform.json'
);

echo "Loaded: " . $bp->title . PHP_EOL;
