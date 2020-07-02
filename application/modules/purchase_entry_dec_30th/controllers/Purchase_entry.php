<?php 
 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
set_time_limit(0);
 date_default_timezone_set('Asia/Kolkata');
class Purchase_entry extends MX_Controller {

    function __construct() {
        parent::__construct();
		if($this->uri->segment(2) !="login") { 
					 Modules::run("security/is_admin");		 
					if ($this->session->userdata("is_loggedin") != TRUE || $this->session->userdata("user_id") == "" ) {
							redirect("admin/login");
							die;
					}
					 			
					if($this->session->userdata("user_role") != "school")
					{
						redirect("admin/login");
							die;
					}
		}
		$this->load->helper('url');
		$this->load->library('grocery_CRUD'); 
		$this->load->library('ci_jwt'); 
		$this->load->model('purchase_model'); 
		$this->load->model('consumption_entry/consumption_model'); 
		$this->load->model('common/common_model'); 
		$this->load->config('config'); 
		 
		 
	}
	function index()
	{
		 
		$data["today_purchases"] = $this->purchase_model->get_balance_entries_today($this->session->userdata("school_id"),date('Y-m-d'));
		$drs = $this->db->query("select * from  items where status='1' and 	allowed_to_edit='1'");         
        $data["rset"] = $drs;
		
		
		$data["module"] = "purchase_entry";
        $data["view_file"] = "purchase_entry";
        echo Modules::run("template/admin", $data);
         
	}
	function install()
	{
		 
		if (!$this->db->table_exists('purchases'))
		{
				 $create_sql = "CREATE TABLE IF NOT EXISTS `purchases` (
								  `pid` int(11) NOT NULL AUTO_INCREMENT,
								  `school_id` int(11) NOT NULL,
								  `item_id` int(11) NOT NULL,
								  `purchase_date` date NOT NULL,
								  `quantity` decimal(10,3) NOT NULL,
								  `purchase_price` decimal(10,2) DEFAULT NULL,
								  PRIMARY KEY (`pid`),
								  KEY `school_id` (`school_id`),
								  KEY `item_id` (`item_id`),
								  KEY `purchase_date` (`purchase_date`)
								) ENGINE=MyISAM DEFAULT CHARSET=latin1;";
				$this->db->query($create_sql);
			$this->db->query("insert into purchases(school_id,item_id, purchase_date, quantity,purchase_price) select school_id,item_id,entry_date as purchase_date,purchase_quantity as quantity,purchase_price from balance_sheet where purchase_quantity>0");
			echo "Done";
		}
         
	}
	
	
	
	
	/*
	
	*/
	function purchase_entryform($item_id=null)
	{
		
		injection_check();
		$encode_item_id = $item_id;		
				
		$item_id = $this->ci_jwt->jwt_web_decode($item_id);	
		$entry_date			=	$this->today_date();	
		$school_id	=	$this->session->userdata("school_id");
		
		
				 $sql = "select * from balance_sheet where school_id=? and item_id=? and entry_date=?";
			  $rs = $this->db->query($sql,array($school_id,$item_id,$entry_date));
			   
			  if($rs->num_rows()==0)
			  {
					$this->createNonExistRecord($school_id,$item_id, $entry_date); 
					redirect('purchase_entry/purchase_entryform/'.$encode_item_id );
			  } 
		
		
		$price_data  =	$data['price_data'] =   $this->consumption_model->get_price_details($school_id,$item_id,$entry_date);
		
	  
		
		$this->form_validation->set_rules('quantity', 'Quantity', 'required|numeric|greater_than[0]');              
		$this->form_validation->set_rules('price', 'Price', 'required|numeric|greater_than[0]'); 
		$this->form_validation->set_rules('billno', 'Bill Number', 'numeric|greater_than_equal_to[0]'); 
		$this->form_validation->set_rules('vendor_id', 'Vendor name', 'numeric'); 
		
		 
		
		$date			=	$this->today_date();	
		$school_id	=	$this->session->userdata("school_id");
		 $stock_entry_table = $this->common_model->get_stock_entry_table($date);
			 
		$allow_to_modify = true;
	
	  $stock_entry_table = $this->common_model->get_stock_entry_table($date);
		 $school_id	=	$this->session->userdata("school_id");
		 $sql = "select * from $stock_entry_table where entry_date=? and school_id=? and item_id=?  	";
		$rs = $this->db->query($sql,array($date,  $school_id,$item_id));
		   
		$data["today_purchases"] = $purchased_item_row = $rs->row();
		 
			//print_a($data["today_purchases"]);
			if($data["today_purchases"]->purchase_price == 0)
			{
				 $district_prices_rs =$this->db->query("select * from  district_item_prices where  district_id=?  and item_id=? and status=1  order by price_id desc limit 0,1",array($this->session->userdata("district_id"), $item_id));
				 $data["item_price"] = 0;
				 if($district_prices_rs->num_rows()>0)
				 {
					$data["item_price"] =$district_prices_rs->row()->price;
				 }
				 
				//$data["item_price"] =$this->common_model->get_item_fixed_price($item_id,$school_id);
			}else
			{
				$data["item_price"] =$data["today_purchases"]->purchase_price;
			}
			
		
		if($this->form_validation->run() == true && $allow_to_modify  == true && $this->input->post('action')=="submit")
		 {
			 
			 $inputs_array['school_id'] =  $school_id;
				$inputs_array['item_id'] =   $item_id;
				$inputs_array['entry_date'] = $date;

				$inputs_array['pqty'] = floatval($this->input->post('quantity') );
				$inputs_array['pprice'] =	floatval($this->input->post('price'));
				$vendor_id	=	intval($this->input->post('vendor_id'));
				
				 $bsql = "select *  from $stock_entry_table where entry_date=? and school_id=? and item_id=? 	";
				$brs = $this->db->query($bsql,array($date ,$school_id,$item_id));
				$brow = $brs->row();

				$inputs_array['bf_qty'] = $brow->session_1_qty;
				$inputs_array['bf_price'] = $brow->session_1_price;

				$inputs_array['lu_qty'] = $brow->session_2_qty;
				$inputs_array['lu_price'] = $brow->session_2_price;


				$inputs_array['sn_qty'] = $brow->session_3_qty;
				$inputs_array['sn_price'] = $brow->session_3_price;

				$inputs_array['di_qty'] = $brow->session_4_qty;
				$inputs_array['di_price'] = $brow->session_4_price;
		
		
			 
		  $updatable_entries= $this->common_model->get_updatable_entries($inputs_array);
		  
		  if($updatable_entries['negative_reached'] == true)
		  {
				send_json_result([
                'success' =>  FALSE ,
                'message' => '<div class="alert alert-danger">Updation failed as closing balance reaching negative value on '.$updatable_entries['negative_date'].". please check the below transactions table</div>",
				'html_table'=>$this->generate_html_table($updatable_entries['entries_list'])
            ] );  
		  }
			 
			 
			 
 
			$qty 		= 	floatval($this->input->post('quantity') );
			$school_price	=	floatval($this->input->post('price'));
			
			 
			 
			
			
			$date			=	date('Y-m-d');				 
			$purchase_biil_no = $this->input->post('billno');
			$entry_id = $purchased_item_row->entry_id;		
			$stock_entry_table = $this->common_model->get_stock_entry_table($date);
			$update_data = array('purchase_quantity'=> $qty,'purchase_price'=>$school_price,'purchase_biil_no'=>$purchase_biil_no,'vendor_annapurna_id'=>$vendor_id);
			$this->db->where('entry_id', $entry_id);
			$this->db->update($stock_entry_table, $update_data);  
			 
			//update closing Balance	
			$qry = "update $stock_entry_table set closing_quantity=(opening_quantity+purchase_quantity) - (session_1_qty+session_2_qty+session_3_qty+session_4_qty) where entry_id=?";
			$rs = $this->db->query($qry,array($entry_id)); 
			
			$pur_rs = $this->db->query("select * from purchases where school_id=? and item_id=? and purchase_date=?",array($school_id,$item_id,$entry_date));
			if($pur_rs->num_rows()==0)
			{
					$this->db->query("insert into purchases set school_id=? , item_id=? , purchase_date=?,quantity=?,purchase_price=? , vendor_annapurna_id=?",array($school_id,$item_id,$entry_date, $qty,$school_price,$vendor_id));
			}
			else{
					$prow_id = $pur_rs->row()->pid;
					$this->db->query("update purchases set quantity=?,purchase_price=?,vendor_annapurna_id=? where pid=? ",array(  $qty,$school_price,$vendor_id,$prow_id));
					
			}
			
			
			
			$this->session->set_flashdata('message', '<div class="alert alert-success">Updated Successfully.</div>'); 
				send_json_result([
                'success' =>  TRUE ,
                'message' => '<div class="alert alert-success">Updated Successfully</div>',
				'html_table'=>$this->generate_html_table($updatable_entries['entries_list'])
            ] );  
			//redirect('purchase_entry'); 
		 }
		 
		
		
		 
        $data["allow_to_modify"] = $allow_to_modify; 
        $data["item_id"] = $item_id;
		 $data["vendor_details"] = $rs = $this->db->query("select * from tw_vendors where school_id=?",array($school_id));
        $data["item_details"] = $rs = $this->db->query("select * from items where item_id=?",array($item_id))->row();
        $data["module"] = "purchase_entry"; 
		
		
       
		if($this->config->item("site_name")=="apsocial")
		 {
			 $data["view_file"] = "apsocal_purchase_form";
		 }else{
		
				 $data["view_file"] = "purchase_form";
		 }
		 $data["view_file"] = "purchase_form";
        echo Modules::run("template/admin", $data);
         
	}
	 
	private function today_date()
	{
	
		switch($this->config->item("site_name"))
		{
			case 'apreis':
							//echo $this->config->item("day_start_time");die;
							$is_yester_day = $this->db->query("select CURRENT_TIME < ? as is_yester_day",$this->config->item("day_start_time"))->row()->is_yester_day;
							if($is_yester_day ==1)
									$date = date("Y-m-d",strtotime("yesterday"));
							else 
									$date = date('Y-m-d');
							break;
			default :
							$date = date('Y-m-d');
							break;
		}
		//echo $date;die;
		return $date;
	
	}
	
	private function generate_html_table($dataarray=array())
	 {
		$table_html = "<table class='table'><thead><tr><th>Date</th><th>Opening Quantity</th><th>Purchase Quantity</th><th>Total</th><th>Consumed Quantity</th><th>Closing Quantity </th></tr></thead><tbody>";
		foreach($dataarray as $data)
		{
			$total_used = $data['session_1_qty'] + $data['session_2_qty'] + $data['session_3_qty'] + $data['session_4_qty'] ;
			$avilable_qty = $data['opening_quantity'] + $data['purchase_quantity'];
			
			$table_html  .= "<tr><td>".$data['entry_date_dp']."</td><td>".$data['opening_quantity']."</td><td>".$data['purchase_quantity']."</td><td>".$avilable_qty."</td><td>".$total_used."</td><td>".number_format($data['closing_quantity'],3)."</td></tr>";
		
		}
		$table_html .= "</tbody></table>";
		return $table_html;
	 }
	 function createNonExistRecord($school_id,$item_id, $todate)
	 {
		   $future_date = $this->db->query("SELECT ? > CURRENT_DATE as future_date ",array($todate))->row()->future_date;
		 if($future_date==1)
		 {
			 return true;
		 }
		 
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
			$check_rs = $this->db->query("select * from balance_sheet where school_id=? and item_id=? and entry_date=?",array($school_id,$item_id,$todate));
			if($check_rs->num_rows()==0){
					$ins_data = array('school_id'=>$school_id,'item_id'=>$item_id,'entry_date'=>$todate,'opening_quantity'=>$closing_quantity ,'closing_quantity'=>$closing_quantity );
					$this->db->insert($stock_entry_table ,$ins_data); 
					//echo $this->db->last_query();die;
			}
	 } 
	 
}
