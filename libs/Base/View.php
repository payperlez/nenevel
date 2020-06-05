<?php

/**
 * @author      Obed Ademang <kizit2012@gmail.com>
 * @copyright   Copyright (C), 2015 Obed Ademang
 * @license     MIT LICENSE (https://opensource.org/licenses/MIT)
 *              Refer to the LICENSE file distributed within the package.
 *
 */

namespace DIY\Base;
use DIY\Base\Utils\Session;

class DViewException extends \Exception{ }

class View {
    private $_viewConfig = [];
    private $__data__ = [];
    private $_loader;
    private $_engine;

    public function __construct() {
        Session::init();
        $this->_viewConfig = unserialize(TEMPLATES);
        if(isset($this->_viewConfig['templateDir'])){
            $this->_loader = new \Twig\Loader\FilesystemLoader($this->_viewConfig['templateDir']);
            $view_settings = array(
                'cache' => $this->_viewConfig['cacheDir'],
                'debug' => $this->_viewConfig['debug'],
                'autoescape' => $this->_viewConfig['autoescape'],
                'auto_reload' => true
            );
            
            if(RUNTIME_ENVIRONMENT === 'dev') unset($view_settings['cache']);
            
            $this->_engine = new \Twig\Environment($this->_loader, $view_settings);
        } else {
            throw new DViewException("Please set the path to your templates");
        }
    }

    public function __get($name) {
        return $this->__data__[$name];
    }

    /**
     * @param $name
     * @param $value
     * @return void
     */
    public function __set($name, $value) {
        if ($name == 'view_template_file') {
            throw new DViewException("Cannot bind variable name 'view_template_file'");
        }
        $this->__data__[$name] = $value;
    }

    /**
     * @param $__name__
     * @return void
     */
    public function render($__name__) {
        foreach ($this->_loader->getPaths() as $path) {
            if (file_exists("{$path}/{$__name__}.twig")) {
                echo $this->_engine->render("{$__name__}.twig", $this->__data__);
            }
        }

        return true;
    }
}
