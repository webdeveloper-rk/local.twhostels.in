<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
set_time_limit(0);
 date_default_timezone_set('Asia/Kolkata');
class Loadmissed_data extends MX_Controller {

    function __construct() {
        parent::__construct();
		 
		$this->load->helper('url');
		$this->load->library('grocery_CRUD');
		$this->load->model('school_model');
		 $this->load->library('excel');
	}

    function index() {
		$sql = "select * from missed_school_items ";
		$rs= $this->db->query($sql);
		foreach($rs->result() as $school_row)
		{
				 
							$this->db->query("insert into balance_sheet set entry_date='2017-11-27' ,
										item_id='".$school_row->item_id."' ,school_id='".$school_row->school_id."',
										purchase_quantity='0',	purchase_price='0' ,
											closing_quantity='0',	closing_price='0',
												record_type='rk_inserted'
										");
						 
		}
		
        echo "Done";
    }
 
		 
}
