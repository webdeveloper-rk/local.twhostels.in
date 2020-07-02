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
						redirect(site_url());
							die;
					}
		}
		$this->load->helper('url');
		$this->load->library('grocery_CRUD');
		$this->load->library('ci_jwt');
		$this->load->model('locks/locks_model');
	 
	}

    function index() {
            
		  
		$this->form_validation->set_rules('school_id', 'School Name', 'required|numeric');     
		 
		if($this->form_validation->run() == true )
		{
				 
			 $school_id = intval($this->input->post('school_id'));	
			 $month = intval($this->input->post('month'));	
			 $year = intval($this->input->post('year'));	
			 
			 //check logged user can access this school id 
			 $encoded_data = $this->ci_jwt->jwt_web_encode(array('school_id'=>$school_id,'month'=>$month,'year'=>$year));
			 redirect('attendence/admin/schoolview/'.$encoded_data );
		 }
		 
		 $data["module"] = "attendence";
        $data["view_file"] = "school_attendence";
        echo Modules::run("template/admin", $data);
					 
	
    }
  
  
  
  	function schoolview($encodeed )
	{
		  
		    $decoded_data = $this->ci_jwt->jwt_web_decode($encodeed);
			//print_a(  $decoded_data ,1);
			$school_id = $decoded_data->school_id;
			$month = $decoded_data->month;
			$year = $decoded_data->year;
			
			$months = array("01"=>"January","02"=>"February","03"=>"March","04"=>"April","05"=>"May",
									"06"=>"June","07"=>"July","08"=>"August","09"=>"September","10"=>"October","11"=>"November","12"=>"December");
				
			$month = intval($month);
			$year = intval($year);
			if($month<10)
					$month = "0".$month;
				
				$first_date = $year."-".$month."-01";
				$second_date = date("Y-m-t", strtotime($first_date));
				$month_name = $months[$month];	
				
			
			$dco_district_id = $this->session->userdata("district_id");
			$school_district_id  = $this->db->query("select * from schools where school_id=?",array($school_id))->row()->district_id;
			if($this->session->userdata("is_dco") ==1 && $dco_district_id != $school_district_id)
			{
				die("<h1>Access Denied. you dont have access to update </h1>");
			}
			
		
		try{
			$crud = new grocery_CRUD($this);
			
			$state = $crud->getState();
			$state_info = $crud->getStateInfo();
			if($state=="update_validation")
			{
				 $primary_key = $state_info->primary_key ;
				 $att_date = $this->db->query("select * from school_attendence where attendence_id=?",array($primary_key))->row()->entry_date;
				  
				  $locked_status = $this->locks_model->is_locked("attendance", $att_date);
				   
				 if($locked_status==1)
				 {
					  $locked_date = $this->locks_model->locked_date("attendance");
						 send_json_result([
											'success' =>  FALSE ,
											'error_message' => ' Attendance entries Locked to '.$locked_date.'.', 
											'error_fields' => '' 
										]);  
				 }
				  
			}

			 
			$crud->set_theme('flexigrid'); 
			$crud->set_table('school_attendence');
			$crud->where('school_id',$school_id );
			
			$crud->where('entry_date >=', $first_date);
			$crud->where('entry_date <=', $second_date);
						
			$crud->order_by('entry_date','desc');
			$crud->set_subject('Attendance');
			
			$crud->callback_column('entry_date',array($this,'date_formatdisplay'));
			$crud->callback_edit_field('entry_date',array($this,'date_formatdisplay'));
			 
			$school_code = $this->db->query("select * from schools where school_id=?",array($school_id))->row()->school_code;
			$cat3_school_codes = array('85000');
			
			if(  in_array($school_code,$cat3_school_codes))
			{
				$crud->columns(array('entry_date','present_count','cat1_attendence','cat1_guest_attendence','cat2_attendence','cat2_guest_attendence','cat3_attendence'));
				$crud->edit_fields('entry_date','cat1_attendence','cat1_guest_attendence','cat2_attendence','cat2_guest_attendence','cat3_attendence');
			}
			else{
				 
				$crud->columns(array('entry_date','present_count','cat1_attendence','cat1_guest_attendence','cat2_attendence','cat2_guest_attendence' ));
				 $crud->edit_fields('entry_date','cat1_attendence','cat1_guest_attendence','cat2_attendence','cat2_guest_attendence' );
			}
			$crud->required_fields('cat1_attendence','cat1_guest_attendence','cat2_attendence','cat2_guest_attendence');
			
			 $edit_check = $this->db->query("select * from attendance_months_locks where month=? and year=? and admin_locked_status='locked'",array(intval($month),$year));
						if($edit_check->num_rows()>0)
						{
							//$crud->unset_edit(); 
						} 
						//before october 1st also remove edit option 
						$low_date = $this->db->query("select  ? < '2019-10-01' as low_date ",array($first_date))->row()->low_date;
						if($low_date==1)
						{
							//$crud->unset_edit(); 
						} 
						
			$crud->unset_add(); 
            $crud->unset_delete();
			 
			
			
			
			
			$crud->display_as('cat1_guest_attendence','Category 1 Guest Attendance')
				->display_as('cat2_guest_attendence','Category 2 Guest Attendance')
				->display_as('cat3_guest_attendence','Category 3 Guest Attendance');
			$crud->display_as('cat1_attendence','Category 1 Attendance')
				->display_as('cat2_attendence','Category 2  Attendance')
				->display_as('cat3_attendence','Category 3 Attendance');
			$crud->display_as('present_count','Total');
			
		//	$crud->edit_fields(array('entry_date','cat1_attendence','cat2_attendence','cat3_attendence', 'cat1_guest_attendence', 'cat2_guest_attendence','cat3_guest_attendence'));
			$crud->callback_after_update(array($this, 'update_attendence_total_count'));
			 
			 $crud->field_type('entry_date', 'readonly');
			 
			 
			$school_info = $this->db->query("select * from schools where school_id=?",array($school_id))->row();
			$output = $crud->render(); 
			$data["module"] = "cms";
			$data["extra_content"] = "<p>Category 1 : up to 7th class<br>Category 2 : 8,9,10th classes <br>Category 3 : Intermediate and above</p><br><a href='".site_url('attendence/admin')."' class='btn btn-primary'>Go Back</a>";
						$data["view_file"] = "cms";
			$output->title = "Attendance entries of " .$school_info->name." - ".$month_name ." - ".$year;;
			$data["crud"] = $output;
			echo Modules::run("template/admin", $data);
			

		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}
	
	public function  update_attendence_total_count($post_array,$primary_key)
	{
			 
			 $update_attendence_sql= "update  school_attendence set 
								present_count=(cat1_attendence+cat2_attendence+cat3_attendence+cat1_guest_attendence	+cat2_guest_attendence	+cat3_guest_attendence	)
						where attendence_id=?";
			 $this->db->query( $update_attendence_sql,array($primary_key));
			 return true;
	}
	
	
	
	public function date_formatdisplay($value, $row)
	{
		 return date('d-M-Y',strtotime($value));
	}
}
