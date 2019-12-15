<?php 
 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
set_time_limit(0);
 date_default_timezone_set('Asia/Kolkata');
class Spenders extends MX_Controller {

    function __construct() {
        parent::__construct();
		if($this->uri->segment(2) !="login") { 
					 Modules::run("security/is_admin");		 
					if ($this->session->userdata("is_loggedin") != TRUE || $this->session->userdata("user_id") == "" ) {
							redirect("admin/login");
							die;
					}
					 			
					if($this->session->userdata("user_role") != "subadmin")
					{
						redirect("admin/login");
							die;
					}
		}
		$this->load->helper('url'); 
		 
		 $this->load->library('excel');
		 $this->load->library('ci_jwt');
	}

    function index() {
         redirect('spenders/amountwise/');
    }
 
	
	function amountwise() {
		 
		 if($this->input->post('school_date')!="")
		 {
			 
			 $school_date = date('Y-m-d',strtotime($this->input->post('school_date')));
			 
			  $encoded_date = $this->ci_jwt->jwt_web_encode($school_date);
			 redirect('spenders/amountwise_report/'. $encoded_date);
			 die;
		 }
        $data["school_code"] = "";
        $data["module"] = "spenders";
        $data["view_file"] = "amountwise";
        echo Modules::run("template/admin", $data);
    }
	function amountwise_report($encoded_date=null)
	{
		 $date = $this->ci_jwt->jwt_web_decode($encoded_date);
		 
		if($date==null)
				$date = date('Y-m-d');
			
		  $report_date = date('Y-m-d',strtotime($date));
		 
		$sql = "SELECT 
						sc.school_id,sc.name,sc.school_code ,entry_date, 
						 sum(	(session_1_qty* session_1_price ) + 
								(session_2_qty* session_2_price ) + 
								(session_3_qty* session_3_price ) + 
								(session_4_qty* session_4_price ) 
								) as amount_used
						 FROM balance_sheet  bs inner join items it on it.item_id  = bs.item_id  
						 inner join schools sc on   bs.school_id  = sc.school_id  
						 WHERE   entry_date=?  and sc.school_code not like '%85000%' group by bs.school_id  
						 order by amount_used desc";
	 
		$rs = $this->db->query($sql,array($report_date));
		$data["rset"] = $rs;
		
		
		 
		$data["report_date"] = date('d-m-Y',strtotime($date));
		$data["rdate"] = date('Y-m-d',strtotime($date));
		$data["module"] = "spenders";
		$data["module"] = "spenders";
		$data["view_file"] = "amountwise_list";
		echo Modules::run("template/admin", $data);
		
	}
	/*****************************************************************************************
	
	
	
	
	
	*****************************************************************************************/
	function itemwise() {
		 
		 if($this->input->post('school_date')!="")
		 {
			 
			 $school_date = date('Y-m-d',strtotime($this->input->post('school_date')));
			 
			    $encoded_date = $this->ci_jwt->jwt_web_encode($school_date);
			 redirect('spenders/itemwise_report/'. $encoded_date);
			 die;
		 }
        $data["school_code"] = "";
        $data["module"] = "spenders";
        $data["view_file"] = "itemwise";
        echo Modules::run("template/admin", $data);
    }
	function itemwise_report($encoded_date=null)
	{
		 $date = $this->ci_jwt->jwt_web_decode($encoded_date);
		if($date==null)
				$date = date('Y-m-d');
			
		  $report_date = date('Y-m-d',strtotime($date));
		 
		$sql = "SELECT 
		sc.school_id,sc.name,sc.school_code,it.item_name,it.telugu_name  , 
		entry_date,
		it.item_id,
		( opening_quantity + purchase_quantity) as total_quantity, 
		 ( opening_quantity + purchase_quantity) - closing_quantity as used_qty , 
		 (
				(session_1_qty* session_1_price ) + 
				(session_2_qty* session_2_price ) + 
				(session_3_qty* session_3_price ) + 
				(session_4_qty* session_4_price )  ) as total_price   
		 FROM balance_sheet  bs inner join items it on it.item_id  = bs.item_id  
		 inner join schools sc on   bs.school_id  = sc.school_id  
			WHERE (
							(session_1_qty* session_1_price ) + 
							(session_2_qty* session_2_price ) + 
							(session_3_qty* session_3_price ) + 
							(session_4_qty* session_4_price )  ) > 500  and entry_date=?   and sc.school_code not like '%85000%'  order by total_price desc";
	 
		$rs = $this->db->query($sql,array($report_date));
		$data["rset"] = $rs;
		
		
		 
		$data["report_date"] = date('d-m-Y',strtotime($date));
		$data["module"] = "spenders";
		$data["module"] = "spenders";
		$data["view_file"] = "itemwise_list";
		echo Modules::run("template/admin", $data);
		
	}
	
	/********************************************************
	
	
	***********************************************************/
	public function items_list_popup($encoded_params)
	{
		
		$decoded_params = $this->ci_jwt->jwt_web_decode($encoded_params);
		 
		
		$school_id=$decoded_params->school_id;
		$date=$decoded_params->rdate;
		
		 
		$school_id = intval($school_id);
		if($school_id==0)
		{
			echo "<h1>Invalid School</h1>";
			return;
		}
		else
		{
			
			if($date==null)
				$date = date('Y-m-d');
			
		  $report_date = date('Y-m-d',strtotime($date));
		  
			 $sql = "select bs.*,it.telugu_name,it.item_name,
			 
			 (session_1_qty+ session_2_qty+session_3_qty+session_4_qty )  as total_qty,
						(
				(session_1_qty* session_1_price ) + 
				(session_2_qty* session_2_price ) + 
				(session_3_qty* session_3_price ) + 
				(session_4_qty* session_4_price )  ) as total_price 
			from 
			balance_sheet bs inner join items it on it.item_id = bs.item_id and bs.entry_date=?  and bs.school_id=? order by total_price desc ";
			
			$rs = $this->db->query($sql,array($report_date,$school_id));
			$data['rdate'] = date('d-m-Y',strtotime($date));
			$data['rset'] = $rs;
			$data['sname'] = $this->db->query("select * from schools where school_id=?",array($school_id))->row()->name;
			$this->load->view("itemwise_list_popup",$data);
			
		}
		
		
		
	}
	
}
