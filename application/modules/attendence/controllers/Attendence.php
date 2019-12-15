<?php 
 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
set_time_limit(0);
 date_default_timezone_set('Asia/Kolkata');
class Attendence extends MX_Controller {

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
	 
	}

    function index() {
		  $cyear = date('Y');
		$this->form_validation->set_rules('month', 'Month ', 'required|greater_than[0]|less_than_equal_to[12]');              
		$this->form_validation->set_rules('year', 'Year ', 'required|greater_than_equal_to[2017]|less_than_equal_to['.$cyear .']');  
		if($this->session->userdata("user_role")=="subadmin"){
				$this->form_validation->set_rules('school_id', 'School  ', 'required');  
		}	
		 
		if($this->form_validation->run() == true )
		{
			
			$month = intval($this->input->post('month'));
			$year = intval($this->input->post('year'));
			if($month<10)
					$month = "0".$month;
			
			 redirect("attendence/monthly/".$month."/".$year);
		}
		
		$data["module"] = "attendence";
        $data["view_file"] = "month_year_form";
        echo Modules::run("template/admin", $data);
	}
	function monthly($month,$year){
            
			$months = array("01"=>"January","02"=>"February","03"=>"March","04"=>"April","05"=>"May",
									"06"=>"June","07"=>"July","08"=>"August","09"=>"September","10"=>"October","11"=>"November","12"=>"December");
				
			$month = intval($month);
			$year = intval($year);
			if($month<10)
					$month = "0".$month;
				
				$first_date = $year."-".$month."-01";
				$second_date = date("Y-m-t", strtotime($first_date));
				$month_name = $months[$month];	
			$school_id = $this->session->userdata("school_id");
			$uri_seg = $this->uri->uri_to_assoc();
			if(isset($uri_seg['edit']))//check whethere user is updating self school record or not , if not redirect to attendence page.
			{  
				$edit_attendence_id = $uri_seg['edit'];
				$rs = $this->db->query("select entry_date as cal_date from school_attendence where school_id=? and attendence_id=?",array($school_id,$edit_attendence_id) );
				if($rs->num_rows()==0)
				{
					redirect("attendence");
				} 
			}
			   
			
			$this->db->query("insert into school_attendence(school_id,entry_date) select ? as school_id,cal_date as entry_date from calender where cal_date between '2018-12-01' and CURRENT_DATE and cal_date not in (select entry_date as cal_date from school_attendence where school_id=?)",array($school_id,$school_id));
			 
		
					try{
						$crud = new grocery_CRUD($this);

						$crud->set_theme('flexigrid'); 
						$crud->set_table('school_attendence');
						$crud->where('school_id',$this->session->userdata("school_id"));
						$crud->where('entry_date >=', $first_date);
						$crud->where('entry_date <=', $second_date);
						$crud->order_by('entry_date','desc');
						$crud->set_subject('Attendence');
						$crud->edit_fields(array( 'cat1_attendence','cat2_attendence','cat3_attendence'));
			
						 $crud->callback_column('entry_date',array($this,'date_formatdisplay'));
						 $crud->callback_edit_field('entry_date',array($this,'date_formatdisplay'));
						
						$crud->callback_after_update(array($this, 'update_attendence_total_count'));
						
						 
							$crud->unset_add(); 
						 
						/*$edit_check = $this->db->query("select * from attendance_months_locks where month=? and year=? and locked_status='locked'",array(intval($month),$year));
						if($edit_check->num_rows()>0)
						{
							$crud->unset_edit(); 
						} */
						$crud->unset_edit(); 
						
						
						//before october 1st also remove edit option 
						$low_date = $this->db->query("select  ? < '2019-10-01' as low_date ",array($first_date))->row()->low_date;
						if($low_date==1)
						{
							$crud->unset_edit(); 
						} 
						
						$crud->unset_read();
						$crud->unset_delete();
						$crud->columns(array('entry_date','present_count'));
						 $crud->required_fields(array(   'cat1_attendence','cat2_attendence','cat3_attendence','cat1_guest_attendence','cat2_guest_attendence','cat3_guest_attendence'));
						 
						$crud->field_type('entry_date', 'readonly');
						
						 //$crud->callback_view_field('entry_date',array($this,'date_formatdisplay'));
						
						$crud->columns(array('entry_date','present_count','cat1_attendence','cat1_guest_attendence','cat2_attendence','cat2_guest_attendence','cat3_attendence','cat3_guest_attendence'));
						
						$crud->display_as('cat1_guest_attendence','Category 1 Guest Attendance')
							->display_as('cat2_guest_attendence','Category 2 Guest Attendance')
							->display_as('cat3_guest_attendence','Category 3 Guest Attendance');
						$crud->display_as('cat1_attendence','Category 1 Attendance')
							->display_as('cat2_attendence','Category 2  Attendance')
							->display_as('cat3_attendence','Category 3 Attendance');
						$crud->display_as('present_count','Total');
						 
						 
						$output = $crud->render();
						//echo $this->db->last_query();
						
						$school_name = $this->db->query("select concat(school_code,'-',name) as name from schools where school_id=?",array($this->session->userdata("school_id")))->row()->name;
						
						$data["module"] = "cms";
						$data["extra_content"] = "";
						$data["extra_content"] = "<p>Category 1 : up to 7th class<br>Category 2 : 8,9,10th classes <br>Category 3 : Intermediate and above</p><br><a href='".site_url('attendence')."' class='btn btn-primary'>Go Back</a>";
						$data["view_file"] = "cms";
						$output->title = $school_name ." - Attendance entries - ".$month_name ." - ".$year;
						$data["crud"] = $output;
						echo Modules::run("template/admin", $data);
						

					}catch(Exception $e){
						show_error($e->getMessage().' --- '.$e->getTraceAsString());
					}
				 
	
    }
 
	public function update_attendence_total_count($post_array,$primary_key)
	{
			 
			 $update_attendence_sql= "update  school_attendence set present_count=(cat1_attendence+cat2_attendence+cat3_attendence+cat1_guest_attendence	+cat2_guest_attendence	+cat3_guest_attendence	) where attendence_id='$primary_key'";
			 $this->db->query( $update_attendence_sql);
			 return true;
	}
	public function date_formatdisplay($value, $row)
		{
			 return date('d-M-Y',strtotime($value));
		}
}
