<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Json_test extends CI_Controller {
 function __construct()    {        // Construct the parent class        parent::__construct();		$this->load->library("CI_Jwt");         // Configure limits on our controller methods    }
 
	public function index()
	{			$json_text = 'a:8:{i:3;s:2:"23";i:4;s:2:"46";i:5;s:2:"36";i:6;s:2:"41";i:7;s:2:"43";i:8;s:2:"57";i:9;s:2:"66";i:10;s:2:"94";}';			print_a(unserialize($json_text));	}	  
	 
}
