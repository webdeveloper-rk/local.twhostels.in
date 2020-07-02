<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
set_time_limit(0);
date_default_timezone_set('Asia/Kolkata');
class Menu_pic extends MX_Controller {

    function __construct() {
        parent::__construct();
		if($this->uri->segment(2) !="login") { 
					 Modules::run("security/is_admin");		 
					
		}
			$this->load->helper('url');  
			$this->load->config("config.php");

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

   	public function index(){
		 
		 
		
		$this->form_validation->set_rules('bill_type', 'Bill Type ', 'required|numeric');    
		 
		if (empty($_FILES['menu_image']['name']))
		{
				$this->form_validation->set_rules('menu_image', 'Menu Picture', 'required');
		}
		 
		if($this->form_validation->run() == true  )
		{ 
			$bill_type = intval($this->input->post('bill_type')); 
			$extention = strtolower(pathinfo($_FILES['menu_image']['name'],PATHINFO_EXTENSION ));
			if(!in_array($extention,$this->config->item('allowed_types')))
			{
					$this->session->set_flashdata('message', '<div class="alert alert-danger">Invalid Menu picture. Only JPG, PNG types allowed.</div>');
					redirect('menu_pic');
			}
			else if($extention != "pdf" && !getimagesize($_FILES['menu_image']['tmp_name'])){
						$this->session->set_flashdata('message', '<div class="alert alert-danger">Looks like not a image. Only JPG, PNG types allowed.</div>');
						redirect('menu_pic');
					}
			else
			{
				 
					//start upload images 
					$uploaded_file = $this->do_upload() ;
					 
			}
			
		}
		  $file_path = $this->db->query("select * from menu_pic where status='1' ")->row()->menu_pic_path;
		
		$data["menu_pic_path"] = site_url()."assets/uploads/menu_pic/".$file_path;
		$data["module"] = "menu_pic";
        $data["view_file"] = "menupic_form";
        echo Modules::run("template/admin", $data);
	}
	
	  public function do_upload()
        {
                $config['upload_path']          = './assets/uploads/menu_pic/';
                $config['allowed_types']        = 'gif|jpg|png';
                $config['max_size']             = 1000;
                $config['max_width']            = 2024;
                $config['max_height']           = 2024;

                $this->load->library('upload', $config);

                if ( ! $this->upload->do_upload('menu_image'))
                {
                        $error =   $this->upload->display_errors() ;

                        $this->session->set_flashdata('message', '<div class="alert alert-danger">'.$error.'</div>');
						redirect('menu_pic');
                }
                else
                {
                        $data = array('upload_data' => $this->upload->data());
						$file_name= $data['upload_data']['file_name'];
						//menu_pic_path
						$this->db->query("update menu_pic set status='0' where status='1'"); 
						 
						 //insert into database 
						 $ndata = array();
						$ndata['menu_pic_path'] = $file_name;
						$ndata['status'] = 1;
						$this->db->insert("menu_pic",$ndata);
						 
						 $this->session->set_flashdata('message', '<div class="alert alert-success">Uploaded Successfully.</div>');
							redirect('menu_pic');
                }
        }

	 
}
