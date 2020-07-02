<?php 
 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
set_time_limit(0);
 date_default_timezone_set('Asia/Kolkata');
class Report_crt_required_gps extends MX_Controller {

    function __construct() {
        parent::__construct();
		if($this->uri->segment(2) !="login") { 
					 Modules::run("security/is_admin");		 
					
		}
		if ($this->session->userdata("is_loggedin") != TRUE || $this->session->userdata("user_id") == "" ) {
							redirect("admin/login");
							die;
					}
					 			
					if($this->session->userdata("user_role") != "subadmin" &&  $this->session->userdata("user_role") != "dtdo") 
					{
						redirect("admin/login");
							die;
					}
		$this->load->helper('url');  
		$this->load->config("config.php");  
		$this->load->library("ci_jwt");  
		$this->load->library("excel");  
		$this->load->model("common/common_model");
	}
		/*************************************************************************
	
	
	
	*****************************************************************************/
	function index() {
		 
		 $data['display_result'] = false ;
		 $data['months'] = $months = array("01"=>"January","02"=>"February","03"=>"March","04"=>"April","05"=>"May",
									"06"=>"June","07"=>"July","08"=>"August","09"=>"September","10"=>"October","11"=>"November","12"=>"December");	
									
		 
		if($this->session->userdata('user_role') == "subadmin")
			$this->form_validation->set_rules('district_id', 'District ', 'required|numeric|greater_than[0]');  
		 
		 $this->form_validation->set_rules('type', 'Hidden ', 'required');  //dummy post data 
		 
		if($this->form_validation->run() == true    )
		{
			$data['district_id']=   $district_id = intval($this->input->post('district_id'));
			 
			 $data['display_result'] = true ;
			$rdata = array();
			
			$condition = '';
			$report_for = '';
			//print_a($this->session->all_userdata(),1);
			 
				if($this->session->userdata('is_dco') ==1)
				{
					
					$district_id = $this->session->userdata('district_id');
				}else
				{
					$district_id = $district_id ;
				}
				$condition = " and district_id='$district_id' ";
				$district_rs = $this->db->query("select  name from districts where district_id=?",array($district_id));
				if($district_rs->num_rows()==0)
				{
					redirect(current_url());
				}
				$report_for =  $district_rs->row()->name;
					
			 
				
				$rows = $this->common_model->get_crt_required($district_id,'gps'); 
				 
				$rdata['rows'] = $data['rows'] =  $rows;
				 
				if(strtolower($this->input->post("submit"))=="view report")
				{
					
				}else if(strtolower($this->input->post("submit"))=="download")
				{
					$this->download_report($rdata);
					die;
				}
		}
				
		 
        $condition = '';
				if($this->session->userdata('is_dco') ==1)
				{
					$district_id = $this->session->userdata('district_id');
					$condition = " and district_id='$district_id' ";
				}
				
		
		$data["districts_list"] = $this->db->query("select * from  districts where 1 $condition ");
        
        
        $data["rdata"] = $rdata;
        $data["module"] = "report_crt_required_gps";
        $data["view_file"] = "districtwise_report";
        echo Modules::run("template/admin", $data);
    }
	
	/********************************************************************
	
	
	********************************************************************/
	public function download_report($rdata)
    {
				//print_a($rdata,1);
				$this->excel->setActiveSheetIndex(0);
                //name the worksheet
				$title = $rdata['district_name'] . ' District  -GPS - CRT REQUIRED Report ';
				$headtitle = ' GOVERNMENT OF TELANGANA';
				
                $this->excel->getActiveSheet()->setTitle(substr($title,0,30) );
              
                $this->excel->getActiveSheet()->setCellValue('A1', $headtitle);
				//merge cell A2 until Q2
                $this->excel->getActiveSheet()->mergeCells('A1:M1');
				
				$this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                //make the font become bold
                $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
                $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(16);
                $this->excel->getActiveSheet()->getStyle('A1')->getFill()->getStartColor()->setARGB('#333');
				
				 $second_title= 'TRIBAL WELFARE DEPARTMENT  ,'.$rdata['district_name'].'  DIST - GPS - CRT REQUIRED';
				 $this->excel->getActiveSheet()->setCellValue('A2', $second_title);
				//merge cell A2 until Q2
                $this->excel->getActiveSheet()->mergeCells('A2:M2');
				
				$this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                //make the font become bold
                $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
                $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setSize(16);
                $this->excel->getActiveSheet()->getStyle('A2')->getFill()->getStartColor()->setARGB('#333');
				
				
				 $third_title= 'Details of School wise, Class wise Strength and Teacher Posts in Govt. Primary Schools';
				 $this->excel->getActiveSheet()->setCellValue('A3', $third_title);
				//merge cell A2 until Q2
                $this->excel->getActiveSheet()->mergeCells('A3:M3');
				
				$this->excel->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                //make the font become bold
                $this->excel->getActiveSheet()->getStyle('A3')->getFont()->setBold(true);
                $this->excel->getActiveSheet()->getStyle('A3')->getFont()->setSize(16);
                $this->excel->getActiveSheet()->getStyle('A3')->getFill()->getStartColor()->setARGB('#333');
				
				
				///////////////////////////////////////////////////////////////////////////////////////////////////
				$strengths = $rdata['strengths'];
				$working = $rdata['working'];
				$required_counts = $rdata['required_counts'];
				
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

				$this->excel->getActiveSheet()->getStyle('A4:Z4')->applyFromArray( $style_header );

				
				$this->excel->getActiveSheet()->setCellValue('A4', " S.NO " );
				$this->excel->getActiveSheet()->setCellValue('B4',  'Name of GPS');	 
				$this->excel->getActiveSheet()->setCellValue('C4',  'Agency / Non Agency'); 
				$this->excel->getActiveSheet()->setCellValue('D4',  'SGT CRT REQUIRED'); 
				 
				$this->excel->getActiveSheet()->getStyle('A4:M4')->getFont()->setBold(true); 		
				 
				 
				 
				
 
			 
					 
			 
                 $this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                //make the font become bold
                $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
				$this->excel->getActiveSheet()->getStyle('A2:S2')->getFont()->setBold(true);
			 
                $i = 5;
				$sno=1;
				  
				 $sno = 1;
				 $grand_total = 0;
				 $posts_total = array();
				 $posts_array = array(2);
				 $rows = $rdata['rows'];
				foreach($rows as  $index=>$crt_required_data){ 
				
				$school_info = $crt_required_data['school_info'];
				$posts_info = $crt_required_data['posts'];
				 
						
						
						$total_required = 	intval($posts_info[2]) ;
						foreach($posts_array as $post_id)
						{
							$posts_total[$post_id] = intval($posts_total[$post_id]) + intval($posts_info[$post_id]);
						}		
					$grand_total =  $grand_total + 	$total_required;			
											
											
 
							 
							$this->excel->getActiveSheet()->setCellValue('A'.$i, $sno );
							$this->excel->getActiveSheet()->setCellValue('B'.$i,  $school_info->name);	
							$this->excel->getActiveSheet()->setCellValue('C'.$i,  $school_info->school_agency);	
							$this->excel->getActiveSheet()->setCellValue('D'.$i,  $total_required);	
							 
					 
					 
					$i++;$sno++;
				}
	 
				    $this->excel->getActiveSheet()->setCellValue('C'.$i, 'Total');
					$this->excel->getActiveSheet()->setCellValue('D'.$i, $grand_total);
					$this->excel->getActiveSheet()->getStyle('A'.$i.':S'.$i)->getFont()->setBold(true);
					 
                
              
                $filename="GPS_".$second_title ."_".date('d-M-Y')	.'.xls'; //save our workbook as this file name
                header('Content-Type: application/vnd.ms-excel'); //mime type
                header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
                header('Cache-Control: max-age=0'); //no cache
 
                //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
                //if you want to save it as .XLSX Excel 2007 format
                $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');  
                //force user to download the Excel file without writing it to server's HD
				ob_end_clean(); 
                $objWriter->save('php://output');
                 
    }


}
