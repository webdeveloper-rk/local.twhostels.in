<?php
set_time_limit (0);
defined('BASEPATH') OR exit('No direct script access allowed');

class Fixeditems extends CI_Controller {

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
		 die;
	}	public function fuelcharges_deleted(){		$fuel_item_id = 243;//Fuel Item _id database id hard coded				if(isset($_GET['date']))		{				$date = $_GET['date'];		}		 else		 {			 	$date = date('Y-m-d');		 }		  		 				/*******************************************				*******************************************/				$query_updated =  " update  										balance_sheet bs inner join 										school_attendence sa on sa.entry_date = bs.entry_date  and bs.entry_date='$date'  																							   and sa.entry_date='$date'  inner join 										fixed_rates fr on fr.item_id = bs.item_id and bs.item_id = '$fuel_item_id' 																					and bs.item_id= fr.item_id 																					and fr.school_id = bs.school_id 																					and sa.school_id= bs.school_id 																					and fr.school_id=sa.school_id 										 										 set										 											purchase_quantity 	= 	sa.present_count,											purchase_price 		= 	fr.amount ,											session_4_new_qty 	= 	sa.present_count,											session_4_new_price = 	fr.amount ,											session_4_qty 	= 	sa.present_count,											session_4_price	= 	fr.amount ,											bs.updated_time = now()											";				 				//echo $query_updated ;die;				$this->db->query($query_updated);				echo $this->db->affected_rows();				echo " - rows affected";										/*******************************************				*******************************************/		 		 	}		public function fuelcharges()	{		$fuel_item_id = 243;//Fuel Item _id database id hard coded				if(isset($_GET['date']))		{				$date = $_GET['date'];		}		 else		 {			 	$date = date('Y-m-d');		 }		 $school_codes_condition = " ";		 if(isset($_GET['school_codes']))//_seperated 		{				$school_codes = $_GET['school_codes'];				$school_codes = str_replace("_",",",$school_codes);				$school_codes_condition = " and school_code in (".$school_codes.") ";						}		 	  	$sql = "select school_id,						(cat1_attendence+cat2_attendence+cat3_attendence+	cat1_guest_attendence+ cat2_guest_attendence+cat3_guest_attendence	) as total_attendence 					from school_attendence where entry_date='$date'";		$rs = $this->db->query($sql);		$attendence_counts = array();		foreach($rs->result() as $row)		{			$attendence_counts[$row->school_id]= $row->total_attendence;		}			 // print_a($attendence_counts);	 		$schools_items = array();					$fr_sql = "select * from  fixed_rates where item_id='$fuel_item_id'  $school_codes_condition ";		$fr_rs = $this->db->query($fr_sql);		foreach($fr_rs->result() as $fs_row)		{			$school_id = 	$fs_row->school_id;			$item_price = 	$fs_row->amount;			 			 $purchase_quantity = intval($attendence_counts[$school_id]);				$total_amount = $purchase_quantity * $item_price;				 $schools_items[$school_id]['purchase_quantity'] = $purchase_quantity;				 $schools_items[$school_id]['total_amount'] = $total_amount;				 $schools_items[$school_id]['item_price'] = $item_price;											 		}					 			// print_a( $schools_items,0);													/*								[purchase_quantity] => 440            [total_amount] => 1100            [item_price] => 55						*/						$bs_sql = " select * from  balance_sheet where  item_id='$fuel_item_id'   and entry_date='$date'";			$bs_rs = $this->db->query($bs_sql);						$count_updated = 0;			foreach($bs_rs->result() as $bs_row){				//print_a($bs_row,0);								$entry_id = $bs_row->entry_id;				 $school_id = $bs_row->school_id;				$purchase_quantity = $schools_items[$school_id]['purchase_quantity'];				$item_price = $schools_items[$school_id]['item_price'];				$query_updated =  "update  balance_sheet set 																		opening_quantity='0.00',																		opening_price='0.00',																		purchase_quantity='$purchase_quantity',																		purchase_biil_no='00000',																		purchase_price='$item_price',																		session_4_new_qty='$purchase_quantity',																		session_4_qty='$purchase_quantity',																		session_4_new_price='$item_price',																		session_4_price='$item_price',																		closing_quantity='0.00',																		closing_price='0.00',																		created_time=now() 																												where 																		entry_id =	'$entry_id'	";				// echo $query_updated ;die;				 $this->db->query($query_updated);				 $count_updated++;			}																						 //echo $query_updated; 				 echo ";<br>";																				echo "Done - ".$count_updated  . " Records updated.";						}						 
	
	
}