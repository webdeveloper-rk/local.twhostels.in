<?php 
 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
set_time_limit(0);
 date_default_timezone_set('Asia/Kolkata');
class Admin extends MX_Controller {

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
		 
		 
	}

    function index() {
   
		
		$this->form_validation->set_rules('school_id', 'School name', 'numeric|greater_than[0]');
		if($this->form_validation->run() == true)
		 {
			 $school_id	=	intval($this->input->post('school_id'));
			 redirect("manage/vendors/".$school_id);
		 }			 
		 
        $data['schools_rs'] = $this->db->query("select * from schools where is_school='1' and ehostel_id!=0");
		 
        $data["module"] = "vendors"; 
        $data["view_file"] = "schools_form";
        echo Modules::run("template/admin", $data);
	}
	
		 
 
	 
	
}
