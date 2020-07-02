<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Updateschool_ids extends CI_Controller {
 function __construct()    {        // Construct the parent class        parent::__construct();		$this->load->library("CI_Jwt"); 		$this->load->library("excel");         // Configure limits on our controller methods		die("<h1>Access Denied</h1>");;    }
 
	public function index()
	{		$i = 1;		$updated  = 0;		 $rs = $this->db->query("select * from tw_vendors");		 foreach($rs->result() as $row)		 {			$school_rs = $this->db->query("select * from schools where school_code=?",array($row->ddo_code));			if($school_rs->num_rows()>0)			{				$school_id  = $school_rs->row()->school_id;				$this->db->query("update tw_vendors set school_id=? where vendor_annapurna_id=?",array($row->vendor_annapurna_id));				$updated++;			}else			{				echo "<br> School Not found ".$i . " - ".$row->ddo_code."<br>";			}			$i++;		 }		 echo "Total Updated : ".$updated  ."<br>";	} 
}
