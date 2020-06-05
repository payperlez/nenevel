<?php

/**
 * @property View $view
 * @property Flash $flash
 * @property TemplateFactory $flashTemplate
 * @author      Obed Ademang <kizit2012@gmail.com>
 * @copyright   Copyright (C), 2015 Obed Ademang
 * @license     MIT LICENSE (https://opensource.org/licenses/MIT)
 *              Refer to the LICENSE file distributed within the package.
 *
 */

namespace DIY\Base;
use \Tamtamchik\SimpleFlash\Flash;
use \Tamtamchik\SimpleFlash\TemplateFactory;
use \Tamtamchik\SimpleFlash\Templates;

class DController extends BaseController {
    public $view;
    public $flash;
    public $flashTemplate;

    public function __construct() {
        parent::__construct();

        $this->flashTemplate = TemplateFactory::create(Templates::BOOTSTRAP_4);
        $this->view = new View();
        $this->flash = new Flash();
        $this->flash->setTemplate($this->flashTemplate);

        $this->view->csrf_token = csrf_token();
        $this->view->base_url = BASE_URL;
        $this->view->current_url = url()->getOriginalUrl();
        $this->view->static = STATIC_URL;
        $this->view->media = MEDIA_URL;
    }
}
