<?php
set_time_limit(0);
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Date extends MX_Controller {

    function __construct() {
        parent::__construct(); 
    } 
    function index() {		echo date('d-m-Y H:i:s ');
    } 
}

