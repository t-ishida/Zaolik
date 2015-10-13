<?php
namespace Zaolik;

class DIContainer 
{
    private static $instance = null;
    private function __construct() {
    }

    public static function getInstance() 
    {
        if (self::$instance === null) {
            self::$instance = new DIContainer();
        }
        return self::$instance;
    }
    
    private $flyWeights = array();
    private $new        = array();

    public function clear() 
    {
        $this->flyWeights = array();
        $this->new        = array();
    }

    public function setFlyWeight ($name, \Closure $closure) 
    {
        if (!isset($name) || !is_string($name)) {
            throw new \InvalidArgumentException('$name is invalid.');
        }

        if (isset($this->flyWeights[$name])) {
            throw new \InvalidArgumentException("$name is already exists.");
        }
        $this->flyWeights[$name]['constructor'] = $closure;
        return $this;
    } 

    public function getFlyWeight ($name)
    {
        if (!isset($name) || !is_string($name)) {
            throw new \InvalidArgumentException('$name is invalid.');
        }

        if (!isset($this->flyWeights[$name])) {
            throw new \InvalidArgumentException("$name is not exists.");
        }

        if (!isset($this->flyWeights[$name]['instance'])) {
            $constructor = $this->flyWeights[$name]['constructor'];
            $this->flyWeights[$name]['instance'] = $constructor();
        }
    }

    public function setNew ($name, \Closure $closure) 
    {
        if (!isset($name) || !is_string($name)) {
            throw new \InvalidArgumentException('$name is invalid.');
        }

        if (isset($this->new[$name])) {
            throw new \InvalidArgumentException("$name is already exists.");
        }
        $this->new[$name]['constructor'] = $closure;
        return $this;
    }

    public function getNewInstance (/* polymophick */) 
    {
        $numArgs = func_num_args();
        $args = func_get_args();
        if ($numArgs === 0) { 
            throw new \InvalidArgumentException('$name is invalid.');
        }
        $name = array_shift($args);
        if ($numArgs === 1) {
            $args = array();
        } elseif ($numArgs === 2)  {
            $args = $args[0];
            if (!is_array($args)) {
                $args = array($args);
            }
        } 
        if (!is_string($name)) {
            throw new \InvalidArgumentException('$name is invalid.');
        }
        if (!isset($this->new[$name])) {
            throw new \InvalidArgumentException("$name is not exists.");
        }
        return call_user_func_array ($this->new[$name]['constructor'], $args);
    }
}

