<?php

class Locks_model extends CI_Model {

    

    function __construct() {
        parent::__construct();
    }
	
	
	function is_locked($locked_type,$checking_date)
	{
		$allowed_locks = array("attendance","balance_sheet");
		if(!in_array($locked_type,$allowed_locks))
		{
			die("Invalid Lock type.");
		}
		$is_locked = true;
		//$rs  = $this->db->query("select * from locks where lock_type=? and status='1' and   locked_date <=  ? ",array($locked_type,$checking_date));	
		$is_locked  = $this->db->query("select ? <=locked_date as is_locked from locks where lock_type=? and status='1'    ",
										array($checking_date,$locked_type ))->row()->is_locked;

		 
		// echo $this->db->last_query();
		 
		
		  //echo $is_locked;
		  return $is_locked;
	}
	
	function locked_date($lock_type)
	{
		$allowed_locks = array("attendance","balance_sheet");
		if(!in_array($lock_type,$allowed_locks))
		{
			die("Invalid Lock type.");
		}
		$locked_date = $this->db->query("select date_format(locked_date,'%d-%M-%Y') as entry_date_dp from locks where lock_type=? and status='1' order by locked_date desc limit 0,1",array($lock_type))->row()->entry_date_dp;  
		
		  return $locked_date;
	}
	
}
?>