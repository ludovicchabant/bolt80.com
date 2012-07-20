<?php

use PieCrust\IPieCrust;
use PieCrust\PieCrustDefaults;
use PieCrust\TemplateEngines\ITemplateEngine;
use PieCrust\Util\PathHelper;


class HamlTemplateEngine implements ITemplateEngine
{
    protected $pieCrust;
    protected $cacheDir;
    protected $haml;
    
    public function initialize(IPieCrust $pieCrust)
    {
        $this->pieCrust = $pieCrust;
    }
    
    public function getExtension()
    {
        return 'haml';
    }
    
    public function renderString($content, $data)
    {
        $this->ensureLoaded();
        
        $dir = $this->cacheDir;
        if (!$dir)
            $dir = rtrim(sys_get_temp_dir(), '/\\') . '/';
        $temp = $dir . '__haml_string_tpl__.haml';
        $out = $dir . '__haml_string_tpl__.php';
        
        if (@file_put_contents($temp, $content) === false)
            throw new PieCrustException("Can't write input Haml template to: " . $temp);
        
        $phpMarkup = $this->haml->parse($temp);
        if (@file_put_contents($out, $phpMarkup) === false)
            throw new PieCrustException("Can't write output Haml template to: " . $out);
        
        // Declare all top-level data as local-scope variables before including the HAML PHP.
        $_PIECRUST_APP = $this->pieCrust;
        foreach ($data as $key => $value)
        {
            if (is_array($value))
            {
                $$key = self::arrayToObject($value);
            }
            else
            {
                $$key = $value;
            }
        }
        require $out;
    }
    
    public function renderFile($templateName, $data)
    {
        $this->ensureLoaded();
        
        $templatePath = PathHelper::getTemplatePath($this->pieCrust, $templateName);
        $outputPath = $this->haml->parse($templatePath, $this->cacheDir);
        if ($outputPath === false) throw new PieCrustException("An error occured processing template: " . $templateName);
        
        // Declare all top-level data as local-scope variables before including the HAML PHP.
        $_PIECRUST_APP = $this->pieCrust;
        foreach ($data as $key => $value)
        {
            if (is_array($value))
            {
                $$key = self::arrayToObject($value);
            }
            else
            {
                $$key = $value;
            }
        }
        require $outputPath;
    }
    
    public function clearInternalCache()
    {
    }
    
    protected function ensureLoaded()
    {
        if ($this->haml === null)
        {
            $this->cacheDir = false;
            if ($this->pieCrust->isCachingEnabled())
            {
                $this->cacheDir = $this->pieCrust->getCacheDir() . 'templates_c';
            }
            
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

    protected static function arrayToObject(array $arr)
    {
        $result = (object)$arr;
        foreach ($arr as $key => $value)
        {
            if (is_array($value))
            {
                $result->$key = self::arrayToObject($value);
            }
        }
        return $result;
    }
}
