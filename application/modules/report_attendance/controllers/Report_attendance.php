<?php 
 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
set_time_limit(0);
 date_default_timezone_set('Asia/Kolkata');
class Report_attendance extends MX_Controller {

    function __construct() {
        parent::__construct();
		if($this->uri->segment(2) !="login") { 
					 Modules::run("security/is_admin");		 
					
		}
		//print_a($this->session->all_userdata(),1);
		if ($this->session->userdata("is_loggedin") != TRUE || $this->session->userdata("user_id") == "" ) {
							redirect("admin/login");
							die;
					}
					 			
					if($this->session->userdata("user_role") != "subadmin" &&  $this->session->userdata("school_code") != "dsapswreis")
					{
						redirect("admin/login");
							die;
					}
		$this->load->helper('url');  
		$this->load->config("config.php");  
		$this->load->library("ci_jwt");  
		$this->load->library("excel"); 
		$this->load->model('common/common_model');
		
	}
	function index()
	{
		$type='';
		$tval = '';
		 $data['display_result'] = false ;
		 $data['months'] = $months = array("0"=>"0","01"=>"January","02"=>"February","03"=>"March","04"=>"April","05"=>"May",
									"06"=>"June","07"=>"July","08"=>"August","09"=>"September","10"=>"October","11"=>"November","12"=>"December");	
									
		 
		$this->form_validation->set_rules('month', 'Month ', 'required|numeric|greater_than[0]|less_than_equal_to[12]');  
		$this->form_validation->set_rules('year', 'Year ', 'required|numeric|greater_than_equal_to[2020]|less_than_equal_to['.date('Y') .']');  
		$this->form_validation->set_rules('district_id', 'District ', 'required|numeric|greater_than[0]');  
		$this->form_validation->set_rules('school_id', 'School ', 'required|numeric|greater_than[0]');  
		 
		if($this->form_validation->run() == true    )
		{
			$data['district_id']=   $district_id = intval($this->input->post('district_id'));
			$data['school_id']=    $school_id = intval($this->input->post('school_id'));
			 
			 
			$data['month']=    $month = intval($this->input->post('month'));
			$data['year']=    $year = intval($this->input->post('year')); 
			$data['submit']=    $submit = $this->input->post('submit'); 
			$data['type']=    $type = $this->input->post('type'); 
			  
			 
			$rdata['month_name'] = $months[$month] ."-".$year;
			$rdata['report_for'] = $report_for;
			
			$edate = $year."-".$month."-01";
			 
			 $attendence_rs = $this->db->query("SELECT einfo.employee_type,einfo.fullname,einfo.emp_id ,
			 day(last_day(?)) as total_days,
		 IFNULL(present_days,0) present_days,
		 IFNULL(leaves,0) leaves FROM `employee_info` einfo left JOIN
		 (select * from employee_attendance WHERE school_id=? and month= ? and year=?) att on att.emp_id = einfo.emp_id 
		 WHERE einfo.school_id=?  and employee_type='crt'" ,array($edate,$school_id,$month,$year,$school_id));
		 
		 $data["attendence_rs"] =  $attendence_rs;
		 $school_row =  $this->db->query("select * from schools where school_id=?",array($school_id))->row();
		 $data["school_name"] =  $school_row->name." - ".$school_row->district_name;
		 
			$data_html_text = $this->load->view("ajax_response",$data,TRUE); 
			send_json_result([
                'success' =>  TRUE ,
                'message' => '<div class="alert alert-success"> Fetched Successfully.</div>',
				'html_table'=>$data_html_text 
            ] );  
			 
			 
		 }
         
        $data["schools_list"] = $this->db->query("select * from  schools where  0 ");
        $data["districts_list"] = $this->db->query("select * from  districts  ");
        $data["type"] = $type;
        $data["tval"] = $tval;
        $data["rates"] = $rates;
        $data["item_name"] = $item_name;
        $data["school_code"] = "";
        
        $data["module"] = "report_attendance";
        $data["view_file"] = "attendance_report";
        echo Modules::run("template/admin", $data);
    }
	
	function getschoolslist($district_id='')
	{
			 $schools_list = $this->db->query("select * from  schools where district_id=?  and school_code not like '%85000%' order by name asc ",array($district_id));
	
			$list = array();
			foreach($schools_list->result() as $row)
			{
				$list[] = array("id"=>$row->school_id,"name"=>$row->name." - ".$row->district_name);
			}
			send_json_result($list );  
	}
		/********************************************************************
	
	
	********************************************************************/
	public function download_purchase_report($rdata,$rates,$item_name)
    {
				 
				$this->excel->setActiveSheetIndex(0);
                //name the worksheet
				$title = '  Purchase Report - '.$item_name;
				$headtitle = '  Purchase Report  -'.$rdata['month_name']."-".$rdata['report_for']." - ".$item_name;
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

				
				$this->excel->getActiveSheet()->setCellValue('A2', " School code " );
				$this->excel->getActiveSheet()->setCellValue('B2', " School name " );
				$this->excel->getActiveSheet()->setCellValue('C2',  'District name');	
				$this->excel->getActiveSheet()->setCellValue('D2',  'Item name');	
				$this->excel->getActiveSheet()->setCellValue('E2',  'Purchase Quantity');	 
				$this->excel->getActiveSheet()->setCellValue('F2',  'Purchase rate');	 
				$this->excel->getActiveSheet()->setCellValue('G2',  'Total Amount');	 
				$this->excel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);				
				$this->excel->getActiveSheet()->getStyle('B2')->getFont()->setBold(true);				
				$this->excel->getActiveSheet()->getStyle('C2')->getFont()->setBold(true);				
				$this->excel->getActiveSheet()->getStyle('D2')->getFont()->setBold(true); 			
				$this->excel->getActiveSheet()->getStyle('E2')->getFont()->setBold(true); 			
				$this->excel->getActiveSheet()->getStyle('F2')->getFont()->setBold(true); 			
				$this->excel->getActiveSheet()->getStyle('G2')->getFont()->setBold(true); 			
				 
				 
				 
				
 
			 
					 
			 
                 $this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                //make the font become bold
                $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
				$this->excel->getActiveSheet()->getStyle('A2:S2')->getFont()->setBold(true);
			 
                $i = 3;
				$sno=1;
				$purchased_total = 0;
				 $total_purchased_qty = 0;
				 $total_purchased_amount = 0;
				 
				foreach($rdata['rset']->result()  as  $purchase_data){ 
				 
					$item_rate = $rates[$purchase_data->item_id]; 
					$total_purchased_qty  =  $total_purchased_qty  + $purchase_data->purchase_quantity; 
					 
					$total_purchased_amount =  $total_purchased_amount +  $purchase_data->purchase_total;
				 
					
					$this->excel->getActiveSheet()->setCellValue('A'.$i, $purchase_data->school_code);
					$this->excel->getActiveSheet()->setCellValue('B'.$i, $purchase_data->school_name);
					$this->excel->getActiveSheet()->setCellValue('C'.$i, $purchase_data->district_name);
					$this->excel->getActiveSheet()->setCellValue('D'.$i, $purchase_data->item_name); ;
					$this->excel->getActiveSheet()->setCellValue('E'.$i, $purchase_data->purchase_quantity);
					$this->excel->getActiveSheet()->setCellValue('F'.$i, round($purchase_data->purchase_price,2));
					$this->excel->getActiveSheet()->setCellValue('G'.$i, round($purchase_data->purchase_total,2));
					 
					$purchased_total = $purchased_total + $purchase_data->purchase_quantity; 
					$i++;$sno++;
				}
					 
					$this->excel->getActiveSheet()->setCellValue('D'.$i, 'Total Quantity');
					$this->excel->getActiveSheet()->setCellValue('E'.$i, $total_purchased_qty);
					$this->excel->getActiveSheet()->setCellValue('F'.$i, 'Total Amount');
					$this->excel->getActiveSheet()->setCellValue('G'.$i, $total_purchased_amount);
				 
					$this->excel->getActiveSheet()->getStyle('A'.$i.':S'.$i)->getFont()->setBold(true);
                
              
                $filename=$item_name." - " .$headtitle .date('d-M-Y')	.'.xls'; //save our workbook as this file name
             
			   header('Content-Type: application/vnd.ms-excel',true,200); //mime type
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
