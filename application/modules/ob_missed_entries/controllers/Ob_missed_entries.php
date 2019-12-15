<?php 
 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
set_time_limit(0);
 date_default_timezone_set('Asia/Kolkata');
class Ob_missed_entries extends MX_Controller {

    function __construct() {
        parent::__construct();
		if($this->uri->segment(2) !="login") { 
					 Modules::run("security/is_admin");		 
					if ($this->session->userdata("is_loggedin") != TRUE || $this->session->userdata("user_id") == "" ) {
							redirect("admin/login");
							die;
					}
					 	//print_a($this->session->all_userdata(),1);	
						
						$roles = array('subadmin','secretary');
					if(!in_array($this->session->userdata("user_role"),$roles))
					{
						redirect("admin/login");
							die;
					}
		}
		$this->load->helper('url');
		$this->load->model('common/common_model');
		$this->load->library('excel');
	 
	}

    function index() {
			$this->listschools();
		}
		
	function listschools()
	{
		 $condition_dco =  '';
		/// print_a($this->session->all_userdata());
		 if($this->session->userdata("is_dco") == 1) 
		{
						$condition_dco = " and   district_id = '".intval($this->session->userdata("district_id"))."'   ";
						
						$uid  = $this->session->userdata("user_id");
						$is_atdo = $this->db->query("select * from users where uid=?",array($uid))->row()->is_atdo;
					if($is_atdo ==1)
					{
						$schools_list = array();
								$data_selected_set  =  $this->db->query("select *  from assigned_schools where user_id=?",array($uid));
								foreach($data_selected_set->result() as $asrow)
								{
									$schools_list[] = $asrow->school_id;
								}
								if( count($schools_list)==0)
									$schools_list[] = 0;
								
								$condition_dco = " and   school_id in (".implode(",",$schools_list).")   ";
								
								
					}
						
						
		}
		
			
		  $sql = "
		  
		 select * from (

select sc.school_id,name,school_code as ddo_code ,district_name,IFNULL(filled_count,   0  )  as filled_count,contact_number,district_id  from schools sc left join 
(SELECT school_id,count(*) as filled_count FROM `school_opening_balance` where qty>0 group by school_id having filled_count>0) as ft on ft.school_id = sc.school_id   order by filled_count asc) as t1 where 1 $condition_dco  order by filled_count asc 
		  ";
		$rs  = $this->db->query($sql );
	// echo $this->db->last_query();die;
		
		 
		
		 
		$data["rset"] = $rs;
		if($this->input->post('submit')=="Download Report")
				{
					 
					$this->download_report($data);
					die;
				}
			
		
		$data["module"] = "ob_missed_entries"; 
		$data["view_file"] = "missed_schools";
		echo Modules::run("template/admin", $data);
		
	}
	
	
	 
	
	
	
	
	public function download_report($data)
    {
		 
		 
		 $rset = $data['rset'];
		 
                $this->excel->setActiveSheetIndex(0);
                //name the worksheet
                $this->excel->getActiveSheet()->setTitle('OB Missed Hostels Report  ');
               
                $this->excel->getActiveSheet()->setCellValue('A1', 'OB Missed Hostels Report');
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

				$this->excel->getActiveSheet()->getStyle('A3:Z3')->applyFromArray( $style_header );
 
				////////////////////////////////////////////////////////////////////////////////////////////////
				$this->excel->getActiveSheet()->setCellValue('A3', 'District Name'); 
				$this->excel->getActiveSheet()->setCellValue('B3', 'DDO Code');	
				$this->excel->getActiveSheet()->setCellValue('C3', ' Name');				
				$this->excel->getActiveSheet()->setCellValue('D3', 'No of items filled');				
				$this->excel->getActiveSheet()->setCellValue('E3', 'Contact number');				
				 
                $this->excel->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                //make the font become bold
                $this->excel->getActiveSheet()->getStyle('A3')->getFont()->setBold(true);
				$this->excel->getActiveSheet()->getStyle('A3:S3')->getFont()->setBold(true);
				
                $this->excel->getActiveSheet()->getStyle('A3')->getFont()->setSize(12); 
                
                
                $i = 4;
				$sno=1;
				$consumption_amount_total = 0;
				
				$only_zeros = intval($this->input->post("only_zeros"));
				 
				 foreach($rset->result() as $school) { 
 			 
				  if($only_zeros==1 && $school->filled_count !=0)
					  continue;
				 
					if($school->ddo_code  == "85000" || $school->ddo_code  == "")
								continue;
					
					$this->excel->getActiveSheet()->setCellValue('A'.$i  , $school->district_name);
					$this->excel->getActiveSheet()->setCellValue('B'.$i,  $school->ddo_code  );
					$this->excel->getActiveSheet()->setCellValue('C'.$i, $school->name  );
					$this->excel->getActiveSheet()->setCellValue('D'.$i, $school->filled_count);
					$this->excel->getActiveSheet()->setCellValue('E'.$i, $school->contact_number);
					 $i++;
				}
	 
				 
                //die;
              
                $filename='ob_missed_report.xls'; //save our workbook as this file name
                header('Content-Type: application/vnd.ms-excel'); //mime type
                header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
                header('Cache-Control: max-age=0'); //no cache
				 
                //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
                //if you want to save it as .XLSX Excel 2007 format
                $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');  
                //force user to download the Excel file without writing it to server's HD
                $objWriter->save('php://output'); 
    }
	 
	
}
 