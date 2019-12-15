<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
set_time_limit(0); 
 date_default_timezone_set('Asia/Kolkata');
class Purchase_consumption_bulk_twhostels extends MX_Controller {

    function __construct() {
        parent::__construct();
		if($this->uri->segment(2) !="login") { 
					 Modules::run("security/is_admin");		 
					
		}
			$this->load->helper('url');  
			$this->load->config("config.php");
			$this->load->config("opening_balance/config.php");
			$this->load->library("ci_jwt");
			$this->load->model("common/common_model");

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

   	public function index(){
		
		 $this->form_validation->set_rules('school_id', 'School', 'required|numeric|greater_than[0]');              
		$this->form_validation->set_rules('item_id', 'Item', 'required|numeric|greater_than[0]'); 
		$this->form_validation->set_rules('month_year', 'Month & Year', 'required');  
		 
		if($this->form_validation->run() == true )
		{
			  $start_date = $this->input->post('month_year')."-01";
			  $todate = date('Y-m-d',strtotime( $start_date));
			  $school_id = intval($this->input->post('school_id'));
			  $item_id = intval($this->input->post('item_id'));
			  $dataarray = array('school_id'=>$school_id,'item_id'=>$item_id,'start_date'=>$start_date);
			  //$this->check_is_less_than_opening_date($school_id,$item_id, $todate);
			  
			  //if date less than 2019-12-01 mark start date as 
			  
			  
			  
			  $balance_sheet_table = $this->common_model->get_stock_entry_table($todate);
			  
			  $cal_days_rs = $this->db->query("select * from calender where cal_date < CURRENT_DATE and cal_date between ? and last_day(?) ",array($start_date,$start_date));
			   // echo $this->db->last_query(),"<br>";
			  $newly_created = 0;
			  foreach($cal_days_rs->result() as $cal_row){
				  //check record exists for particular date if not redirect to form 
				  $cal_tdate = $cal_row->cal_date;
				  $sql = "select * from $balance_sheet_table where school_id=? and item_id=? and entry_date=? and ? between '2019-12-01' and current_date()";
				  $rs = $this->db->query($sql,array($school_id,$item_id,$cal_tdate,$cal_tdate));
				  //echo $this->db->last_query(),"<br>";
				  //echo $rs->num_rows();die;
				  if($rs->num_rows()==0)
				  {
					   if($this->createNonExistRecord($school_id,$item_id, $cal_tdate)){ 
							$this->common_model->update_entries($school_id,$item_id,$cal_tdate);
								$newly_created++; 
					   }
						 
				  }  
			  }
			  // if($newly_created >0)
				//$this->common_model->update_entries($school_id,$item_id,$start_date);
			  
			  //die;
			  
			  $encoded_data  = $this->ci_jwt->jwt_web_encode($dataarray );
			  redirect('purchase_consumption_bulk_twhostels/update/'.$encoded_data );
			  die;
			  
		} 
		
		 if($this->session->userdata("is_dco")=="1"){
				$district_condition = " and s.district_id='".$this->session->userdata("district_id")."'";
		 }
		 else{
				$district_condition = " ";
		 }
		 
		$drs = $this->db->query("SELECT s.*,d.name as dname,s.name as sname FROM  schools  s inner join districts d on s.`district_id`=d.district_id $district_condition ");         
        $data["schools"] = $drs; 
		
		
		
		$itemsrs = $this->db->query("SELECT * FROM  items where  status='1'");         
        $data["items"] = $itemsrs; 
        $data["module"] = "purchase_consumption_bulk_twhostels";        
        $data["view_file"] = "consumption_selection_form";
        echo Modules::run("template/admin", $data);
	 }

	function update($encoded_data=null)
	{
		
		 

			$decoded_data  = $this->ci_jwt->jwt_web_decode($encoded_data ); 
		 $school_id = $decoded_data->school_id;
		 $item_id= $decoded_data->item_id;
		 $start_date= $decoded_data->start_date;
		  //$this->check_is_less_than_opening_date($school_id,$item_id, $start_date);
		 
		 
		  
		 	//print_a($data["today_consumes"]);
		$drs = $this->db->query("SELECT s.*,d.name as dname,s.name as sname FROM  schools  s inner join districts d on s.`district_id`=d.district_id and s.school_id=?",array($school_id));         
        $data["school_info"] = $drs->row();
		
		//print_a($data["school_info"]);
		
		$stock_entry_table = $this->common_model->get_stock_entry_table($start_date);
		 $vendor_names = array();
		 $vendor_names[0] = "";
		 $vendor_rs = $this->db->query("select * from tw_vendors where school_id=?",array($school_id));
		 foreach($vendor_rs->result() as $vrow)
		 {
			$vendor_names[] = $vrow->vendor_name;
		 }
		 
		$days_rs = $this->db->query("select *,date_format(entry_date,'%d-%M-%Y') as display_date from  $stock_entry_table where school_id=? and item_id=? and entry_date between ? and last_day(?) order by entry_date asc ",array($school_id,$item_id, $start_date, $start_date));  
		 
		 //echo $this->db->last_query();
        
		$data["days_rs"] = $days_rs;
		$data["vendor_names"] = $vendor_names;
		$data["encoded_data"] = $encoded_data;
		$data["date_selected"] = date(' M-Y',strtotime($start_date));
		$data["date"] = $start_date ;
		$data["school_id"] = $school_id ;
		$data["item_id"] = $item_id ;
		$data["item_details"] = $this->db->query("select * from items where item_id=?",array($item_id))->row();
		 	
		      
        $data["module"] = "purchase_consumption_bulk_twhostels";  
		$data["view_file"] = "edit_form"; 
        echo Modules::run("template/admin", $data);
	}
	 
	 function createNonExistRecord($school_id,$item_id, $todate)
	 {
		 
		  $future_date = $this->db->query("SELECT ? > CURRENT_DATE as future_date ",array($todate))->row()->future_date;
		 if($future_date==1)
		 {
			 return true;
		 }
		 
		 
			$b_start_date = '2019-12-01';
			$b_end_date= date('Y-m-d');
			$allowed = $this->db->query("select    ? between ? and ?  as allowed",array($todate,$b_start_date,$b_end_date))->row()->allowed;
			
			//echo $this->db->last_query();
			//echo $allowed ; die;
			if($allowed ==0)
			{
				return false;//dont allow to create before dec 1st  2019 OR  after current date 
			}
			
			$sql =  "select * from  balance_sheet where school_id=? and item_id=? and entry_date=? ";
			$bsd_rs2 = $this->db->query($sql,array($school_id,$item_id,$todate));
			if($bsd_rs2->num_rows() > 0 )
			{
				return false ;// record already exists dont create 
			}
			
			
			
			$stock_entry_table = $this->common_model->get_stock_entry_table($todate);
			$sql =  "select max(entry_date) as edate from  $stock_entry_table where school_id=? and item_id=? and entry_date< ?";
			$rs = $this->db->query($sql,array($school_id,$item_id,$todate));
			 
			if($rs->num_rows()==0) {
					$this->session->set_flashdata('message', '<div class="alert alert-danger">No entries Found in $stock_entry_table. please Contact Administrator.</div>');
					redirect("purchase_consumption_bulk_twhostels");
			}
			$bsdata = $rs->row();
			$sql =  "select * from  $stock_entry_table where school_id=? and item_id=? and entry_date=? ";
			$bsd_rs = $this->db->query($sql,array($school_id,$item_id,$bsdata->edate));
			$bsd_data = $bsd_rs->row();
			$closing_quantity = $bsd_data->closing_quantity;
			if($closing_quantity=="" || $closing_quantity == NULL)
					$closing_quantity = 0.00;
			
			$check_rs = $this->db->query("select * from balance_sheet where school_id=? and item_id=? and entry_date=?",array($school_id,$item_id,$todate));
			if($check_rs->num_rows()==0){
					$ins_data = array('school_id'=>$school_id,'item_id'=>$item_id,'entry_date'=>$todate,'opening_quantity'=>$closing_quantity ,'closing_quantity'=>$closing_quantity );
					$this->db->insert($stock_entry_table ,$ins_data); 
			//echo $this->db->last_query();die;
			}
			return true;
	 }
	 private function check_is_less_than_opening_date($school_id,$item_id, $todate)
	 {
		 
		 $opening_academic_year = $this->config->item('opening_academic_year');
		
		$sql = "select *,? < opening_balance_date as ob_not_allowed,date_format(?,'%d-%M-%Y') as edit_date,
					date_format(opening_balance_date,'%d-%M-%Y') as ob_fmted_date   from 
								school_opening_balance where school_id=? and item_id=?   and  academic_year=?";
		  $rs = $this->db->query($sql,array($todate,$todate,$school_id,$item_id ,$opening_academic_year));
		 //  echo $this->db->last_query();die;
			 
			if($rs->num_rows()==0) {
				 $this->session->set_flashdata('message', '<div class="alert alert-danger">OB Table not found entry. please Contact Administrator.</div>');
					redirect("purchase_consumption_bulk_twhostels");
			}
			else
			{
				$ob_row =  $rs->row();
				$ob_not_allowed = $ob_row->ob_not_allowed;
				$edit_date= $ob_row->edit_date;
				$ob_fmted_date=$ob_row->ob_fmted_date;
				if($ob_not_allowed ==1 )
				{
					$this->session->set_flashdata('message', '<div class="alert alert-danger"> '.$edit_date.' is not allowed to update as selected item opening balance date is '.$ob_fmted_date.' </div>');
					redirect("purchase_consumption_bulk_twhostels");
				}
			}
	 }
	function delete_entry($entry_id)
	{
		$this->db->query("delete from balance_sheet where entry_id=? and (session_1_qty+session_2_qty+session_3_qty+session_4_qty)=0  and purchase_quantity=0 ",$entry_id);
	}
	
	public function ajax_monthly_data($encoded_data)
	{
		$decoded_data  = $this->ci_jwt->jwt_web_decode($encoded_data );
		 
		 $school_id = $decoded_data->school_id;
		 $item_id= $decoded_data->item_id;
		 $month_start_date= $decoded_data->start_date;
		 $stock_entry_table = $this->common_model->get_stock_entry_table($month_start_date);
		 $vendor_names = array();
		 $vendor_names[0] = "";
		 $vendor_rs = $this->db->query("select * from tw_vendors where school_id=?",array($school_id));
		 foreach($vendor_rs->result() as $vrow)
		 {
			$vendor_names[$vrow->vendor_annapurna_id] = $vrow->vendor_name;
		 }
		 
		$days_rs = $this->db->query("select *,date_format(entry_date,'%d-%M-%Y') as display_date from  $stock_entry_table where school_id=? and item_id=? and entry_date between ? and last_day(?) order by entry_date asc ",array($school_id,$item_id, $month_start_date, $month_start_date));  
		$rows_data= array();
		foreach($days_rs->result() as $day_data)
		{
			 $dataarray = array('school_id'=>$day_data->school_id,'item_id'=>$day_data->item_id,'date'=>$day_data->entry_date,'ajaxform'=>'yes');
			 $encoded_data  = $this->ci_jwt->jwt_web_encode($dataarray );
			 
			$rows_data[]= array($day_data->display_date,
								$day_data->opening_quantity,
								$day_data->purchase_quantity,
								$day_data->purchase_price,
								@$vendor_names[$day_data->vendor_annapurna_id],
								$day_data->session_4_qty
								,$day_data->session_4_price,
								$day_data->closing_quantity,
								anchor('purchase_consumption/update/'.$encoded_data, 'Edit ',  'class="purchase_info"')
			);
		}
		$ajax_response['data'] = $rows_data;
		header('Content-Type: application/json');
		echo json_encode($ajax_response);
		die;
	}
}
