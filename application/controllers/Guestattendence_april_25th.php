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
		
		 $this->load->library('webservices_lib');		 $date = '';		 if(isset($_GET['date']))
			$date = $_GET['date'];		 		 else 				$date  = date('Y-m-d'); 
		 
		$school_codes = array();
		$school_rs  =  $this->db->query("select * from schools");
		foreach($school_rs->result() as $row){
			$school_codes[$row->school_code] = $row->school_id;
		}
		
		$attendene_report = array();
	 
		 		$obj = new HasService(array(),'http://182.18.156.60:9090/swfguestap/services/HasService?wsdl');		 		
		$requestObj = new getStateWiseAttendance($date);
		$responseObj = $obj->getStateWiseAttendance($requestObj);
		$responseReturned = $responseObj->getReturn();
		
		 
		$classes_list = array();
		$school_list = array();
		$dst_list = array();
		
		foreach($responseReturned as $school_att_obj)
		{ 				//SKIP Attendence for CHINA TEKUR				/*if( $school_att_obj->schoolcode == "41309")				{					continue;				}				*/
			$school_list[ $school_codes[$school_att_obj->schoolcode]]['cat1'] =   intval($school_att_obj->cat1) ;			$school_list[ $school_codes[$school_att_obj->schoolcode]]['cat2'] =   intval($school_att_obj->cat2) ;			$school_list[ $school_codes[$school_att_obj->schoolcode]]['cat3'] =   intval($school_att_obj->cat3) ;			$school_list[ $school_codes[$school_att_obj->schoolcode]]['school_code'] =  $school_att_obj->schoolcode ;
		}		foreach($school_list as $school_id=>$school_att)		{			$sql = "update school_attendence set cat1_guest_attendence = '".$school_att['cat1']."',												 cat2_guest_attendence = '".$school_att['cat2']."',												 cat3_guest_attendence = '".$school_att['cat3']."' 												 												 where school_id='".$school_id."' and 	entry_date='".$date."'  ";			$this->db->query($sql);  		}				$tssql = "update school_attendence set 						present_count =   cat1_attendence +  cat2_attendence + 									cat3_attendence  + cat1_guest_attendence+ cat2_guest_attendence+cat3_guest_attendence  					where  entry_date='".$date."'  ";		$this->db->query($tssql);					echo "<br>Completed of Date ".$date;		 
	}
	
}