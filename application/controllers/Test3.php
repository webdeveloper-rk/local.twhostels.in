<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test3 extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	 function __construct()
    { 
        parent::__construct();
		$this->load->library("CI_Jwt");  
    }
	 public function index()
		{
			 print_a($this->session->all_userdata());
				  echo  $encoded_string = $this->ci_jwt->jwt_encode("hi this is ","rama kira");
				   
				    
		} 
		function test($val)
		{
			  $decoded_string = $this->ci_jwt->jwt_decode($val,"rama kira");
		}
}
