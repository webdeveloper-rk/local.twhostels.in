<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
set_time_limit(0); 
 date_default_timezone_set('Asia/Kolkata');
class Locks extends MX_Controller {

    function __construct() {
        parent::__construct();
		if($this->uri->segment(2) !="login") { 
					 Modules::run("security/is_admin");		 
					
		}
			$this->load->helper('url');  
			$this->load->config("config.php");
			$this->load->library("ci_jwt");
			$this->load->model("common/common_model");
			 if(!in_array($this->session->userdata("user_role"),array( "subadmin")) )
					{
						redirect("admin/login");
							die;
					}
		 if( $this->session->userdata("school_code")!="10100")
					{
						redirect("admin/login");
							die;
					}
		$this->load->model("locks_model");			
}

   	public function index($lock_type='attendance'){
		$allowed_locks = array("attendance","balance_sheet");
		if(!in_array($lock_type,$allowed_locks))
		{
			die("Invalid Lock type");
		}
		
		 $drs = $this->db->query("SELECT  users.name,locks.*,date_format(locked_date,'%d-%M-%Y') as entry_date_dp,date_format(creation_time,'%d-%M-%Y %H:%i:%s') as posted_time  
			   from  locks  left join users on users.uid = locks.user_id  where lock_type=? and locks.status='1' order by locked_date desc",array($lock_type) );         
        $data["locks_list"] = $drs; 
		 
        $data["lock_type"] = $lock_type;        
        $data["module"] = "locks";        
        $data["view_file"] = "locks_list";
        echo Modules::run("template/admin", $data);
	 }

	function add($lock_type='attendance')
	{
		$allowed_locks = array("attendance","balance_sheet");
		if(!in_array($lock_type,$allowed_locks))
		{
			die("Invalid Lock type");
		}
		 
		 $this->form_validation->set_rules('entry_date', 'Lock Date', 'required');              
		  
		$this->form_validation->set_rules('comment', ' Explanation ', 'required');   
		 
		if($this->form_validation->run() == true )
		{
				//invalidate previous locks
				$this->db->query("update locks set status='0' where lock_type=? and status='1' ",array($lock_type));
				
				$entry_date = date('Y-m-d',strtotime($this->input->post('entry_date'))); 
				
				$this->db->set('creation_time', 'NOW()', FALSE);  
				$inputs_array['locked_date'] = $entry_date;

				$inputs_array['lock_type'] = $lock_type;
				$inputs_array['comment'] = $this->input->post("comment");
				$inputs_array['ip_address'] = $this->input->ip_address();

				$inputs_array['user_id'] = $this->session->userdata('user_id');
				 
			 
				$this->db->insert('locks', $inputs_array);   
					send_json_result([
						'success' =>  TRUE ,
						'message' => '<div class="alert alert-success">Updated Successfully</div>'  
					] );  
			
		}else{
				if($this->input->post('entry_date')!=""){
					
										send_json_result([
									'success' =>  false ,
									'message' => '<div class="alert alert-danger">'.validation_errors().'</div>'  
													] );  
				}
		}
		
		 
		 
        $data["module"] = "locks";        
        $data["locked_date"] = $this->db->query("select date_format(locked_date,'%d-%M-%Y') as entry_date_dp from locks where lock_type=? and status='1' order by locked_date desc limit 0,1",array($lock_type))->row()->entry_date_dp;        
        $data["lock_type"] = $lock_type;        
        $data["view_file"] = "add_form";
        echo Modules::run("template/admin", $data);
	}
	 function lock_test()
	 {
		echo "<br> 2019-12-01 : ",$this->locks_model->is_locked("attendance",'2019-12-01');
		echo "<br> 2020-03-01 : ",$this->locks_model->is_locked("attendance",'2020-03-01');
		echo "<br> 2020-01-04 : ",$this->locks_model->is_locked("attendance",'2020-01-04');
		echo "<br> 2020-01-05 : ",$this->locks_model->is_locked("attendance",'2020-01-05');
	 }
	  
	 
	
}
