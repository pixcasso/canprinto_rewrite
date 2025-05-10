<?php

declare(strict_types=1);

namespace Canprinto;

use Canprinto\Admin\CptBlueprint;
use Canprinto\Admin\BlueprintMetaBox;

final class Plugin
{
    public function run(): void
    {
        CptBlueprint::init();
        BlueprintMetaBox::init();
    }
}
