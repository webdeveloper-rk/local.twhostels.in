<?php 
 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
set_time_limit(0);
 date_default_timezone_set('Asia/Kolkata');
class Employees  extends MX_Controller {

    function __construct() {
        parent::__construct();
        
		if($this->uri->segment(2) !="login") { 
					 Modules::run("security/is_admin");		 
					if ($this->session->userdata("is_loggedin") != TRUE || $this->session->userdata("user_id") == "" ) {
							redirect("admin/login");
							die;
					}
					 			
					 if(!in_array($this->session->userdata("user_role") ,array("dtdo","subadmin")))
					{
						redirect("admin/login");
							die;
					} 
		}
		$this->load->helper('url'); 
		$this->load->library('ci_jwt');  
		$this->load->config('config'); 
		 
	}

    function index($school_type='gps') { 
	
		//print_a($this->session->all_userdata());
		$district_condition = "";
		$district_id   = intval($this->session->userdata("district_id"));
		if($this->session->userdata("user_role")=="dtdo")
		{
			$district_condition = " and sc.district_id='".$district_id."'";
		}
		$rset = $this->db->query("select sc.*,d.name as dname from schools sc left join districts d on d.district_id = sc.district_id where 1 $district_condition and school_type=?",array($school_type));
	
        $data["school_type"] =  $school_type; 
        $data["rset"] = $rset; 
        $data["module"] = "employees"; 
        $data["view_file"] = "schools_list";
        echo Modules::run("template/admin", $data);
	}
	
		 
	  function viewlist($school_id=0) { 
	
		 
		$district_condition = "";
		$district_id   = intval($this->session->userdata("district_id"));
		
		$school_district_id = $this->db->query("select district_id from schools where school_id=?",array($school_id))->row()->district_id;
		 
		$district_id   = intval($this->session->userdata("district_id"));
		 if($this->session->userdata("user_role")=="dtdo" && $school_district_id != $district_id)
		{
			die("<h1>Invalid Access</h1>");
		} 
		
		
		
		$school_rset = $this->db->query("select sc.*,d.name as dname from schools sc left join districts d on d.district_id = sc.district_id where  school_id=?",array($school_id));
		$school_name= $school_rset->row()->name." - ".$school_rset->row()->dname . " ( ". strtoupper($school_rset->row()->school_type)." )";
		
		
		$rset = $this->db->query("select emp.*,p.post_title from employee_info emp left join posts p on p.post_id = emp.post_id where  school_id=?",array($school_id));
        $data["school_name"] = $school_name; 
        $data["school_id"] = $school_id; 
        $data["rset"] = $rset; 
        $data["module"] = "employees"; 
        $data["view_file"] = "employee_list";
        echo Modules::run("template/admin", $data);
	}
	 
	function processform($process_type,$school_id=0,$emp_id=0,$emp_type='') { 
	
		 $school_district_id = $this->db->query("select district_id from schools where school_id=?",array($school_id))->row()->district_id;
		$district_condition = "";
		$district_id   = intval($this->session->userdata("district_id"));
		 if($this->session->userdata("user_role")=="dtdo" && $school_district_id != $district_id)
		{
			die("<h1>Invalid Access</h1>");
		} 
		
 
		 if($this->session->userdata("user_role")=="dtdo" && $process_type=="edit"  )
		{
			$employee_school_id = $this->db->query("select school_id from employee_info where emp_id=?",array($emp_id))->row()->school_id;
			
			if($school_id != $employee_school_id){
					die("<h1>Invalid Access</h1>");
			}
		} 
		
			$school_rset = $this->db->query("select sc.*,d.name as dname from schools sc left join districts d on d.district_id = sc.district_id where  school_id=?",array($school_id));
		$school_name= $school_rset->row()->name." - ".$school_rset->row()->dname . " ( ". strtoupper($school_rset->row()->school_type)." )";
		
		
	 
		
		
		$rset = $this->db->query("select * ,
								date_format(dob,'%m/%d/%Y') as dob,
								date_format(p_schooljoining_date,'%m/%d/%Y') as p_schooljoining_date,
								date_format(first_app_date,'%m/%d/%Y') as first_app_date,
								date_format(joining_date,'%m/%d/%Y') as joining_date  
							from employee_info emp  where  emp_id=?",array($emp_id));
							
							
							
        $data["emp_type"] = $emp_type; 
        $data["school_name"] = $school_name; 
        $data["school_id"] = $school_id; 
        $data["process_type"] = $process_type; 
        $data["emp_id"] = $emp_id; 
        $data["emp_info"] = @$rset->row(); 
        $data["module"] = "employees"; 
		if($emp_type=="regular"){
			$data["view_file"] = "employee_form";
		}else if($emp_type=="crt"){
			$data["view_file"] = "crt_employee_form";
		}
        echo Modules::run("template/admin", $data);
	}
	
	function updateemployee($process_type,$school_id=0,$emp_id=0) { 
	
		 $school_district_id = $this->db->query("select district_id from schools where school_id=?",array($school_id))->row()->district_id;
		$district_condition = "";
		$district_id   = intval($this->session->userdata("district_id"));
		 if($this->session->userdata("user_role")=="dtdo" && $school_district_id != $district_id)
		{
			send_json_result([
                'success' =>  TRUE ,
                'message' => '<div class="alert alert-danger">  Invalid School id</div>',
				'html_table'=>''
            ] );  
		} 
		
 
		 if($this->session->userdata("user_role")=="dtdo" && $process_type=="edit"  )
		{
			$employee_school_id = $this->db->query("select school_id from employee_info where emp_id=?",array($emp_id))->row()->school_id;
			
			if($school_id != $employee_school_id){
					send_json_result([
                'success' =>  TRUE ,
                'message' => '<div class="alert alert-danger">  Invalid Employee id</div>',
				'html_table'=>''
            ] );  
		
			}
		} 
		if($process_type=="add")
		{
			$params = $this->input->post("param");
			$params['school_id'] = $this->input->post("school_id"); //overwrite from adding form   
			$params['dob'] =   date('Y-m-d',strtotime($params['dob']));
			$params['p_schooljoining_date'] =   date('Y-m-d',strtotime($params['p_schooljoining_date']));
			$params['first_app_date'] =   date('Y-m-d',strtotime($params['first_app_date']));
			$params['joining_date'] =   date('Y-m-d',strtotime($params['joining_date']));
			//$params['degree_month_year'] =   date('Y-m-d',strtotime($params['degree_month_year'])); 
			//print_a($params,1);

			
			$this->db->insert("employee_info",$params);
			
			send_json_result([
                'success' =>  TRUE ,
                'process_type' =>  'add' ,
				'school_id' =>  $this->input->post("school_id") ,
                'message' => '<div class="alert alert-success">  Registered Successfully</div>',
				'html_table'=>''
            ] );  
			
		}
		if($process_type=="edit")
		{
			$params = $this->input->post("param");
			
			$params['dob'] =   date('Y-m-d',strtotime($params['dob']));
			$params['p_schooljoining_date'] =   date('Y-m-d',strtotime($params['p_schooljoining_date']));
			$params['first_app_date'] =   date('Y-m-d',strtotime($params['first_app_date']));
			$params['joining_date'] =   date('Y-m-d',strtotime($params['joining_date']));
			//$params['degree_month_year'] =   date('Y-m-d',strtotime($params['degree_month_year'])); 
			
			
			$this->db->where("emp_id",$emp_id);
			$this->db->update("employee_info",$params);
			
			send_json_result([
                'success' =>  TRUE ,
				'process_type' =>  'edit' ,
				'school_id' =>  $this->input->post("school_id") ,
                'message' => '<div class="alert alert-success">  Updated Successfully</div>',
				'html_table'=>''
            ] );  
			
		}
		
		
		
		
		
		
		
		
	}
	
	function get_designation_list($school_id='')
	{
			 $schools_list = $this->db->query("select * from  schools where school_id=?    order by name asc ",array($school_id));
			 $school_type = $schools_list->row()->school_type;
			 $condition = "";
			 if($school_type=="gps")
			 {
				$condition = " and gps_applicable='1' ";
			 }else if($school_type=="ashram")
			 {
				$condition = " and ashram_applicable='1' ";
			 }
	
			$posts_list = $this->db->query("select * from  posts where 1 $condition ",array($school_id));
			$list = array();
			foreach($posts_list->result() as $row)
			{
				$list[] = array("id"=>$row->post_id,"name"=>$row->post_title );
			}
			// echo $this->db->last_query();
			 //print_a($list);
			send_json_result($list );  
	}
}
