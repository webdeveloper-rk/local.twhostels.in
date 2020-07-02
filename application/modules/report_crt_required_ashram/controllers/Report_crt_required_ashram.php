<?php 
 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
set_time_limit(0);
 date_default_timezone_set('Asia/Kolkata');
class Report_crt_required_ashram extends MX_Controller {

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
		$this->load->model("common/common_model");  
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
					//redirect(current_url());
					die("Invalid District_id");
				}
				$report_for =  $district_rs->row()->name; 
			 
				$rows = $this->common_model->get_crt_required($district_id,'ashram'); 
				 
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
					$district_id = intval($this->session->userdata('district_id'));
					$condition = " and district_id='$district_id' ";
				}
				
		
		$data["rdata"] = $rdata; 
		$data["districts_list"] = $this->db->query("select * from  districts where 1 $condition "); 
        
        $data["module"] = "report_crt_required_ashram";
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
				$title = $rdata['district_name'] . ' District Working Report ';
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
				
				 $second_title= 'TRIBAL WELFARE DEPARTMENT  ,'.$rdata['district_name'].'  DIST  - ASHRAM CRT REQUIRED ';
				 $this->excel->getActiveSheet()->setCellValue('A2', $second_title);
				//merge cell A2 until Q2
                $this->excel->getActiveSheet()->mergeCells('A2:M2');
				
				$this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                //make the font become bold
                $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
                $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setSize(16);
                $this->excel->getActiveSheet()->getStyle('A2')->getFill()->getStartColor()->setARGB('#333');
				
				
				 $third_title= 'Details of School wise  Teacher Posts in Govt. ASHRAM Schools';
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
			 //	print_a($working ,1);  
				
				$required_counts = @$rdata['required_counts'];
				$class_strengths = $rdata['class_strengths'];
				
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
													'color' => array('rgb'=>'999999'),
													),
													'font' => array(
													'bold' => true,
													'color' =>  array('rgb'=>'FFFFFF'),
													)
											);
											
				$yellow_header = array(
													'borders' => array(
													'bottom' => $default_border,
													'left' => $default_border,
													'top' => $default_border,
													'right' => $default_border,
													),
													'fill' => array(
													'type' => PHPExcel_Style_Fill::FILL_SOLID,
													'color' => array('rgb'=>'#CCCCFF'),
													),
													'font' => array(
													'bold' => true,
													'color' =>  array('rgb'=>'FFFFFF'),
													)
											);							

				//$this->excel->getActiveSheet()->getStyle('A4:Z4')->applyFromArray( $style_header );

				
				$this->excel->getActiveSheet()->mergeCells('E4:O4');
				$this->excel->getActiveSheet()->setCellValue('E4', " Class wise Strength particulars " );
				 							

				
				$this->excel->getActiveSheet()->setCellValue('A5', " S.NO " );
				$this->excel->getActiveSheet()->setCellValue('B5',  'Name of Institution/Location'); 
				$this->excel->getActiveSheet()->setCellValue('C5',  'Agency / Non Agency');	 
				
				//As per G.O.Ms No. 11 & 17		
				$this->excel->getActiveSheet()->mergeCells('D4:Q4');
				$this->excel->getActiveSheet()->setCellValue('D4', " As per G.O.Ms No. 11 & 17 " );				

				$this->excel->getActiveSheet()->setCellValue('D5',  'SGT'); 
				$this->excel->getActiveSheet()->setCellValue('E5',  'S.A(Maths)/ Science'); 
				$this->excel->getActiveSheet()->setCellValue('F5',  'SA (SS)- Social'); 
				$this->excel->getActiveSheet()->setCellValue('G5',  'S.A(English)'); 
				$this->excel->getActiveSheet()->setCellValue('H5',  'LP-I(TP 11)'); 
				$this->excel->getActiveSheet()->setCellValue('I5',  'LP-2(HP 11)'); 
				$this->excel->getActiveSheet()->setCellValue('J5',  'S.A.(Maths)'); 
				$this->excel->getActiveSheet()->setCellValue('K5',  'S.A.(Phy. Sci.)'); 
				$this->excel->getActiveSheet()->setCellValue('L5',  'S.A.(Bio. Sci.)'); 
				$this->excel->getActiveSheet()->setCellValue('M5',  'S.A. (1st Lang.)-Telugu'); 
				$this->excel->getActiveSheet()->setCellValue('N5',  'S.A. (2nd Lang.)-Hindhi'); 
				$this->excel->getActiveSheet()->setCellValue('O5',  'S.A./ (PET)'); 
				$this->excel->getActiveSheet()->setCellValue('P5',  'Craft/ Drawing / Music');  
				$this->excel->getActiveSheet()->setCellValue('Q5',  'Total');   
				
				 $this->excel->getActiveSheet()->getStyle('A5:BZ5')->getFont()->setBold(true); 	
				 $this->excel->getActiveSheet()->getStyle('A4:BZ4')->getFont()->setBold(true); 
			 
                 $this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                //make the font become bold
                $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
				$this->excel->getActiveSheet()->getStyle('A2:S2')->getFont()->setBold(true);
			 
                $i = 6;
				$sno=1;
				 $sno = 1;
				 $grand_total = 0;
				 $posts_total = array();
				 $posts_array = array(2,4,5,6,7,8,10,11,12,13,14,15,16);
				 
				 $rows = $rdata['rows'];
				foreach($rows as  $index=>$crt_required_data){ 
				 
				$school_info = $crt_required_data['school_info'];
				$posts_info = $crt_required_data['posts'];
				 
						
						
						$total_required = 	intval($posts_info[2])+
											intval($posts_info[4])+
											intval($posts_info[5])+
											intval($posts_info[6])+
											intval($posts_info[7])+
											intval($posts_info[8])+
											intval($posts_info[10])+
											intval($posts_info[11])+
											intval($posts_info[12])+
											intval($posts_info[13])+
											intval($posts_info[14])+
											intval($posts_info[15])+
											intval($posts_info[16]);
						foreach($posts_array as $post_id)
						{
							$posts_total[$post_id] = intval($posts_total[$post_id]) + intval($posts_info[$post_id]);
						}		
					$grand_total =  $grand_total + 	$total_required;			
											
				$this->excel->getActiveSheet()->setCellValue('A'.$i, $sno );
				$this->excel->getActiveSheet()->setCellValue('B'.$i,  $school_info->name);	 	
				$this->excel->getActiveSheet()->setCellValue('C'.$i,  $school_info->school_agency);			

				$this->excel->getActiveSheet()->setCellValue('D'.$i, intval($posts_info[2]));//  'SGT'); 
				$this->excel->getActiveSheet()->setCellValue('E'.$i, intval($posts_info[4]));//  'S.A(Maths)/ Science'); 
				$this->excel->getActiveSheet()->setCellValue('F'.$i, intval($posts_info[5]));//  'SA (SS)- Social'); 
				$this->excel->getActiveSheet()->setCellValue('G'.$i, intval($posts_info[6]));//  'S.A(English)'); 
				$this->excel->getActiveSheet()->setCellValue('H'.$i, intval(0));//  'LP-I(TP 11)'  $posts_info[7] ); 
				$this->excel->getActiveSheet()->setCellValue('I'.$i, intval(0));//  'LP-2(HP 11)' $posts_info[8]); 
				$this->excel->getActiveSheet()->setCellValue('J'.$i, intval($posts_info[10]));//  'S.A.(Maths)'); 
				$this->excel->getActiveSheet()->setCellValue('K'.$i, intval($posts_info[11]));//  'S.A.(Phy. Sci.)'); 
				$this->excel->getActiveSheet()->setCellValue('L'.$i, intval($posts_info[12]));//  'S.A.(Bio. Sci.)'); 
				$this->excel->getActiveSheet()->setCellValue('M'.$i, intval($posts_info[13] - $posts_total[7]));//  'S.A. (1st Lang.)-Telugu'); 
				$this->excel->getActiveSheet()->setCellValue('N'.$i, intval($posts_info[14]-$posts_total[8]));//  'S.A. (2nd Lang.)-Hindhi'); 
				$this->excel->getActiveSheet()->setCellValue('O'.$i, intval($posts_info[15]-$posts_total[16]));//  'S.A./ (PET)'); 
				$this->excel->getActiveSheet()->setCellValue('P'.$i, intval(0));//  'Craft/ Drawing / Music');  
				$this->excel->getActiveSheet()->setCellValue('Q'.$i, $total_required);//  ''); 
				$this->excel->getActiveSheet()->getStyle('Q'.$i )->getFont()->setBold(true); 
					 
					$i++;$sno++;
				}
	 
				 $this->excel->getActiveSheet()->setCellValue('C'.$i, "Totals");			

				$this->excel->getActiveSheet()->setCellValue('D'.$i, intval($posts_total[2]));//  'SGT'); 
				$this->excel->getActiveSheet()->setCellValue('E'.$i, intval($posts_total[4]));//  'S.A(Maths)/ Science'); 
				$this->excel->getActiveSheet()->setCellValue('F'.$i, intval($posts_total[5]));//  'SA (SS)- Social'); 
				$this->excel->getActiveSheet()->setCellValue('G'.$i, intval($posts_total[6]));//  'S.A(English)'); 
				$this->excel->getActiveSheet()->setCellValue('H'.$i, intval($posts_total[7]));//  'LP-I(TP 11)'); 
				$this->excel->getActiveSheet()->setCellValue('I'.$i, intval($posts_total[8]));//  'LP-2(HP 11)'); 
				$this->excel->getActiveSheet()->setCellValue('J'.$i, intval($posts_total[10]));//  'S.A.(Maths)'); 
				$this->excel->getActiveSheet()->setCellValue('K'.$i, intval($posts_total[11]));//  'S.A.(Phy. Sci.)'); 
				$this->excel->getActiveSheet()->setCellValue('L'.$i, intval($posts_total[12]));//  'S.A.(Bio. Sci.)'); 
				$this->excel->getActiveSheet()->setCellValue('M'.$i, intval($posts_total[13]));//  'S.A. (1st Lang.)-Telugu'); 
				$this->excel->getActiveSheet()->setCellValue('N'.$i, intval($posts_total[14]));//  'S.A. (2nd Lang.)-Hindhi'); 
				$this->excel->getActiveSheet()->setCellValue('O'.$i, intval($posts_total[15]));//  'S.A./ (PET)'); 
				$this->excel->getActiveSheet()->setCellValue('P'.$i, intval($posts_total[16]));//  'Craft/ Drawing / Music');  
				$this->excel->getActiveSheet()->setCellValue('Q'.$i, $grand_total);//  '');  
                $this->excel->getActiveSheet()->getStyle('A'.$i.':BZ'.$i )->getFont()->setBold(true); 
              
                $filename="Ashram_".$second_title ."_".date('d-M-Y')	.'.xls'; //save our workbook as this file name
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
