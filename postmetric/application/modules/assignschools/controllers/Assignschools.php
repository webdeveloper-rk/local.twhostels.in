<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Assignschools extends MX_Controller {

    function __construct() {
        parent::__construct(); 
         $this->load->config("config.php");
         $this->load->model("admin/school_model");
		 if($this->session->userdata("user_role") != "subadmin")
		{
				redirect("general/logout"); 
		}
		$this->load->model("common/common_model");  
		$this->load->library('ci_jwt'); 
		$this->load->library('assigned_schools'); 
    }

   

    function index() {

		//print_a($this->assigned_schools->get_list(437));
		 
	}

	
    function update($uid,$type='') {

		$school_ids = $this->input->post('school_ids');
		$action = $this->input->post('action');
		 
		 if($action == "assignschools" )
		{
			 $this->db->query("delete from assigned_schools where user_id=?",array($uid));
			 foreach($school_ids as $sc_id)
			 {
				$ins_data = array("user_id"=>$uid,"school_id"=>$sc_id);
				$this->db->insert("assigned_schools",$ins_data);
			 }
			 $this->session->set_flashdata('message', '<div class="alert alert-success">Updated Successfully.</div>'); 
			  redirect("manage/atdos/");
		}
		$sql = " select * from  users  where uid=?  "; 
		$user_row = $this->db->query($sql,array($uid))->row(); 
		$data['user_row'] = $user_row;
	 
		$sql = " select  sc.*,d.name as district_name from schools sc left join districts d on d.district_id = sc.district_id   where   sc.district_id=? and is_school=1 and school_code !='85000' "; 
		$rs = $this->db->query($sql,array($user_row->district_id)); 
		 
		$data['rset'] = $rs;
		$checked_list = array();
		$data_selected_set  =  $this->db->query("select *  from assigned_schools where user_id=?",array($uid));
		foreach($data_selected_set->result() as $asrow)
		{
			$checked_list[] = $asrow->school_id;
		}
 
		
		$data["checked_list"] = $checked_list; 
		$data["module"] = "assignschools"; 
		$data["view_file"] = "assign_schools_form"; 
		echo Modules::run("template/admin", $data);
	}

    
}

