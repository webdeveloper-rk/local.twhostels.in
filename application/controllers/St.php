<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class St extends CI_Controller {

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
		  $rs = $this->db->query("select * from  class_student_strengths");
		  $list = array();
		  foreach($rs->result() as $row)
		  {
			  
			 $this->db->query("update   school_class_strengths set strength=? where school_id=? and class_id=? ",array($row->class_1,$row->school_id,1));
			 
			  $this->db->query("update   school_class_strengths set strength=? where school_id=? and class_id=? ",array($row->class_2,$row->school_id,2));
			  
			   $this->db->query("update   school_class_strengths set strength=? where school_id=? and class_id=? ",array($row->class_3,$row->school_id,3));
			   
			    $this->db->query("update   school_class_strengths set strength=? where school_id=? and class_id=? ",array($row->class_4,$row->school_id,4));
				
				 $this->db->query("update   school_class_strengths set strength=? where school_id=? and class_id=? ",array($row->class_5,$row->school_id,5));
				 
				  $this->db->query("update   school_class_strengths set strength=? where school_id=? and class_id=? ",array($row->class_6,$row->school_id,6));
				  
				   $this->db->query("update   school_class_strengths set strength=? where school_id=? and class_id=? ",array($row->class_7,$row->school_id,7));
				   
				    $this->db->query("update   school_class_strengths set strength=? where school_id=? and class_id=? ",array($row->class_8,$row->school_id,8));
					
					 $this->db->query("update   school_class_strengths set strength=? where school_id=? and class_id=? ",array($row->class_9,$row->school_id,9));
					 
					  $this->db->query("update   school_class_strengths set strength=? where school_id=? and class_id=? ",array($row->class_10,$row->school_id,10));
					  
					   $this->db->query("update   school_class_strengths set strength=? where school_id=? and class_id=? ",array($row->inter_1,$row->school_id,11));
					   
					    $this->db->query("update   school_class_strengths set strength=? where school_id=? and class_id=? ",array($row->inter_2,$row->school_id,12));
						
						 $this->db->query("update   school_class_strengths set strength=? where school_id=? and class_id=? ",array($row->degree_1,$row->school_id,13));
						 
						  $this->db->query("update   school_class_strengths set strength=? where school_id=? and class_id=? ",array($row->degree_2,$row->school_id,14));
						  
						   $this->db->query("update   school_class_strengths set strength=? where school_id=? and class_id=? ",array($row->degree_3,$row->school_id,15));
						   
			  
		  }
	}
	
	
	
	
	//old reports link
	
}
