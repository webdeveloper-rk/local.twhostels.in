<?php 
 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
set_time_limit(0);
 date_default_timezone_set('Asia/Kolkata');
class Report_gps_districtwide extends MX_Controller {

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
					
			 
				$schools_list = $this->db->query("select * from schools where district_id=? and trim(lower(school_type))='gps'",array($district_id));
				$rdata['schools_list']=$schools_list;
				$rdata['district_name']=$district_rs->row()->name;
			
				$post_id = 2 ; //hard coded from database table posts for SGT's
			 
				$strengths_list = $this->db->query("select school_id,sum(boys+girls) as strength from school_class_strengths where school_id in(select school_id from schools where district_id=? and trim(lower(school_type))='gps')  group by school_id ",array($district_id));
				$strengths= array();
				$required_counts = array();
				foreach($strengths_list->result() as $row)
				{
					$strengths[$row->school_id] = $row->strength;
					 $required_rs = $this->db->query("select required_count from go_enrolements where    post_id=? and ? between enrole_from and enrole_to"
								,array($post_id,$row->strength));
					$required_count  = 0;
					if($required_rs->num_rows()>0)
					{
						$required_counts[$row->school_id]  = $required_rs->row()->required_count;
					}
				}
				$rdata['strengths']=$strengths;
				$rdata['required_counts']=$required_counts;
				
				
				//Working Employees under SGT's
				$working_list = $this->db->query("select school_id,count(*) as emp_count from employee_info where post_id=? and school_id in(select school_id from schools where district_id=? and trim(lower(school_type))='gps')  group by school_id ",array($post_id,$district_id));
				$working= array();
				foreach($working_list->result() as $row)
				{
					$working[$row->school_id] = $row->emp_count;
				}
				$rdata['working']=$working;
				
				
				 
				
			
			 
				$this->download_consumption_report($rdata);
			 
			 
		 }
        $condition = '';
		if($this->session->userdata('is_dco') ==1)
				{
					$district_id = $this->session->userdata('district_id');
					$condition = " and district_id='$district_id' ";
				}
				
		
		$data["districts_list"] = $this->db->query("select * from  districts where 1 $condition ");
        
        
        $data["module"] = "report_gps_districtwide";
        $data["view_file"] = "districtwise_report";
        echo Modules::run("template/admin", $data);
    }
	
	/********************************************************************
	
	
	********************************************************************/
	public function download_consumption_report($rdata)
    {
				//print_a($rdata,1);
				$this->excel->setActiveSheetIndex(0);
                //name the worksheet
				$title = $rdata['district_name'] . ' District Report ';
				$headtitle = ' GOVERNMENT OF TELANGANA';
				
                $this->excel->getActiveSheet()->setTitle($title );
              
                $this->excel->getActiveSheet()->setCellValue('A1', $headtitle);
				//merge cell A2 until Q2
                $this->excel->getActiveSheet()->mergeCells('A1:M1');
				
				$this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                //make the font become bold
                $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
                $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(16);
                $this->excel->getActiveSheet()->getStyle('A1')->getFill()->getStartColor()->setARGB('#333');
				
				 $second_title= 'TRIBAL WELFARE DEPARTMENT  ,'.$rdata['district_name'].'  DIST';
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
				$this->excel->getActiveSheet()->setCellValue('C4',  'Mandel');	
				$this->excel->getActiveSheet()->setCellValue('D4',  'Agency / Non Agency');	
				$this->excel->getActiveSheet()->setCellValue('E4',  'Strength');	
				$this->excel->getActiveSheet()->setCellValue('F4',  'Secondary grade Teachers required as per G.O.Ms. No.17'); 
				$this->excel->getActiveSheet()->setCellValue('G4',  'Working');	 
				$this->excel->getActiveSheet()->setCellValue('H4',  'CRTs to be engaged  '); 
				$this->excel->getActiveSheet()->setCellValue('I4',  'Remarks'); 
				$this->excel->getActiveSheet()->getStyle('A4:M4')->getFont()->setBold(true); 		
				 
				 
				 
				
 
			 
					 
			 
                 $this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                //make the font become bold
                $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
				$this->excel->getActiveSheet()->getStyle('A2:S2')->getFont()->setBold(true);
			 
                $i = 5;
				$sno=1;
				$consumed_total = 0;
				foreach($rdata['schools_list']->result()  as  $school_data){  
							$rcount = intval(@$required_counts[$school_data->school_id]);
							$working_count = intval(@$working[$school_data->school_id]);
							
							$crt_required = $rcount - $working_count;
							 
							$this->excel->getActiveSheet()->setCellValue('A'.$i, $sno );
							$this->excel->getActiveSheet()->setCellValue('B'.$i,  $school_data->name);	
							$this->excel->getActiveSheet()->setCellValue('C'.$i,  $school_data->mandel);	
							$this->excel->getActiveSheet()->setCellValue('D'.$i,  $school_data->school_agency);	
							$this->excel->getActiveSheet()->setCellValue('E'.$i,  $strengths[$school_data->school_id]);	//'Strength'
							$this->excel->getActiveSheet()->setCellValue('F'.$i,   $rcount );//'Secondary grade Teachers required as per G.O.Ms. No.17'); 
							$this->excel->getActiveSheet()->setCellValue('G'.$i,  $working_count);//'Working');	 
							$this->excel->getActiveSheet()->setCellValue('H'.$i,  $crt_required);//'CRTs to be engaged  '); 
							$this->excel->getActiveSheet()->setCellValue('I'.$i,  '');//'Remarks'); 
					 
					 
					$i++;$sno++;
				}
	 
				  /* $this->excel->getActiveSheet()->setCellValue('A'.$i, 'Total Purchased');
					$this->excel->getActiveSheet()->setCellValue('B'.$i, $consumed_total);
					$this->excel->getActiveSheet()->getStyle('A'.$i.':S'.$i)->getFont()->setBold(true);
					*/
                
              
                $filename=$second_title ."_".date('d-M-Y')	.'.xls'; //save our workbook as this file name
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
