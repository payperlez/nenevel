<?php

namespace App\Controllers;
use NENEVEL\Base\DController;
use NENEVEL\Base\Utils\DUtil;
use NENEVEL\Base\Utils\Session;

class HomeController extends DController {
    public function __construct(){
        parent::__construct();
    }

    public function index(){
        $this->view->message = "Hello, World!";
        $this->view->render("home/index");
    }
}
