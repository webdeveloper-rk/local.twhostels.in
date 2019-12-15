<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Opening_balance extends MX_Controller {

    function __construct() {
        parent::__construct(); 
         $this->load->config("config.php");
         $this->load->model("admin/school_model");
		 if($this->session->userdata("user_role") != "school")
		{
				redirect("general/logout"); 
		}
		$this->load->model("common/common_model");  
    }

   

    function index() {

		
        $this->listitems();

    }
	function listitems()
	{
		$school_id = $this->session->userdata('school_id');
		$opening_balance_start_date = $this->config->item('opening_balance_start_date');
		$opening_balance_end_date = $this->config->item('opening_balance_end_date');
		$opening_academic_year = $this->config->item('opening_academic_year');
		 
		 $sql = " insert into school_opening_balance( school_id,  academic_year,item_id,opening_balance_date,editable_end_date)
					select ? as school_id,? as academic_year,item_id,? as opening_balance_date,? as editable_end_date from items where status=1 and   item_id  
								not in (select item_id  from school_opening_balance where school_id=? and  academic_year=? ) ";
			$rs = $this->db->query($sql,array($school_id,$opening_academic_year,$opening_balance_start_date,$opening_balance_end_date,$school_id,$opening_academic_year));
	 
		$sql = "select editable_end_date>=CURRENT_DATE as is_editable,it.item_id,it.telugu_name,it.item_name,DATE_FORMAT(opening_balance_date, '%d-%M-%Y')  as  ob_date_frmted ,qty as ob_qty from items it left join  school_opening_balance sob    on sob.item_id= it.item_id and sob.school_id= ?    and sob.academic_year=? where it.status='1' order by it.item_name asc ";
		
		$rs = $this->db->query($sql,array($school_id,$this->config->item('opening_academic_year')));
		// print_statement($this->db->last_query());
		$data['rset'] = $rs;
		 
		 
		$data["module"] = "opening_balance"; 
        $data["view_file"] = "openingbalance"; 
        echo Modules::run("template/admin", $data);
	}


	function update($item_id='0'){

		$data['allowed_to_update'] =  $allowed_to_update = $this->allowed_to_update($item_id);
		 
		$school_id = $this->session->userdata('school_id');
		$opening_balance_start_date = $this->config->item('opening_balance_start_date');
		$opening_balance_end_date = $this->config->item('opening_balance_end_date');
		$opening_academic_year = $this->config->item('opening_academic_year');
		 
		$ob_rs = $this->db->query("select * from  school_opening_balance where school_id=? 
										and    item_id=? and academic_year=?" ,
										array($school_id, $item_id,$opening_academic_year));
		if($ob_rs->num_rows()==0)
		{
			//check if record already exists in balance sheet/stock entry table 
			$this->session->set_flashdata('message', '<div class="alert alert-danger">Please contact Administrator to resolve this opening balance.</div>');
					redirect('opening_balance/');
		}	
		else{
			$balance_entry_date  = $ob_rs->row()->opening_balance_date;
		}			
		$item_data= $this->db->query("select * from items where item_id=?",array($item_id))->row();  
		
		if($allowed_to_update==true)
		{
				$this->form_validation->set_rules('quantity', 'Quantity', 'required|numeric');
                $this->form_validation->set_rules('price', 'Price', 'required|numeric');

                if ($this->form_validation->run() == FALSE)
                {
                        //nothing to do 
                }
				else {
					//update entries 
					$opening_quantity = $this->input->post('quantity');
					$opening_price = $this->input->post('price');
					
					/*if($this->config->item("site_name")=="twhostels"){
						 $is_excempted = $this->common_model->fixed_rate_item_excemption($item_id,$school_id);
							if($is_excempted == false) { 
												$opening_price	=	$this->common_model->get_item_fixed_price($item_id,$school_id);
							}
						}*/
						
					 $stock_entry_table = $this->common_model->get_stock_entry_table($balance_entry_date);
					 
					$ob_rs = $this->db->query("select * from   $stock_entry_table  where school_id=? 
										and    item_id=? and entry_date=?" ,
										array($school_id, $item_id,$balance_entry_date));
									//	echo $this->db->last_query();
										
					if(	$ob_rs->num_rows()==0)
					{
						$this->db->query("insert into  $stock_entry_table  set school_id=? 
										,    item_id=? , entry_date=?" ,
										array($school_id, $item_id,$balance_entry_date));
									
						$ob_rs = $this->db->query("select * from   $stock_entry_table  where school_id=? 
										and    item_id=? and entry_date=?" ,
										array($school_id, $item_id,$balance_entry_date));				
					}						
					$bs_data = 	$ob_rs->row();			
					$entry_id = $bs_data->entry_id;
					
					 
					
					$update_sql = "update school_opening_balance set qty=? , price=?  where   school_id=?   and item_id=? and academic_year=?";
					$this->db->query($update_sql,array($opening_quantity,$opening_price,$school_id,  $item_id,$opening_academic_year));
					 
					
					$update_sql = "update  $stock_entry_table set opening_quantity=? , opening_price=?  where entry_id= ?";
					$this->db->query($update_sql,array($opening_quantity,$opening_price,$entry_id));
					 
					
					//update to purchases table also 
					$prs= $this->db->query("select * from purchases where school_id=? and item_id=? and purchase_date='2016-08-01' ",array($school_id,$item_id));
					if($prs->num_rows()==0)
					{
							$update_sql = "insert into purchases set   school_id=?   , item_id=? ,purchase_date='2016-08-01',quantity=? ,purchase_price=? ";
							$this->db->query($update_sql,array($school_id,$item_id,$opening_quantity,  $opening_price));
					}else{
						$update_sql = "update  purchases set   quantity=? ,purchase_price=?  where  school_id=?  and  item_id=? and purchase_date='2016-08-01' ";
							$this->db->query($update_sql,array($opening_quantity,  $opening_price,$school_id,$item_id));
							//echo $this->db->last_query();die;
					}
					 
					$this->school_model->update_entries($school_id,$item_id,$balance_entry_date);
					
					 //echo $update_sql;die;
					$this->session->set_flashdata('message', '<div class="alert alert-success">Succesfully updated</div>');
					redirect('opening_balance/');
					
				}
		}
		$ob_rs = $this->db->query("select *,date_format(opening_balance_date,'%d-%M-%Y') as ob_date from   school_opening_balance  where school_id=? 
										and    item_id=? and opening_balance_date=?" ,
										array($school_id, $item_id,$balance_entry_date));				
		 						
					$bs_data = 	$ob_rs->row();		
		
		$data['bs_data'] =  $bs_data;
		$data['item_data'] =  $item_data;
		$data['open_date'] =  date('d-m-Y',strtotime($opening_balance_start_date));
		
        $data["module"] = "opening_balance";
        $data["view_file"] = "openingbalance_entryform";
        echo Modules::run("template/admin", $data);

    }
	private function allowed_to_update($item_id)
	{
		$school_id = $this->session->userdata('school_id');		 
		$academic_year = $this->config->item('opening_academic_year'); 		
		$sql = "SELECT editable_end_date>= CURRENT_DATE  as allowed from school_opening_balance where school_id=? and item_id=? and academic_year=?";
		$rs = $this->db->query($sql,array($school_id,$item_id,$academic_year));
		 
		$allowed = $rs->row()->allowed;
		if($allowed > 0 )
		{
			return true;
		}
		else {
			return false;
		}
	}
	function install()
	{
		$create_sql = "CREATE TABLE IF NOT EXISTS `school_opening_balance` (
													  `sid` int(11) NOT NULL AUTO_INCREMENT,
													  `academic_year` varchar(255) NOT NULL,
													  `school_id` int(11) NOT NULL,
													  `item_id` int(11) NOT NULL,
													  `qty` decimal(10,3) NOT NULL DEFAULT '0.000',
													  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
													  `opening_balance_date` date NOT NULL,
													  `editable_end_date` date NOT NULL,
													  `status` enum('1','0') NOT NULL DEFAULT '1',
													  PRIMARY KEY (`sid`),
													  KEY `school_id` (`school_id`),
													  KEY `item_id` (`item_id`),
													  KEY `academic_year` (`academic_year`)
													) ENGINE=MyISAM DEFAULT CHARSET=latin1; ";
		$this->db->query($create_sql);	
		
		$insert_sql  = "select school_id,item_id ? as entry_date from schools,items";
		
		
		
	}
	function old()
	{
		$school_id = $this->session->userdata('school_id');
		$opening_balance_start_date = $this->config->item('opening_balance_start_date');
		$opening_balance_end_date = $this->config->item('opening_balance_end_date');
		
		$sql = "select * from balance_sheet bs inner join items it on bs.item_id= it.item_id and it.status='1' and bs.school_id=? where entry_date=?";
		$rs = $this->db->query($sql,array($school_id,$opening_balance_start_date));
		//if($this->input->ip_address()=="103.49.53.146")
		//	echo $this->db->last_query();
		$data['rset'] = $rs;
		$data['open_date'] =  date('d-m-Y',strtotime($opening_balance_start_date));
		$data['allowed_to_update'] = $this->allowed_to_update(); 
		$data["module"] = "opening_balance"; 
        $data["view_file"] = "openingbalance_old"; 
        echo Modules::run("template/admin", $data);
	}
 
}

