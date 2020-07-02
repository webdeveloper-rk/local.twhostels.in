<?php 
 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
set_time_limit(0);
 date_default_timezone_set('Asia/Kolkata');
class School_day_report extends MX_Controller {

    function __construct() {
        parent::__construct();
		if($this->uri->segment(2) !="login") { 
					 Modules::run("security/is_admin");		 
					
		}
		if ($this->session->userdata("is_loggedin") != TRUE || $this->session->userdata("user_id") == "" ) {
							redirect("admin/login");
							die;
					}
					$allowed_roles = array('school','subadmin','dco','collector','report_viewer','secretary','gcc');		
					if(!in_array($this->session->userdata("user_role"),$allowed_roles))
					{
						redirect("admin/login");
							die;
					}
		$this->load->helper('url');  
		$this->load->config("config.php");  
		$this->load->model("common/common_model");  
		$this->load->library("ci_jwt");  
		$this->load->library("excel");  
	}
	function index()
	{
		redirect("school_day_report/display_menu");
	}
   function view($date_encoded= null)
   {
	   
		$data["school_date"] 		= '';
		$data["formated_date"] 	='';
		$data["result_display"] 	= false;
				 
		 if($date_encoded==null)
		 {
			$date = date('Y-m-d');
		 }
		 else{
				injection_check();				 
				
				$inputs = $this->ci_jwt->jwt_web_decode($date_encoded);		
				//print_a($inputs );				
				$data["school_date"] 		=  $inputs->school_date ;
				$data["school_id"] 		=  $inputs->school_id ;
				$data["formated_date"] 	= $inputs->formated_date;
				$data["report_date"] 	= date('d-m-Y',strtotime($inputs->formated_date));
				$data["result_display"] 	= true;
				 
				 
				
				if($this->session->userdata("user_role")=="school")
				{
					$school_id = intval($this->session->userdata("school_id"));		
				}
				else
				{
					$school_id = $inputs->school_id ;	
				}
				    $stock_entry_table = $this->common_model->get_stock_entry_table($inputs->formated_date);
				 
				
				$sql = "SELECT it.item_name,it.telugu_name,bs.*,
									(
										(session_1_qty*session_1_price) +
										(session_2_qty*session_2_price)+ 
										(session_3_qty*session_3_price) + 
										(session_4_qty*session_4_price)
									) as today_consumed

									FROM $stock_entry_table  bs inner join items  it on bs.item_id=it.item_id WHERE `school_id`=? and 
									`entry_date`=? order by closing_quantity desc";
				$rset  = $this->db->query($sql,array($school_id,$inputs->formated_date));
				//print_statement($this->db->last_query());
				$data["rset"] 	= $rset;
				$data["school_name"] 	= $this->db->query("select concat(school_code,'-',name) as name from schools where school_id=?",array($school_id))->row()->name;
		
		
		 }
		 
		 
		$this->form_validation->set_rules('school_date', 'Date ', 'required');      
			if($this->session->userdata("user_role")!="school")
			{ 
				$this->form_validation->set_rules('school_id', 'School ', 'required|numeric');    
			}		
		  
		if($this->form_validation->run() == true )
		{
			if(!chk_date_format($this->input->post('school_date')))
			{
				$this->session->set_flashdata('message', '<div class="alert alert-danger">Invalid Date format. ex: mm/dd/YYYY</div>');
				redirect('school_day_report/view');
			}
			else
			{
				if($this->session->userdata("user_role")=="school")
				{
					$school_id = intval($this->session->userdata("school_id"));		
				}
				else
				{
					$school_id =   intval($this->input->post('school_id'));
				}
				
				$posted_date = $this->input->post('school_date');
				
				$formated_date = date('Y-m-d',strtotime($posted_date));
				$inputs = array('school_date'=>$posted_date,'formated_date'=>$formated_date,'school_id'=>$school_id);
				$encoded_input = $this->ci_jwt->jwt_web_encode($inputs);
				redirect("school_day_report/view/".$encoded_input);				
			}
		}
		 
		 
       
         
        $data["module"] = "school_day_report";
        $data["view_file"] = "report_form";
        echo Modules::run("template/admin", $data);
   }
   
   function display_menu()
   {
	    $file_path = $this->db->query("select * from menu_pic where status='1' ")->row()->menu_pic_path;
		
		$data["menu_pic_path"] = site_url()."assets/uploads/menu_pic/".$file_path;
		$data["module"] = "school_day_report";
        $data["view_file"] = "menu_pic";
        echo Modules::run("template/admin", $data);
   }
	
function view_balances($date_encoded= null)
   {
	   
		$data["school_date"] 		= '';
		$data["formated_date"] 	='';
		$data["result_display"] 	= false;
				 
		 if($date_encoded==null)
		 {
			$date = date('Y-m-d');
		 }
		 else{
				 		 
				
				$inputs = $this->ci_jwt->jwt_web_decode($date_encoded);		
				// print_a($inputs );				
				$data["school_date"] 		=  $inputs->school_date ;
				$data["school_id"] 		=  $inputs->school_id ;
				$data["formated_date"] 	= $inputs->formated_date;
				$data["submit"] 	= $inputs->submit;
				$data["report_date"] 	= date('d-m-Y',strtotime($inputs->formated_date));
				$data["result_display"] 	= true;
				 
				 

				
				$sql = "SELECT  sc.school_code,sc.name,sc.district_name,it.item_name,bs.opening_quantity as balance_qty 

									FROM `balance_sheet` bs inner join items  it on bs.item_id=it.item_id inner join schools sc on sc.school_id = bs.school_id  WHERE  
									`entry_date`=? and opening_quantity<0 order by   sc.district_name,sc.school_code asc ";
				$rset  = $this->db->query($sql,array($inputs->formated_date));
				 //echo $this->db->last_query();
				$data["rset"] 	= $rset;
				if($inputs->submit=="download"){
					//download code here 
					$this->download_negative_balances_report($data);
					die;
				}else {
					
				}
				 
		
		 }
		 
		 
		$this->form_validation->set_rules('school_date', 'Date ', 'required');      
			 
		  
		if($this->form_validation->run() == true )
		{
			if(!chk_date_format($this->input->post('school_date')))
			{
				$this->session->set_flashdata('message', '<div class="alert alert-danger">Invalid Date format. ex: mm/dd/YYYY</div>');
				redirect('school_day_report/view_balances');
			}
			else
			{
				 
				 $posted_date = get_db_date2($this->input->post('school_date'));
				 $submit_text =  strtolower($this->input->post('submit')) ;
				
				$formated_date = date('Y-m-d',strtotime($posted_date));
				$inputs = array('school_date'=>$posted_date,'formated_date'=>$formated_date,'submit'=>$submit_text);
				//print_a($inputs,1);
				$encoded_input = $this->ci_jwt->jwt_web_encode($inputs);
				redirect("school_day_report/view_balances/".$encoded_input);				
			}
		}
		 
		 
       
         
        $data["module"] = "school_day_report";
        $data["view_file"] = "report_negative_balances";
        echo Modules::run("template/admin", $data);
   }
   	/********************************************************************
	
	
	********************************************************************/
	private function download_negative_balances_report($rdata)
    {
		$report_date = $rdata['report_date'];
		$rset  = $rdata['rset'];
				//print_a($rdata,1);
				$this->excel->setActiveSheetIndex(0);
                //name the worksheet
				$title = 'Negative Balances Report ';
				$headtitle = 'Negative Balances Report - '.$report_date;
                $this->excel->getActiveSheet()->setTitle( );
               
                $this->excel->getActiveSheet()->setCellValue('A1', $headtitle);
				//merge cell A2 until Q2
                $this->excel->getActiveSheet()->mergeCells('A1:G1');
				
				$this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                //make the font become bold
                $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
                $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(16);
                $this->excel->getActiveSheet()->getStyle('A1')->getFill()->getStartColor()->setARGB('#333');
				///////////////////////////////////////////////////////////////////////////////////////////////////
				
				
						$default_border = array(
												'style' => PHPExcel_Style_Border::BORDER_THIN,
												'color' => array('rgb'=>'3396FF')
												);
						$style_header = array(
													'borders' => array(
													'bottom' => $default_border,
													'left' => $default_border,
													'top' => $default_border,
													'right' => $default_border,
													),
													'fill' => array(
													'type' => PHPExcel_Style_Fill::FILL_SOLID,
													'color' => array('rgb'=>'3396FF'),
													),
													'font' => array(
													'bold' => true,
													'color' =>  array('rgb'=>'FFFFFF'),
													)
											);

				$this->excel->getActiveSheet()->getStyle('A2:Z2')->applyFromArray( $style_header );

				
				$this->excel->getActiveSheet()->setCellValue('A2', " School Code " );
				$this->excel->getActiveSheet()->setCellValue('B2',  'School Name');	
				$this->excel->getActiveSheet()->setCellValue('C2',  'District Name');	
				$this->excel->getActiveSheet()->setCellValue('D2',  'Item Name');	
				$this->excel->getActiveSheet()->setCellValue('E2',  'Balance');	
				$this->excel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);				
				$this->excel->getActiveSheet()->getStyle('B2')->getFont()->setBold(true);				
				$this->excel->getActiveSheet()->getStyle('C2')->getFont()->setBold(true);				
				$this->excel->getActiveSheet()->getStyle('D2')->getFont()->setBold(true);				
				$this->excel->getActiveSheet()->getStyle('E2')->getFont()->setBold(true);				
				 
				 
				 
				
 
			 
					 
			 
                 $this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                //make the font become bold
                $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
				$this->excel->getActiveSheet()->getStyle('A2:S2')->getFont()->setBold(true);
			 
                $i = 3;
				$sno=1;
				 
				foreach($rdata['rset']->result()  as  $school_data){ 
				 
				  
					$this->excel->getActiveSheet()->setCellValue('A'.$i, $school_data->school_code);
					$this->excel->getActiveSheet()->setCellValue('B'.$i, $school_data->name);
					$this->excel->getActiveSheet()->setCellValue('C'.$i, $school_data->district_name);
					$this->excel->getActiveSheet()->setCellValue('D'.$i, $school_data->item_name);
					$this->excel->getActiveSheet()->setCellValue('E'.$i, $school_data->balance_qty);
					 
					
					 
					 
					$i++;$sno++;
				}
	  
                $filename=$headtitle .date('d-M-Y')	.'.xls'; //save our workbook as this file name
                header('Content-Type: application/vnd.ms-excel'); //mime type
                header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
                header('Cache-Control: max-age=0'); //no cache
 
                //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
                //if you want to save it as .XLSX Excel 2007 format
                $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');  
                //force user to download the Excel file without writing it to server's HD
				ob_end_clean();ob_start();
                $objWriter->save('php://output');
                 
    }
	
	
}
