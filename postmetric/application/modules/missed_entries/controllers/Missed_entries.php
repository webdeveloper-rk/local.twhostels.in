<?php 
 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
set_time_limit(0);
 date_default_timezone_set('Asia/Kolkata');
class Missed_entries extends MX_Controller {

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
		//print_a($_POST);
		if($this->input->post('school_date')!="")
		 {
			   $date = date('Ymd',strtotime($this->input->post('school_date'))); 
		 }else {
		 
				$date = date('Ymd');
		 }
			
		  $report_date = date('Y-m-d',strtotime($date));
		 
		 $condition_dco =  '';
		 if($this->session->userdata("is_dco") == 1) 
		{
						$condition_dco = " and  district_id = '".intval($this->session->userdata("district_id"))."'   ";
						
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
								
								$condition_dco = " and  sc.school_id in (".implode(",",$schools_list).")   ";
								
								
					}
						
						
		}
		
		
		if($this->session->userdata("role_id")==12)//pos 
							{
								$logged_user_id= $this->session->userdata("user_id");
								$condition_dco =  " and sc.district_id in ( select district_id from po_districts where user_id='$logged_user_id')  ";	
							}
		
		
		
			$balance_sheet_table = $this->common_model->get_stock_entry_table($report_date);
			
		  $sql = "select sc.school_id,sc.school_code,sc.name , sc.district_name ,sc.is_school,t1.total_purchase,t1.total_qty from schools sc left join 
		  ( SELECT `school_id`,sum(purchase_quantity) as total_purchase,sum(session_1_qty+session_2_qty+session_3_qty+session_4_qty) as total_qty
			FROM $balance_sheet_table WHERE `entry_date` = ? group by school_id 
			having(sum(purchase_quantity))>0 or sum(session_1_qty+session_2_qty+session_3_qty+session_4_qty)>0 ) as t1 on sc.school_id = t1.school_id 
			where sc.is_school=1 and t1.total_purchase is NULL and t1.total_qty is NULL and sc.school_code not like '%85000%' $condition_dco order by sc.district_name,sc.school_code 
		  ";
		$rs  = $this->db->query($sql,array($report_date));
		 // echo $this->db->last_query();die;
		
		$report_date_formated = date('d-m-Y',strtotime($report_date));
		
		$data["report_date"] = $report_date_formated;
		$data["rset"] = $rs;
		if($this->input->post('submit')=="Download Report")
				{
					 
					$this->download_report($data);
					die;
				}
			
		
		$data["module"] = "missed_entries"; 
		$data["view_file"] = "missed_schools";
		echo Modules::run("template/admin", $data);
		
	}
	
	
	function monthly()
	{
		
		if($this->input->post('month')!="" && $this->input->post('year')!="")
		 {
			  $year_month = $this->input->post('year')."-".$this->input->post('month');
		 }else {
		 
				$year_month = date('Y-m');
		 }
			
		 //echo $year_month;die;
			
		  $sql = " select sc.school_id ,school_code,name ,sc.district_name  count(*) as missed_days from missed_monthly ms inner join schools sc on sc.school_id=ms.school_id
			WHERE DATE_FORMAT(entry_date,'%Y-%m') = ?  and sc.is_school='1' and sc.school_code !='85000' group by ms.school_id   ";
		$rs  = $this->db->query($sql,array($year_month));
		 
		$report_date_formated = date('M-Y',strtotime($year_month."-01"));
		
		$data["report_date"] = $report_date_formated;
		$data["rset"] = $rs;
		
		
		$data["module"] = "missed_entries"; 
		$data["view_file"] = "monthly_missed";
		echo Modules::run("template/admin", $data);
		
	}
	
	
	
	
	public function download_report($data)
    {
		 
		 $report_date = $data['report_date'];
		 $rset = $data['rset'];
		 
                $this->excel->setActiveSheetIndex(0);
                //name the worksheet
                $this->excel->getActiveSheet()->setTitle('Missed Schools Report  ');
               
                $this->excel->getActiveSheet()->setCellValue('A1', 'Missed Schools Report  for date  of  '.$report_date);
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
				$this->excel->getActiveSheet()->setCellValue('B3', 'School Code');	
				$this->excel->getActiveSheet()->setCellValue('C3', 'School Name');				
				 
                $this->excel->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                //make the font become bold
                $this->excel->getActiveSheet()->getStyle('A3')->getFont()->setBold(true);
				$this->excel->getActiveSheet()->getStyle('A3:S3')->getFont()->setBold(true);
				
                $this->excel->getActiveSheet()->getStyle('A3')->getFont()->setSize(12); 
                
                $i = 4;
				$sno=1;
				$consumption_amount_total = 0;
				 
				 foreach($rset->result() as $school) { 
 			 
				 
					if($school->school_code  == "85000" || $school->school_code  == "")
								continue;
					
					$this->excel->getActiveSheet()->setCellValue('A'.$i  , $school->district_name);
					$this->excel->getActiveSheet()->setCellValue('B'.$i,  $school->school_code  );
					$this->excel->getActiveSheet()->setCellValue('C'.$i, $school->name  );
					 $i++;
				}
	 
				 
                
              
                $filename='missed_schools_report_'.$report_date	.'.xls'; //save our workbook as this file name
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
 