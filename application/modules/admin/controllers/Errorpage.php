<?php
set_time_limit(0);
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Errorpage extends MX_Controller {

    function __construct() {
        parent::__construct(); 		if ($this->session->userdata("is_loggedin") != TRUE || $this->session->userdata("user_id") == "" ) {							redirect("errorpage");							die;					}					$this->load->library("ci_jwt");
    } 
    function index() {		 		 $data["module"] = "admin";        $data["view_file"] = "error_page";        echo  Modules::run("template/admin", $data);
    } 
}

