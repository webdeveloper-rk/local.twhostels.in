<?php
set_time_limit (0);
defined('BASEPATH') OR exit('No direct script access allowed');

class Guestattendence  extends CI_Controller {

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
		$start_time  = time();
		libxml_disable_entity_loader(false); 
		
		 $this->load->library('webservices_lib');
			$date = $_GET['date'];		 
		 
		$school_codes = array();
		$school_rs  =  $this->db->query("select * from schools");
		foreach($school_rs->result() as $row){
			$school_codes[$row->school_code] = $row->school_id;
		}
		
		$attendene_report = array();
	 
		 
		$requestObj = new getStateWiseAttendance($date);
		$responseObj = $obj->getStateWiseAttendance($requestObj);
		$responseReturned = $responseObj->getReturn();
		
		 
		$classes_list = array();
		$school_list = array();
		$dst_list = array();
		
		foreach($responseReturned as $school_att_obj)
		{ 
			$school_list[ $school_codes[$school_att_obj->schoolcode]]['cat1'] =   intval($school_att_obj->cat1) ;
		}
	}
	
}