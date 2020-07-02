<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
set_time_limit(0); 
 date_default_timezone_set('Asia/Kolkata');
class Purchase_consumption_bulk extends MX_Controller {

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
			if($this->session->userdata("school_code") != "10100")
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
			 // $this->check_is_less_than_opening_date($school_id,$item_id, $todate);
			  
			  $balance_sheet_table = $this->common_model->get_stock_entry_table($todate);
			  
			  $cal_days_rs = $this->db->query("select * from calender where cal_date < CURRENT_DATE and cal_date between ? and last_day(?) ",array($start_date,$start_date));
			   // echo $this->db->last_query(),"<br>";
			  $newly_created = 0;
			  foreach($cal_days_rs->result() as $cal_row){
				  //check record exists for particular date if not redirect to form 
				  $cal_tdate = $cal_row->cal_date;
				  $sql = "select * from $balance_sheet_table where school_id=? and item_id=? and entry_date=?";
				  $rs = $this->db->query($sql,array($school_id,$item_id,$cal_tdate));
				 // echo $this->db->last_query(),"<br>";
				  if($rs->num_rows()==0)
				  {
					  $this->createNonExistRecord($school_id,$item_id, $cal_tdate); 
					    //echo $this->db->last_query(),"<br>";
						$newly_created++; 
				  }  
			  }
			   if($newly_created >0)
				$this->common_model->update_entries($school_id,$item_id,$start_date);
			  
			  //die;
			  
			  $encoded_data  = $this->ci_jwt->jwt_web_encode($dataarray );
			  redirect('purchase_consumption_bulk/update/'.$encoded_data );
			  die;
			  
		} 
		$drs = $this->db->query("SELECT s.*,d.name as dname,s.name as sname FROM  schools  s inner join districts d on s.`district_id`=d.district_id");         
        $data["schools"] = $drs; 
		$itemsrs = $this->db->query("SELECT * FROM  items where  status='1'");         
        $data["items"] = $itemsrs; 
        $data["module"] = "purchase_consumption_bulk";        
        $data["view_file"] = "consumption_selection_form";
        echo Modules::run("template/admin", $data);
	 }

	function update($encoded_data=null)
	{
		
		 

		$decoded_data  = $this->ci_jwt->jwt_web_decode($encoded_data );
		 $school_id = $decoded_data->school_id;
		 $item_id= $decoded_data->item_id;
		 $start_date= $decoded_data->start_date;
		 // $this->check_is_less_than_opening_date($school_id,$item_id, $start_date);
		 
		 $stock_entry_table = $this->common_model->get_stock_entry_table($start_date);
		 
		   
			  
		 
		 
		 
		  
		 
		if($this->input->post("submit") == "Submit" )
		{
			
			//print_a($_POST );
		  $date_chk_rs =  $this->db->query("select * from refer_balance_sheet where ? between start_date and end_date and is_current=1",array( $start_date));
		  
		  if($date_chk_rs->num_rows() != 1 ){
				$this->session->set_flashdata('message', '<div class="alert alert-danger">Record not allowed to  update for the selected date. please contact administrator.</div>');
					redirect("purchase_consumption_bulk/update/".$encoded_data);
		  }
		$purchase_qty_lists = $this->input->post("purchase_qty"); 
		$purchase_price_lists = $this->input->post("purchase_price"); 
		
		$bf_qty_lists = $this->input->post("bf_qty"); 
		$bf_price_lists = $this->input->post("bf_price"); 
		
		$lunch_qty_lists = $this->input->post("lunch_qty"); 
		$lunch_price_lists = $this->input->post("lunch_price"); 
		
		$snacks_qty_lists = $this->input->post("snacks_qty"); 
		$snacks_price_lists = $this->input->post("snacks_price"); 
		
		$dinner_qty_lists = $this->input->post("dinner_qty"); 
		$dinner_price_lists = $this->input->post("dinner_price"); 
		 
		// print_a($purchase_qty_lists );
		$entry_ids = array_keys($purchase_qty_lists);
		 // print_a($entry_ids );
		foreach( $entry_ids  as $entry_id ){
		  
		 $update_data = array( 'purchase_quantity'=>floatval($purchase_qty_lists[$entry_id]),
							'purchase_price'=>floatval($purchase_price_lists[$entry_id]),

							'session_1_old_qty'=>0,
							'session_1_old_price'=>0,
							'session_1_new_qty'=>floatval($bf_qty_lists[$entry_id]),
							'session_1_new_price'=>floatval($bf_price_lists[$entry_id]),						
							'session_1_qty'=>floatval($bf_qty_lists[$entry_id]),
							'session_1_price'=>floatval($bf_price_lists[$entry_id]),
							
							'session_2_old_qty'=>0,
							'session_2_old_price'=>0,
							'session_2_new_qty'=>floatval($lunch_qty_lists[$entry_id]),
							'session_2_new_price'=>floatval($lunch_price_lists[$entry_id]),
							'session_2_qty'=>floatval($lunch_qty_lists[$entry_id]),
							'session_2_price'=>floatval($lunch_price_lists[$entry_id]),

							'session_3_old_qty'=>0,
							'session_3_old_price'=>0,
							'session_3_new_qty'=>floatval($snacks_qty_lists[$entry_id]),
							'session_3_new_price'=>floatval($snacks_price_lists[$entry_id]),
							'session_3_qty'=>floatval($snacks_qty_lists[$entry_id]),
							'session_3_price'=>floatval($snacks_price_lists[$entry_id]),

							'session_4_old_qty'=>0,
							'session_4_old_price'=>0,
							'session_4_new_qty'=>floatval($dinner_qty_lists[$entry_id]),
							'session_4_new_price'=>floatval($dinner_price_lists[$entry_id]),
							'session_4_qty'=>floatval($dinner_qty_lists[$entry_id]),
							'session_4_price'=>floatval($dinner_price_lists[$entry_id])
							);
							
		  
			 
			$this->db->where('entry_id',$entry_id);
			$this->db->update($stock_entry_table, $update_data); 
			//echo $this->db->last_query(),"<br>";
		}			
			
			$this->common_model->update_entries($school_id,$item_id,$start_date);
			$this->session->set_flashdata('message', '<div class="alert alert-success">Updated Successfully.</div>');
			//die;
			redirect('purchase_consumption_bulk/update/'.$encoded_data);
		}
		
		//print_a($data["today_consumes"]);
		$drs = $this->db->query("SELECT s.*,d.name as dname,s.name as sname FROM  schools  s inner join districts d on s.`district_id`=d.district_id and s.school_id=?",array($school_id));         
        $data["school_info"] = $drs->row();
        
		$data["date_selected"] = date(' M-Y',strtotime($start_date));
		$data["date"] = $start_date ;
		$data["school_id"] = $school_id ;
		$data["item_id"] = $item_id ;
		$data["item_details"] = $this->db->query("select * from items where item_id=?",array($item_id))->row();
		 	
		 
		$form_data = array('pqty'=>0,'pprice'=>'0','bf_qty'=>'0','bf_price'=>'0','lu_qty'=>'0','lu_price'=>'0','sn_qty'=>'0','sn_price'=>'0','di_qty'=>'0','di_price'=>'0');
		 
				
				 
 
        $data["days_rs"] = $this->db->query("select *,date_format(entry_date,'%d-%M-%Y') as display_date from  $stock_entry_table where school_id=? and item_id=? and entry_date between ? and last_day(?) order by entry_date asc ",array($school_id,$item_id, $start_date, $start_date));  
		// echo $this->db->last_query(); 
        $data["form_data"] = $form_data;        
        $data["module"] = "purchase_consumption_bulk";  
		$data["view_file"] = "edit_form"; 
        echo Modules::run("template/admin", $data);
	}
	 
	 function createNonExistRecord($school_id,$item_id, $todate)
	 {
			$stock_entry_table = $this->common_model->get_stock_entry_table($todate);
			$sql =  "select max(entry_date) as edate from  $stock_entry_table where school_id=? and item_id=? and entry_date< ?";
			$rs = $this->db->query($sql,array($school_id,$item_id,$todate));
			 
			if($rs->num_rows()==0) {
					$this->session->set_flashdata('message', '<div class="alert alert-danger">No entries Found in $stock_entry_table. please Contact Administrator.</div>');
					redirect("purchase_consumption_bulk");
			}
			$bsdata = $rs->row();
			$sql =  "select * from  $stock_entry_table where school_id=? and item_id=? and entry_date=? ";
			$bsd_rs = $this->db->query($sql,array($school_id,$item_id,$bsdata->edate));
			$bsd_data = $bsd_rs->row();
			$closing_quantity = $bsd_data->closing_quantity;
			if($closing_quantity=="" || $closing_quantity == NULL)
					$closing_quantity = 0.00;
			$ins_data = array('school_id'=>$school_id,'item_id'=>$item_id,'entry_date'=>$todate,'opening_quantity'=>$closing_quantity ,'closing_quantity'=>$closing_quantity );
			$this->db->insert($stock_entry_table ,$ins_data); 
			//echo $this->db->last_query();die;
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
					redirect("purchase_consumption_bulk");
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
					redirect("purchase_consumption_bulk");
				}
			}
	 }
	function delete_entry($entry_id)
	{
		$this->db->query("delete from balance_sheet where entry_id=? and (session_1_qty+session_2_qty+session_3_qty+session_4_qty)=0  and purchase_quantity=0 ",$entry_id);
	}
}
