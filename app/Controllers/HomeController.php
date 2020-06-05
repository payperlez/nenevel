<?php

namespace App\Controllers;
use DIY\Base\DController;
use DIY\Base\Utils\DUtil;
use DIY\Base\Utils\Session;

class HomeController extends DController {
    public function __construct(){
        parent::__construct();
    }

    public function index(){
        $this->view->message = "Hello, World!";
        $this->view->render("home/index");
    }
}
