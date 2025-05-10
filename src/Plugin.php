<?php

declare(strict_types=1);

namespace Canprinto;

final class Plugin
{
    use Canprinto\Admin\CptBlueprint;
    use Canprinto\Admin\BlueprintMetaBox;

    public function run(): void
    {
        CptBlueprint::init();
        BlueprintMetaBox::init();
    }
}
