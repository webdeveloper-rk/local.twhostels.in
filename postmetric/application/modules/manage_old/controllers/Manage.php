<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Manage extends MX_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->database();
		$this->load->helper('url');

		$this->load->library('grocery_CRUD');
		 
		if($this->uri->segment(2) !="login") { 
					 Modules::run("security/is_admin");		 
					if ($this->session->userdata("is_loggedin") != TRUE || $this->session->userdata("user_id") == "" ) {
							redirect("admin/login");
							die;
					}
					 
					if ($this->session->userdata("user_role") != "subadmin" ) {
							redirect("dashboard");
							die;
					}
					
		}
					
            
		 
	}

	public function _example_output($output = null)
	{
		$this->load->view('example.php',$output);
	}

	function index()
	{
		redirect("manage/items");
	}
	public function items()
	{
		try{
			$crud = new grocery_CRUD();

			$crud->set_theme('flexigrid');
			$crud->set_theme('datatables');
			$crud->set_table('items');
			$crud->set_subject('Items');
			//$crud->unset_add();
            $crud->unset_delete();
			$crud->columns(array('telugu_name','item_name','item_type'));
			$crud->required_fields(array('telugu_name','item_name','item_type'));
			$crud->callback_after_insert(array($this, 'update_balancesheet_entries'));
			 
			 

			$output = $crud->render();

			//$this->_example_output($output);
			$data["module"] = "manage";
			$data["view_file"] = "cms";
			$output->title = "Manage Items";
			$data["crud"] = $output;
			echo Modules::run("template/admin", $data);
			

		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}
	 
	  
	public function  update_balancesheet_entries($post_array,$primary_key)
	{
			 
			 $item_id = $primary_key;
			 $update_balancesheet_sql  = "insert into balance_sheet(school_id,item_id,entry_date) select school_id,'$item_id' as item_id,CURRENT_DATE as entry_date from schools  where is_school=1";
			 $this->db->query( $update_balancesheet_sql,array($item_id));
			// $this->db->last_query();die;
			 return true;
	}
	
		  function fixed_rates(){
		
		try{
			$crud = new grocery_CRUD($this);
			 
			$crud->set_theme('flexigrid'); 
			$crud->set_table('fixed_rates');
		 
		 
			$crud->set_subject(' Fuel Charges');
			$crud->columns(array('school_code','school_id','item_name','amount' ));
			 
					 
			 $crud->unset_add(); 
            $crud->unset_delete();
			 
			
			$crud->edit_fields(array('school_code','school_id','item_name','amount' ));
			 

			$crud->set_relation('school_id','schools','name');
			$crud->field_type('school_id', 'readonly');
			$crud->field_type('school_code', 'readonly');
			$crud->field_type('item_name', 'readonly');
			$crud->display_as('school_id', 'School Name');
			 

			$output = $crud->render(); 
			$data["module"] = "cms";
			$data["extra_content"] = "";
			$data["view_file"] = "cms";
			$output->title = "Manage Fuel Charges ";
			$data["crud"] = $output;
			echo Modules::run("template/admin", $data);
			

		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}
		 
	
	 
}