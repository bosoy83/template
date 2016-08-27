<?php

namespace Favez\Template;

class Environment
{

    const RENDER_MODE_BLOCKS  = 1;

    const RENDER_MODE_CONTENT = 2;

    protected static $templateDirs = [];

    public static function addTemplateDir($directory)
    {
        self::$templateDirs[] = $directory;
    }

    public static function render($name, $mode = self::RENDER_MODE_CONTENT)
    {
        $template = self::create($name, $mode);

        echo $template->render();
    }

    public static function create($name, $mode = self::RENDER_MODE_CONTENT, $blocks = [])
    {
        $filename = self::getTemplateFilename($name);

        return new \Favez\Template\Template($name, $filename, $mode, $blocks);
    }

    protected static function getTemplateFilename($name)
    {
        foreach (self::$templateDirs as $templateDir)
        {
            $filename = $templateDir . '/' . $name;

            if (is_file($filename))
            {
                return $filename;
            }
        }

        throw new \Exception('Template not found: ' . $name);
    }

}