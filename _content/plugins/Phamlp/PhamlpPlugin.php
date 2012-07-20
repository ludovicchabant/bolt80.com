<?php

require_once 'src/HamlFormatter.php';
require_once 'src/HamlTemplateEngine.php';
require_once 'src/SassProcessor.php';

use PieCrust\PieCrustPlugin;


class PhamlpPlugin extends PieCrustPlugin
{
    public function getName()
    {
        return "PhamlP";
    }

    public function getFormatters()
    {
        return array(
            new HamlFormatter()
        );
    }

    public function getTemplateEngines()
    {
        return array(
            new HamlTemplateEngine()
        );
    }

    public function getProcessors()
    {
        return array(
            new SassProcessor()
        );
    }
}

