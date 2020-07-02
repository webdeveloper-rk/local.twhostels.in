<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Update_ehostel_ids extends CI_Controller {
 function __construct()    {        // Construct the parent class        parent::__construct();		$this->load->library("CI_Jwt"); 		$this->load->library("excel");         // Configure limits on our controller methods    }
 
	public function index()
	{		$total_updated =  0;		 $schools_rs  = $this->db->query("select * from schools where is_school='1'");		 foreach( $schools_rs->result() as $row)		 {			$school_ddo_code = $row->school_code;			$school_id = $row->school_id;			$vendor_data_rs  = $this->db->query("select * from tw_vendors where ddo_code=?",array($school_ddo_code));			if($vendor_data_rs->num_rows()>0)			{				$vendor_info = $vendor_data_rs->row();				$this->db->query("update schools set ehostel_id=? where school_id=?",array($vendor_info->ehostel_id,$school_id));				$total_updated++;			}		 }		 echo "Total Updated :".$total_updated;	} 
}
