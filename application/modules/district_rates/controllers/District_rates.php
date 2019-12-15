<?php 
 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
set_time_limit(0);
 date_default_timezone_set('Asia/Kolkata');
class District_rates extends MX_Controller {

    function __construct() {
        parent::__construct();
		$this->load->library('ci_jwt'); 
					if($this->uri->segment(2) !="login") { 
								 Modules::run("security/is_admin");		 
								if ($this->session->userdata("is_loggedin") != TRUE || $this->session->userdata("user_id") == "" ) {
										redirect("admin/login");
										die;
								}
											
								if($this->session->userdata("is_dco") !=1)
								{
									redirect("admin/login");
										die;
								}
					}
		$this->load->helper('url');
		$this->load->library('grocery_CRUD'); 
		
		$this->load->model('district_model'); 
		$this->load->model('common/common_model');  
		 
		 
	}
	function index()
	{
		 $default_sql = "insert into district_item_prices(district_id,item_id,status,start_date,end_date)
SELECT district_id,item_id,1 as status,'2018-12-01' as start_date,'2020-12-01' as end_date FROM items, districts where concat(district_id,item_id) not in (select concat(district_id,item_id) from district_item_prices)";
		$this->db->query( $default_sql);
		
		redirect("district_rates/list_items");
		return '';
		 
		 $rset = $this->db->query("select * from classes "); 
		
		
		$data["district_id"] =  $this->session->userdata("district_id");
		$data["rset"] =  $rset;
		$data["module"] = "district_rates";
        $data["view_file"] = "district_rates_entry";
        echo Modules::run("template/admin", $data);
         
	}
	/*
	
	*/
	function item_entry_form($encoded_code=null)
	{
		$data["encoded_code"] = $encoded_code;
		
		 
		$params = $this->ci_jwt->jwt_web_decode($encoded_code);	
		$data["params"] = $params;	
 	
		
		//$this->form_validation->set_rules('quantity', 'Quantity', 'required|numeric|greater_than[0]');              
		$this->form_validation->set_rules('price', 'Price', 'required|numeric|greater_than[0]'); 
		 
		
		 
		
		if($this->form_validation->run() == true   )
		 {
			 
 
			$qty 		= 	floatval($this->input->post('quantity') );
			$school_price	=	floatval($this->input->post('price'));
			
			$start_date = date('Y-m-d');
			$end_date = date('2029-12-01'); 
			
			$ended_date = $this->db->query("select subdate(current_date, 1) as yesterday ")->row()->yesterday;
			
			$this->db->query("update  district_item_prices set status=0,end_date=? where  district_id=?   and item_id=? and status=1 ",array($ended_date,$params->district_id, $params->item_id));
			
			 // echo $this->db->last_query();
			
			$this->db->query("insert into  district_item_prices set status=1,district_id=?, item_id=?, price=?,start_date=?,end_date=? ",array($params->district_id, $params->item_id,$school_price,$start_date,$end_date));
			 
			 
			 // echo $this->db->last_query(); 			 die;
			$this->session->set_flashdata('message', '<div class="alert alert-success">Updated Successfully.</div>'); 
			redirect('district_rates/list_items/'.$encoded_code); 
		 }
		 
		
		
		 
        $data["dataset"] =$this->db->query("select * from  district_item_prices where  district_id=?  and item_id=? and status=1  ",array($params->district_id, $params->item_id))->row(); 
		 
        $data["allow_to_modify"] = true; 
         
        $data["module"] = "district_rates"; 
        $data["view_file"] = "district_rates_form";
        echo Modules::run("template/admin", $data);
         
	}
	 function list_items()
	 {
		 
		$district_id = $this->session->userdata("district_id"); 
		$data['district_id'] = $district_id;
		
		 $rset = $this->db->query("select it.item_name,dp.* from  district_item_prices dp inner join items it on it.item_id = dp.item_id where district_id=? and     dp.status=1 ",array($district_id ));  
		  
		$data["rset"] =  $rset;
		
		
        	$data["module"] = "district_rates";
        $data["view_file"] = "district_rates_items_list";
        echo Modules::run("template/admin", $data);
	 }
	
}
