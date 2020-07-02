\<?php
set_time_limit (0);
defined('BASEPATH') OR exit('No direct script access allowed');

class Etest extends CI_Controller {

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
	public function index()
	{
		
		$subject = "RK TEST ".date('d-M-Y H:i:s A');
		 
		 $to_address_list =  '';
		 if(is_array($to_address))
			 $to_address_list = implode(",",$to_address);
		 else
			  $to_address_list =$to_address;
		  
				$body .= "<br><br>Email generated at ".date('d-M-Y h:i:s A') ."<br><br>".site_url()."<br>************";
				$result = $this->email
				->from('annapurna.smtp@gmail.com')
				->reply_to($to_address_list)    // Optional, an account where a human being reads.
				->to('webdeveloper.rk@gmail.com')
				->cc('webdeveloper.rk@gmail.com,innovebtechnologies@gmail.com ')
				->subject($subject)
				->message($body)
				->send(); 
	}
	
	 
 
}