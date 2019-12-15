<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
set_time_limit(0);
 date_default_timezone_set('Asia/Kolkata');
class Report_account_numbers_missing extends MX_Controller {

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
  
			$sql  = "	 select sc.name,sc.school_code,sc.district_name,tw.* from tw_vendors tw  inner join schools sc on sc.school_id = tw.school_id";
			$rdata['rset'] = $rs =  $this->db->query($sql);	
			
			 $wrong_accounts = array();
			 foreach($rs->result() as $row)
			 {
				 if (!is_numeric( trim($row->vendor_account_number))   || $row->vendor_account_number==0) {
					 
					 $wrong_accounts[$row->vendor_annapurna_id] = $row;
				 }
			 }
			 $rdata['rows']  =  $wrong_accounts;
			
			
			$submit = $this->input->post("submit");
			if($submit=="download")
			{
				$this->download_missing_accounts_report($rdata);
			}
			else  {
				$data['display_result'] = true ;
				$data['rdata'] = $rdata;
			} 
			 
			 
		 
        
        
        $data["module"] = "report_account_numbers_missing";
        $data["view_file"] = "accounts_report";
        echo Modules::run("template/admin", $data);
    }
		/********************************************************************
	
	
	********************************************************************/
	public function download_missing_accounts_report($rdata)
    {
				 
				$this->excel->setActiveSheetIndex(0);
                //name the worksheet
				$title = 'Wrong Accounts Report ';
				$headtitle = 'Wrong Accounts Report' ;
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
				$this->excel->getActiveSheet()->setCellValue('D2', " Vendor name " ); 
				$this->excel->getActiveSheet()->setCellValue('E2', " Vendor BANK " ); 
				$this->excel->getActiveSheet()->setCellValue('F2',  'Vendor IFSC');	
				$this->excel->getActiveSheet()->setCellValue('G2',  'Account Number');	
				$this->excel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);				
				$this->excel->getActiveSheet()->getStyle('B2')->getFont()->setBold(true);				
				 
				 
                 $this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                //make the font become bold
                $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
				$this->excel->getActiveSheet()->getStyle('A2:S2')->getFont()->setBold(true);
			 
                $i = 3;
				$sno=1;
				$rset = $rdata['rows'];
				 if(count($rset)==0){  } 
				 $sno = 1;
				  
				 
				
				 foreach($rset  as $key => $item) { 
					$this->excel->getActiveSheet()->setCellValue('A'.$i, $item->name);
					$this->excel->getActiveSheet()->setCellValue('B'.$i, $item->school_code);
					$this->excel->getActiveSheet()->setCellValue('C'.$i, $item->district_name);
					$this->excel->getActiveSheet()->setCellValue('D'.$i, $item->vendor_name);
					$this->excel->getActiveSheet()->setCellValue('E'.$i, $item->vendor_bank);
					$this->excel->getActiveSheet()->setCellValue('F'.$i, $item->vendor_bank_ifsc);
					$this->excel->getActiveSheet()->setCellValue('G'.$i, $item->vendor_account_number); 
					 
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
