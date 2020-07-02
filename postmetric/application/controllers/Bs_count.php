<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bs_count extends CI_Controller {
 function __construct()    {        // Construct the parent class        parent::__construct();		$this->load->library("CI_Jwt");         // Configure limits on our controller methods    }
 
	public function index() 
	{		$duplicates_rs = $this->db->query("select * from duplicate_records  ");		foreach($duplicates_rs->result() as $dup_row)		{			$school_id = $dup_row->school_id;			$item_id = $dup_row->item_id;			$entry_date = $dup_row->entry_date;			 $bs_list =$this->db->query("select * from balance_sheet where school_id=? and item_id=? and entry_date=?  "										,array($school_id,$item_id,$entry_date));			 			 $bs_count = $bs_list->num_rows();						 $this->db->query("update duplicate_records set bs_count=? where school_id=? and item_id=? and entry_date=?",			array($bs_count,$school_id,$item_id,$entry_date));								}		echo "Done";	}	public function showcount()	{		$rs = $duplicates_rs = $this->db->query("SELECT school_id,item_id,entry_date,count(*) as record_count FROM `balance_sheet` 	group by school_id,item_id,entry_date having record_count > 1");		echo "Duplicates : " . $rs->num_rows();	}	  
}
