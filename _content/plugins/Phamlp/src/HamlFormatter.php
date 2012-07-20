<?php

use PieCrust\IPieCrust;
use PieCrust\PieCrustDefaults;
use PieCrust\PieCrustException;
use PieCrust\Formatters\IFormatter;


class HamlFormatter implements IFormatter
{
    protected $pieCrust;
    protected $haml;
    
    public function initialize(IPieCrust $pieCrust)
    {
        $this->pieCrust = $pieCrust;
    }
    
    public function getPriority()
    {
        return IFormatter::PRIORITY_DEFAULT;
    }

    public function isExclusive()
    {
        return true;
    }
    
    public function supportsFormat($format)
    {
        return $format == 'haml';
    }
    
    public function format($text)
    {
        $this->ensureLoaded();
        
        $dir = $this->pieCrust->getCacheDir();
        if (!$dir) $dir = rtrim(sys_get_temp_dir(), '/\\') . '/';

        $temp = $dir . '__format__.haml';
        $out = $dir . '__format__.php';
        
        if (@file_put_contents($temp, $text) === false)
            throw new PieCrustException("Can't write input Haml template to: " . $temp);
        
        $phpMarkup = $this->haml->parse($temp);
        if (@file_put_contents($out, $phpMarkup) === false)
            throw new PieCrustException("Can't write output Haml template to: " . $out);
        
        ob_start();
        try
        {
            $_PIECRUST_APP = $this->pieCrust;
            require $out;
            return ob_get_clean();
        }
        catch (Exception $e)
        {
            ob_end_clean();
            throw $e;
        }
    }
    
    protected function ensureLoaded()
    {
        if ($this->haml === null)
        {
            $appConfig = $this->pieCrust->getConfig();
            if (isset($appConfig['haml']))
                $hamlOptions = $appConfig['haml'];
            else
                $hamlOptions = array('ugly' => false, 'style' => 'nested');

            $hamlOptions = array_merge(
                array('filterDir' => __DIR__ . DIRECTORY_SEPARATOR . 'Filters'),
                $hamlOptions
            );

            require_once 'PhamlP/haml/HamlParser.php';
            $this->haml = new HamlParser($hamlOptions);
        }
    }
}
