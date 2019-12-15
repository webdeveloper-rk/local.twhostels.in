<?php
set_time_limit(0);
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Date extends CI_Controller {

    function __construct() {
        parent::__construct(); 
    } 
    function index() {		echo date('d-m-Y H:i:s ');						/* echo shell_exec("ls -l");		echo shell_exec("mysql  ");		echo shell_exec("show databases;");				*/
    } 
}

