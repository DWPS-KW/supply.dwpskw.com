<?php

namespace Config;

use Kint\Parser\ConstructablePluginInterface;
use Kint\Renderer\Rich\TabPluginInterface;
use Kint\Renderer\Rich\ValuePluginInterface;

class Kint
{
    public ?array $plugins = null;
    public int $maxDepth = 6;
    public bool $displayCalledFrom = true;
    public bool $expanded = false;

    public string $richTheme = 'aante-light.css';
    public bool $richFolder = false;
    public bool $richSort = true; // This is the missing property you added
    public ?array $richObjectPlugins = null;
    public ?array $richTabPlugins = null;
    
    public bool $cliColors = true;
    public bool $cliForceUTF8 = false;
    public bool $cliDetectWidth = true;
    public int $cliMinWidth = 40;
    
}
