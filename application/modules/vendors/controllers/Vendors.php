<?php 
 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
set_time_limit(0);
 date_default_timezone_set('Asia/Kolkata');
class Vendors extends MX_Controller {

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
		$this->load->model('consumption_model'); 
		$this->load->model('common/common_model'); 
		$this->load->config('config'); 
		 
	}

    function index() {
   
		$school_id	=	intval($this->session->userdata("school_id"));  
        $data['vendors'] = $this->db->query("select * from tw_vendors where school_id=?",array($school_id));
		 
        $data["module"] = "vendors"; 
        $data["view_file"] = "vendor_list";
        echo Modules::run("template/admin", $data);
	}
	
		/******************************************************
		
		
		
		/******************************************************/
		
	 function entryform()
	{
		$data["module"] = "vendors"; 
        $data["view_file"] = "vendor_form";
        echo Modules::run("template/admin", $data);
         
	}
	public function ajax_add_vendor()
	{
	$school_id	=	intval($this->session->userdata("school_id"));  
		 $required_fields = array('vendor_type','vendor_name','business_nature',   'vendor_bank','vendor_bank_branch','vendor_bank_ifsc','vendor_account_number');
		 $errors = array();
		 foreach( $required_fields as $field_name){
			if(trim($this->input->post($field_name))==""){
				$field_text = ucfirst(str_replace("_"," ",$field_name));
				$errors[] = $field_text . " Field is required ";
			}
		 }
		 
		 
		 $vendor_account_number =  trim($this->input->post('vendor_account_number'));
		 if(!is_numeric($vendor_account_number)){
			 $errors[] =  " Invalid account number ";
			 
		 }
		 
		 if( strlen($vendor_account_number)<10){
			 $errors[] =  " Account number Should have minimum 10 numbers"; 
		 }
		 if( intval($vendor_account_number)==0){
			  $errors[] =  " Invalid account number ";
		 }
		 
		 $result_flags = array('success'=>false,'msg'=>'');
		 
		 header("Content-Type: application/json;charset=utf-8");
		 
		 if(count($errors)>0)
		 {
			$errror_text = '<div class="alert alert-danger">'.implode("<br>",$errors).'  </div>';
			$result_flags['success'] = false;
			$result_flags['msg'] = $errror_text;
			echo json_encode($result_flags);die;
		 }else
		 {
				//check same ifsc and account number already exists
				$vendor_bank_ifsc =  trim($this->input->post('vendor_bank_ifsc'));
				$vendor_account_number =  trim($this->input->post('vendor_account_number'));
				$check_already_exists_rs = $this->db->query("select * from tw_vendors where school_id=? and vendor_bank_ifsc=? and vendor_account_number=?",array($school_id,$vendor_bank_ifsc,$vendor_account_number));
				if($check_already_exists_rs->num_rows()>0)
				{
					$result_flags['success'] = false;
					$result_flags['msg'] = '<div class="alert alert-danger">IFSC CODE And Account Number already exists </div>'; ;
					echo json_encode($result_flags);die;
				}
			 
					$ehostel_vendor_id = $this->db->query("select max(ehostel_vendor_id) as ehostel_vendor_id from tw_vendors")->row()->ehostel_vendor_id; 
					$ehostel_vendor_id = $ehostel_vendor_id + 1;
					
					
					$ehostel_id = 0;
					$sc_rs = $this->db->query("select * from schools where school_id=?",array($school_id));
					$sc_info = $sc_rs->row();
					$ehostel_id = intval($sc_info->ehostel_id);
					if($ehostel_id==0 && $this->session->userdata("school_code")!='85000')
					{
						$result_flags['success'] = false;
						$result_flags['msg'] = '<div class="alert alert-danger">Ehostel id not mapped .please contact administrator.</div>'; ;
						echo json_encode($result_flags);
						die;
					}
					
			 
					$ins_data['school_id'] = $school_id;
					$ins_data['vendor_type'] = trim($this->input->post('vendor_type'));
					$ins_data['vendor_name'] = trim($this->input->post('vendor_name'));
					$ins_data['ehostel_id'] = $ehostel_id;
					$ins_data['ehostel_vendor_id'] = $ehostel_vendor_id;
					$ins_data['business_nature'] = trim($this->input->post('business_nature'));
					$ins_data['vendor_address'] = trim($this->input->post('vendor_address'));
					$ins_data['vendor_contact_number'] = trim($this->input->post('vendor_contact_number'));
					$ins_data['supplier_name'] = trim($this->input->post('supplier_name'));
					$ins_data['supplier_contact_number'] = trim($this->input->post('supplier_contact_number'));
					$ins_data['vendor_bank'] = trim($this->input->post('vendor_bank'));
					$ins_data['vendor_bank_branch'] = trim($this->input->post('vendor_bank_branch'));
					$ins_data['vendor_bank_ifsc'] = trim($this->input->post('vendor_bank_ifsc'));
					$ins_data['vendor_account_number'] = trim($this->input->post('vendor_account_number'));
					$ins_data['tin_number'] = trim($this->input->post('tin_number'));
					$ins_data['supplier_aadhar_number'] = trim($this->input->post('supplier_aadhar_number'));
					$this->db->insert("tw_vendors",$ins_data);
					
					$result_flags['success'] = true;
					$result_flags['msg'] = '<div class="alert alert-success">Vendor added Successfully </div>'; ;
					echo json_encode($result_flags);die;
				 
		}
	
	}
	  
 
	 
	
}
