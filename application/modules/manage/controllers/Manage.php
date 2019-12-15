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
			$crud->columns(array('telugu_name','item_name','item_code','item_type','min_price','max_price'));
			$crud->add_fields(array('telugu_name','item_name','item_code','item_type','min_price','max_price','status'));
			$crud->edit_fields(array('telugu_name','item_name','item_code','item_type','min_price','max_price','status'));
			$crud->required_fields(array('telugu_name','item_name','item_type'));
			$crud->callback_after_insert(array($this, 'update_balancesheet_entries'));
			$crud->unique_fields(array('telugu_name','item_name' ));
			 
			 

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
	
	public function atdos()
	{
		try{
			$crud = new grocery_CRUD();
 
			$crud->set_theme('datatables');
			$crud->set_table('users');
			$crud->set_subject('Atdos');
			$crud->where ('is_atdo', 1);
			$crud->display_as('school_code', 'Atdo code');
			 
			 
            $crud->unset_add();
            $crud->unset_edit();
            $crud->unset_delete();
			 $crud->columns(array('school_code','name','atdo_location','district_id','email'));
			 
			 $crud->callback_after_insert(array($this, 'updatepassword_roles'));
			 
			$crud->add_action('Assigned Hostels', '', 'assignschools/update','ui-icon-plus');
			
			$crud->required_fields(['school_code','name','email','password','district_id']);
			$crud->field_type('school_id','hidden','0');
			$crud->field_type('rpass','hidden','123456');
			$crud->field_type('is_atdo','hidden','1');
			$crud->field_type('is_dco','hidden','1');
			$crud->field_type('utype','hidden','subadmin');   
			$crud->field_type('contact_no','hidden','00000');   
			$crud->field_type('activation_code','hidden','');   
			$crud->field_type('registered_time','hidden','');   
			$crud->field_type('activated_time','hidden','');   
			$crud->field_type('registered_ip','hidden',$this->input->ip_address());   
			$crud->field_type('terms_acceptance','hidden','true');   
			$crud->field_type('operator_type','hidden','DEO');   
			$crud->field_type('status','hidden','A');   
			$crud->field_type('is_collector','hidden','0');   
			$crud->field_type('old_school_code','hidden',' ');   
			$crud->field_type('ddo_code','hidden','');   
			
			
			$crud->set_relation('district_id','districts','name');
			$crud->display_as('school_code','Atdo Code');
			$crud->display_as('district_id','District  Name');
			$crud->unique_fields(array('school_code','email'));
			
			
			$output = $crud->render();

			//$this->_example_output($output);
			$data["module"] = "manage";
			$data["view_file"] = "cms";
			$output->title = "Manage Atdos";
			$data["crud"] = $output;
			echo Modules::run("template/admin", $data);
			

		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}
	
	
	
	 public function items_per_head()
	{
		try{
			$crud = new grocery_CRUD();

			$crud->set_theme('flexigrid');
			$crud->set_theme('datatables');
			$crud->set_table('item_per_head');
			$crud->set_subject('Items Per head indent');
			$crud->unset_add();
            $crud->unset_delete();
			$crud->columns(array('item_id','grams_per_head' ));
			$crud->field_type('item_id','readonly');
			$crud->field_type('type_of_item','readonly'); 
			$crud->required_fields(array('grams_per_head' )); 
			 $crud->set_relation('item_id','items','{telugu_name}-{item_name}');
			 $crud->display_as('grams_per_head', 'KG Per head(Please convert grams into Kgs format)');
			 
			 

			$output = $crud->render();

			//$this->_example_output($output);
			$data["module"] = "manage";
			$data["view_file"] = "cms";
			$output->title = "Manage Indent Items grams per head on avg";
			$data["crud"] = $output;
			echo Modules::run("template/admin", $data);
			

		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}
	  public function  updatepassword_roles($post_array,$primary_key)
	{
			 
			 $uid = $primary_key;
			 $update_sql  = "update users set password=md5(password),ddo_code=school_code where uid=?";
			 $this->db->query( $update_sql,array($uid)); 
			 
			 $school_code = $this->db->query("select * from users where uid=?",array($uid))->row()->school_code;
			 
			 $ins_data = array('uid'=> $uid,'school_code'=>$school_code,'role_id'=>11,'start_date'=>date('y-m-d h:i:s'),'end_date'=>'2099-01-01');
			 $this->db->insert("user_roles", $ins_data );
			 return true;
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
		 
	/**************************************************************



*******************************************************************/

	  function  schools(){
		  
		  if($this->session->userdata("is_dco") == 1)
		{
			redirect('admin/subadmin/schoolreporttoday');
		}
		
		try{
			$crud = new grocery_CRUD($this);
			 
			$crud->set_theme('flexigrid'); 
			$crud->set_table('schools'); 
			$crud->set_subject('Schools');  
			$crud->where('is_school',1);  
			
			$crud->unset_add();
			$crud->unset_delete();
			$crud->columns(array('school_code','name', 'district_id',  'strength'));
			$crud->set_relation('district_id','districts','name');
			 
			
			$crud->add_fields(array('school_code','name','district_id','amount_category','school_type','strength'));
			$crud->edit_fields(array('school_code','name', 'district_id', 'strength'));
			$crud->field_type('school_code','readonly');
			

			$output = $crud->render(); 
			$data["module"] = "manage";
			$data["extra_content"] = "";
			$data["view_file"] = "cms";
			$output->title = "Manage Schools";
			$data["crud"] = $output;
			echo Modules::run("template/admin", $data);
			

		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}
	/*********************************************************************************************************
	
	
	
	*********************************************************************************************************/

			  function fuel_dates(){
		
		try{
			$crud = new grocery_CRUD($this);
			 
			$crud->set_theme('datatables'); 
			$crud->set_table('fuel_entry_dates');
		 
		 
			$crud->set_subject(' Fuel Charges Dates');
			$crud->columns(array('school_code','school_id' ,'start_date' ));
			 
					 
			 $crud->unset_add(); 
            $crud->unset_delete();
			 
			
			$crud->edit_fields(array('school_code','school_id','start_date'  ));
			 

			$crud->set_relation('school_id','schools','name');
			$crud->field_type('school_id', 'readonly');
			$crud->field_type('school_code', 'readonly'); 
			$crud->display_as('school_id', 'School Name');
			 

			$output = $crud->render(); 
			$data["module"] = "cms";
			$data["extra_content"] = "";
			$data["view_file"] = "cms";
			$output->title = "  Fuel Charges Dates ";
			$data["crud"] = $output;
			echo Modules::run("template/admin", $data);
			

		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}
	
	public function delete_vendor($vendor_annpurna_id)
	{
		$rs= $this->db->query("select * from balance_sheet where vendor_annapurna_id=?",array($vendor_annpurna_id));
		if($rs->num_rows()==0)
		{
			$school_id  = $this->db->query("select * from tw_vendors where vendor_annapurna_id=?",array($vendor_annpurna_id))->row()->school_id;
				//delete vendor 
				$this->db->query("delete from tw_vendors where vendor_annapurna_id=?",array($vendor_annpurna_id));
				redirect("manage/vendors/".$school_id);
		}else
		{
			$items_rs= $this->db->query("select * from items");
			$item_names = array();
			foreach($items_rs->result() as $item_row)
			{
				$item_names[$item_row->item_id]= $item_row->item_name;
			}
			
			echo "<h1>Access Denied. purchase entries exists</h1>" ;
			$purchase_entries = array();
			foreach($rs->result() as $row)
			{
				$purchase_entries[]= $row->entry_date. " - ".$item_names[$row->item_id];
			}
			echo "Purchase entries dates : <br><br><b>";
			echo implode("<br><br>",$purchase_entries);
			die;
		}
	}
	
	public function vendors($school_id)
	{
		
		$school_rs  = $this->db->query("select * from schools where school_id=?",array($school_id));
		if($school_rs->num_rows()==0)
		{
			die("<h1>School not found</h1>");
		}
		$school_info = $school_rs->row();
		$school_name = $school_info->school_code . " - ".$school_info->name;
		
		
					$ehostel_id = 0;
					$sc_rs = $this->db->query("select * from schools where school_id=?",array($school_id));
					$sc_info = $sc_rs->row();
					$ehostel_id = intval($sc_info->ehostel_id);
					if($ehostel_id==0)
					{
						$result_flags['success'] = false;
						$result_flags['msg'] = '<div class="alert alert-danger">Ehostel id not mapped .please contact administrator.</div>'; ;
						echo json_encode($result_flags);
						die;
					}
					
		
		$ehostel_vendor_id = $this->db->query("select max(ehostel_vendor_id) as ehostel_vendor_id from tw_vendors")->row()->ehostel_vendor_id;
					
					$ehostel_vendor_id = $ehostel_vendor_id + 1;
					//echo $ehostel_id;die;
					
		
		try{
			$crud = new grocery_CRUD();

			$crud->set_theme('flexigrid');
			$crud->set_theme('datatables');
			$crud->set_table('tw_vendors');
			$crud->set_subject(  'Vendors'); 
            $crud->unset_delete();
			 $crud->add_action('Delete', '', 'manage/delete_vendor');
			 
            $crud->unset_texteditor('vendor_address');
			$crud->where('school_id',$school_id);
			$crud->field_type('school_id', 'hidden', $school_id);
			$crud->field_type('ehostel_id', 'hidden', $ehostel_id);
			$crud->field_type('ehostel_vendor_id', 'hidden', $ehostel_vendor_id);
			 
			$crud->columns(array('vendor_type', 'vendor_name','business_nature', 'vendor_address','vendor_contact_number',  'vendor_bank','vendor_bank_branch', 'vendor_account_number' ));
			$crud->add_fields(array('school_id','ehostel_id','ehostel_vendor_id','vendor_type','vendor_name','business_nature','tin_number','vendor_address','vendor_contact_number','supplier_name','supplier_contact_number','vendor_bank','vendor_bank_branch','vendor_bank_ifsc','vendor_account_number','supplier_aadhar_number'));
			$crud->edit_fields(array('vendor_type', 'vendor_name','business_nature','tin_number','vendor_address','vendor_contact_number','supplier_name','supplier_contact_number','vendor_bank','vendor_bank_branch','vendor_bank_ifsc','vendor_account_number','supplier_aadhar_number'));
			$crud->required_fields(array('vendor_type', 'vendor_name','vendor_bank','vendor_bank_branch','vendor_bank_ifsc','vendor_account_number')); 
			 
			 $crud->set_rules('vendor_type','vendor type','required');
			 //$crud->set_rules('vendor_contact_number','vendor contact number','required|numeric');
			 
			 //$crud->set_rules('supplier_contact_number','supplier contact number','required|numeric');
			  
			 $crud->set_rules('vendor_name','vendor name','required');
			 $crud->set_rules('vendor_bank','vendor bank','required');
			 $crud->set_rules('vendor_bank_branch','vendor bank branch','required'); 
			 $crud->set_rules('vendor_bank_ifsc','vendor IFSC code','required'); 
			 $crud->set_rules('supplier_name','supplier name','required'); 
			// $crud->set_rules('supplier_aadhar_number','supplier aadhar number','required|numeric');
			  $crud->set_rules('vendor_account_number','vendor account_number','required|numeric|min_length[8]|greater_than[0]');
			 

			$output = $crud->render();

			//$this->_example_output($output);
			$data["module"] = "manage";
			$data["view_file"] = "cms";
			$output->title = $school_name . " - Manage Vendors";
			$data["crud"] = $output;
			echo Modules::run("template/admin", $data);
			

		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}
	
	 
}