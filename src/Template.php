<?php

namespace Favez\Template;

class Template
{

    protected $name;

    protected $filename;

    protected $mode;

    protected $blocks;

    protected $openedBlocks;

    protected $extends;

    protected $vars;

    public function __construct($name, $filename, $mode = Environment::RENDER_MODE_CONTENT, $blocks = [])
    {
        $this->name         = $name;
        $this->filename     = $filename;
        $this->mode         = $mode;
        $this->blocks       = $blocks;
        $this->vars         = [];
        $this->openedBlocks = [];
    }

    public function assign($name, $value = null)
    {
        if (is_array($name))
        {
            $this->vars = array_merge($this->vars, $name);
        }
        else
        {
            $this->vars[$name] = $value;
        }
    }

    public function render()
    {
        extract($this->vars);

        ob_start();
        require_once $this->filename;
        $contents = ob_get_contents();
        ob_end_clean();

        if (!empty($this->extends))
        {
            $template = Environment::create($this->extends, Environment::RENDER_MODE_CONTENT, $this->blocks);
            $template->assign($this->vars);

            return $template->render();
        }

        return $contents;
    }

    public function import($name, $vars = [], $return = false)
    {
        $template = Environment::create($name);
        $template->assign($vars);

        if ($return)
        {
            return $template->render();
        }

        echo $template->render();
    }

    protected function get($key, $default = null)
    {
        $vars = $this->vars;
        $keys = explode('.', $key);

        foreach ($keys as $key)
        {
            if (isset($vars[$key]))
            {
                $vars = $vars[$key];
            }
            else
            {
                $vars = $default;
                break;
            }
        }

        return $vars;
    }

    public function __get($key)
    {
        return $this->get($key);
    }

    protected function block($name)
    {
        ob_start();
        $this->openedBlocks[] = $name;
    }

    protected function endblock()
    {
        if (empty($this->openedBlocks))
        {
            throw new \Exception('Called endblock() without leading block()');
        }

        $blockName = array_pop($this->openedBlocks);

        if (!isset($this->blocks[$blockName]))
        {
            $this->blocks[$blockName] = ob_get_contents();
        }

        ob_end_clean();

        if ($this->mode == Environment::RENDER_MODE_CONTENT)
        {
            echo $this->blocks[$blockName];
        }
    }

    protected function extend($name)
    {
        $this->mode    = Environment::RENDER_MODE_BLOCKS;
        $this->extends = $name;
    }

}