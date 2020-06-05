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
        if(PHP_VERSION_ID >= 50303 && class_exists("\Twig_Environment")){
            if(isset($this->_viewConfig['templateDir'])){
                $this->_loader = new \Twig_Loader_Filesystem($this->_viewConfig['templateDir']);
                $this->_engine = new \Twig_Environment($this->_loader, array(
                    // 'cache' => $this->_viewConfig['cacheDir'],
                    'debug' => $this->_viewConfig['debug'],
                    'autoescape' => $this->_viewConfig['autoescape'],
                    'auto_reload' => true
                ));
            } else {
                die("Please set the path to your templates");
            }
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
    public function render($__name__, $layout = false) {
        if (PHP_VERSION_ID >= 50303 && class_exists("\Twig_Environment")) {
            foreach ($this->_loader->getPaths() as $path) {
                if (file_exists("{$path}/{$__name__}.twig")) {
                    echo $this->_engine->render("{$__name__}.twig", $this->__data__);
                }
            }
        } else{
            if(array_key_exists('view_template_file', $this->__data__)) {
                throw new DViewException("Cannot bind variable called 'view_template_file'");
            }

            $partials = "{$this->_viewConfig['templateDir']}partials/";
            $inclusion = ($layout && (file_exists($partials && is_dir($partials)))) ? true : false;
            extract($this->__data__);
            ob_start();
            if($inclusion && file_exists("{$partials}header.php")) include("{$partials}header.php");
            include("{$this->_viewConfig['templateDir']}{$__name__}.php");
            if($inclusion && file_exists("{$partials}footer.php")) include("{$partials}footer.php");
            echo ob_get_clean();
        }

        return true;
    }
}
