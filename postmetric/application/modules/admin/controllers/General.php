<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class General extends MX_Controller {

    function __construct() {
        parent::__construct();
		
		if($this->uri->segment(2) !="login") { 
					 Modules::run("security/is_admin");		 
					if ($this->session->userdata("is_loggedin") != TRUE || $this->session->userdata("user_id") == "" ) {
							redirect("admin/login");
							die;
					}
					 
		}
					
          $this->load->model('admin_model');
         $this->admin_model->set_table("users");
		 $this->load->library("ci_jwt");
    }

    function index() {
        redirect("admin/dashboard");
    }
	function logout() {
        $variables = array("user_id"=>"","user_name"=>"","user_email"=>"","user_role"=>"blahahhahahahha","is_loggedin"=>false);
        $this->session->set_userdata($variables);
        $this->session->unset_userdata($variables);
		$this->session->sess_destroy();
        $msg = $this->userlib->gen_msg_output(TRUE,"You are successfully logged out");
        $this->session->set_flashdata('notice', $msg);
        redirect(site_url());
    }
     function changepassword() {
       //    Modules::run("security/is_admin");
         $this->load->model('admin_model');
         if ($this->input->post("action")=="updatepassword") {
        $data= $this->admin_model->change_password();
        }
        $data["module"] = "admin";
        $data["view_file"] = "changepassword_view";
        echo  Modules::run("template/admin", $data);
    }
	
	function resetpassword() {
       //    Modules::run("security/is_admin");
         $this->load->model('admin_model');
         if ($this->input->post("action")=="updatepassword") {
			 
			 if($this->session->userdata("is_dco")==1)
				{
					$school_code = $this->input->post("school_code");
					$district_id = $this->session->userdata("district_id");
					$school_check_rs = $this->db->query("select school_id from schools where school_code=? and district_id =? ",array($school_code,$district_id));
					if($school_check_rs->num_rows()==0)
					{
						$this->userlib->show_ajax_output("", 'Error Occured While Updating Password');
						die;
					}
				}
			 
			 
				$data= $this->admin_model->reset_password();
        }
        $data["module"] = "admin";
        $data["view_file"] = "reset_password";
        echo  Modules::run("template/admin", $data);
    }
	public 	function diet_resetpassword() {
       //    Modules::run("security/is_admin");
         $this->load->model('admin_model');
         if ($this->input->post("action")=="updatepassword") {
				$data= $this->admin_model->diet_reset_password();
        }
        $data["module"] = "admin";
        $data["view_file"] = "diet_reset_password";
        echo  Modules::run("template/admin", $data);
    }

}
