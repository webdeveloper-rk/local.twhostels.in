<?php 
 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
set_time_limit(0);
 date_default_timezone_set('Asia/Kolkata');
class Report_missing_vendors extends MX_Controller {

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
	}
	function index()
	{
 
		 $data['display_result'] = false ;
		 $data['months'] = $months = array("01"=>"January","02"=>"February","03"=>"March","04"=>"April","05"=>"May",
									"06"=>"June","07"=>"July","08"=>"August","09"=>"September","10"=>"October","11"=>"November","12"=>"December");	
									
		$this->form_validation->set_rules('month', 'Month ', 'required|numeric|greater_than[0]|less_than_equal_to[12]');  
		$this->form_validation->set_rules('year', 'Year ', 'required|numeric|greater_than_equal_to[2018]|less_than_equal_to['.date('Y') .']');  
		 
		if($this->form_validation->run() == true    )
		{
			$data['month']=    $month = intval($this->input->post('month'));
			$data['year']=    $year = intval($this->input->post('year')); 
			$data['submit']=    $submit = $this->input->post('submit'); 
			 $data['display_result'] = true ;
			$rdata = array();
			if($month<10)
				$month = "0".$month;
			
			$condition = '';
			$report_for = '';
			if($this->session->userdata("is_dco")==1)
			{
					$district_id = $this->session->userdata("district_id");
					$condition = " and district_id='$district_id' ";
					 
					
			}
 
				$report_for = $this->config->item('society_name');
			 
			 
			$sql  = "	 select it.item_name,sc.name,sc.school_code,sc.district_name , bs.school_id,bs.item_id,
												purchase_quantity,
												date_format(entry_date,'%d-%M-%Y') as entry_date_dp,
												entry_date 
												from balance_sheet bs inner join schools sc on sc.school_id=bs.item_id
												inner join items it on it.item_id = bs.item_id 
												where 
												month(entry_date)=? and YEAR(entry_date)=? and vendor_annapurna_id=0 and purchase_quantity>0   $condition  
												 order by bs.school_id";
			$rdata['rset'] = $this->db->query($sql,array($month,$year));	
			
			 
			$rdata['month_name'] = $months[$month] ."-".$year;
			$rdata['report_for'] = $report_for;
			
			
			
			
			if($submit=="download")
			{
				$this->download_missing_vendors_report($rdata);
			}
			else  {
				$data['display_result'] = true ;
				$data['rdata'] = $rdata;
			} 
			 
			 
		 }
        
        
        $data["module"] = "report_missing_vendors";
        $data["view_file"] = "itemwise_report";
        echo Modules::run("template/admin", $data);
    }
		/********************************************************************
	
	
	********************************************************************/
	public function download_missing_vendors_report($rdata)
    {
				 
				$this->excel->setActiveSheetIndex(0);
                //name the worksheet
				$title = 'Missing Vendors Report ';
				$headtitle = 'Missing Vendors Report '. "-".$rdata['month_name']."-".$rdata['report_for'];
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

				
				$this->excel->getActiveSheet()->setCellValue('A2', " Name " );
				$this->excel->getActiveSheet()->setCellValue('B2', " DDO CODE " );
				$this->excel->getActiveSheet()->setCellValue('C2', " District name " );
				$this->excel->getActiveSheet()->setCellValue('D2', " Item name " ); 
				$this->excel->getActiveSheet()->setCellValue('E2',  'Purchase Quantity');	
				$this->excel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);				
				$this->excel->getActiveSheet()->getStyle('B2')->getFont()->setBold(true);				
				 
				 
				 
				
 
			 
					 
			 
                 $this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                //make the font become bold
                $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
				$this->excel->getActiveSheet()->getStyle('A2:S2')->getFont()->setBold(true);
			 
                $i = 3;
				$sno=1;
				$purchased_total = 0;
				foreach($rdata['rset']->result()  as  $purchase_data){ 
				 
		 	 
					
					$this->excel->getActiveSheet()->setCellValue('A'.$i, $purchase_data->name);
					$this->excel->getActiveSheet()->setCellValue('B'.$i, $purchase_data->school_code);
					$this->excel->getActiveSheet()->setCellValue('C'.$i, $purchase_data->district_name);
					$this->excel->getActiveSheet()->setCellValue('D'.$i, $purchase_data->entry_date_dp);
					$this->excel->getActiveSheet()->setCellValue('E'.$i, $purchase_data->item_name);
					$this->excel->getActiveSheet()->setCellValue('F'.$i, $purchase_data->purchase_quantity);
					 
					  
					 
					$i++;$sno++;
				}
	 
				 
                
              
                $filename=$headtitle .date('d-M-Y')	.'.xls'; //save our workbook as this file name
             
			   header('Content-Type: application/vnd.ms-excel',true,200); //mime type
                header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
                header('Cache-Control: max-age=0'); //no cache
  
                //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
                //if you want to save it as .XLSX Excel 2007 format
                $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');  
                //force user to download the Excel file without writing it to server's HD
				@ob_end_clean(); 
                $objWriter->save('php://output');
                 
    }
	

}
