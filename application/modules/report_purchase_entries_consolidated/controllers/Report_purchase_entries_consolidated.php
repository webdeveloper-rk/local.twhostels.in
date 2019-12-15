<?php 
 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
set_time_limit(0);
 date_default_timezone_set('Asia/Kolkata');
class report_purchase_entries_consolidated extends MX_Controller {

    function __construct() {
        parent::__construct();
		if($this->uri->segment(2) !="login") { 
					 Modules::run("security/is_admin");		 
					
		}
		if ($this->session->userdata("is_loggedin") != TRUE || $this->session->userdata("user_id") == "" ) {
							redirect("admin/login");
							die;
					}
					 			
					 
		$this->load->helper('url');  
		$this->load->config("config.php");  
		$this->load->library("ci_jwt");  
		$this->load->library("excel");  
			$this->load->model("common/common_model");  
	}
	function index()
	{
		   
		redirect(current_url()."/itewise_purchase_entries");
	
	}
    
		/*************************************************************************
	
	
	
	*****************************************************************************/
	function itewise_purchase_entries() {
		 
		 //print_a($_POST);
		 if($this->session->userdata("user_role") != "school")
		 {
			 $school_id = $this->input->post("school_id");
			
		 }else{
			$school_id = $this->session->userdata("school_id");
		 }
		   
		 $data['display_result'] = false ;
		 $data['months'] = $months = array("01"=>"January","02"=>"February","03"=>"March","04"=>"April","05"=>"May",
									"06"=>"June","07"=>"July","08"=>"August","09"=>"September","10"=>"October","11"=>"November","12"=>"December");	
		

		$cyear = date('Y');
		$this->form_validation->set_rules('month', 'Month ', 'required|numeric|greater_than[0]|less_than_equal_to[12]');              
		$this->form_validation->set_rules('year', 'Year ', 'required|numeric|greater_than_equal_to[2017]|less_than_equal_to['.$cyear .']');  
		 
		if($this->form_validation->run() == true )
		{
			 
			$data['school_id']=    $school_id ;
			$data['item_id']=    $item_id = intval($this->input->post('item_id')); 
			 $month = intval($this->input->post('month'));
			 $year = intval($this->input->post('year'));
			 if($month<10)
					$month = "0".$month;
			 
			$data['month']=    $month;
			$data['year']=    intval($year = $this->input->post('year')); 
			$data['submit']=    $submit = $this->input->post('submit'); 
			$data['type']=    $type = 'school'; 
			
			$start_date= $year."-".$month."-01";
			
			$rdata = array();
			
			$condition = '';
			$report_for = '';
			 
			$condition = " ";
			$school_rs = $this->db->query("select  name,school_code from schools where   school_id=? ",array($school_id))->row();
			$report_for =  $school_rs->school_code."-".$school_rs->name;;
			 
			$stock_entry_table = "balance_sheet";//$this->common_model->get_stock_entry_table($year."-".$month."-01");
			 
			  $sql  = "SELECT v.vendor_name,v.vendor_annapurna_id,v.vendor_type,vendor_bank,vendor_bank_branch,vendor_type,vendor_bank_ifsc,vendor_account_number,                                      it.item_name,bs.*,date_format(entry_date,'%d-%M-%Y') as entry_date_dp FROM $stock_entry_table bs left join tw_vendors v 
			  on bs.vendor_annapurna_id = v.vendor_annapurna_id inner join items it on it.item_id=bs.item_id 
			  where bs.school_id=? and purchase_quantity>0 and bs.entry_date between ? and last_day(?) 
			  order by  entry_date asc";
			 
			$rdata['rset'] = $this->db->query($sql,array($school_id,$start_date,$start_date));	
			
			 // echo $this->db->last_query();die;
			 
			$rdata['month_name'] = $months[$month] ."-".$year;
			$rdata['report_for'] = $report_for;
			
			
			
			
			if($submit=="download")
			{
				$this->download_purchase_report($rdata);
			}
			else  {
				$data['display_result'] = true ;
				$data['rdata'] = $rdata;
			} 
			 
			 
		 }
         
        $data["module"] = "report_purchase_entries_consolidated";
        $data["view_file"] = "school_itemwise_report";
        echo Modules::run("template/admin", $data);
    }
	
	/********************************************************************
	
	
	********************************************************************/
	private function download_purchase_report($rdata)
    {
				//print_a($rdata,1);
				$this->excel->setActiveSheetIndex(0);
                //name the worksheet
				$title = 'Purchase Report ';
				$headtitle = 'Purchase Report  -'.$rdata['month_name']."-".$rdata['report_for'];
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

				
				$this->excel->getActiveSheet()->setCellValue('A2', " Date " );
				$this->excel->getActiveSheet()->setCellValue('B2',  'Item name');	
				$this->excel->getActiveSheet()->setCellValue('C2',  'Vendor type');	
				$this->excel->getActiveSheet()->setCellValue('D2',  'Vendor name');	
				$this->excel->getActiveSheet()->setCellValue('E2',  'Vendor Bank name');	
				$this->excel->getActiveSheet()->setCellValue('F2',  'Vendor Bank IFSC Code');	
				$this->excel->getActiveSheet()->setCellValue('G2',  'Vendor Bank Account Number');	
				
				$this->excel->getActiveSheet()->setCellValue('H2',  'Purchase Quantity');	
				$this->excel->getActiveSheet()->setCellValue('I2',  'Purchase Price');	
				$this->excel->getActiveSheet()->setCellValue('J2',  'Total Price');	
				$this->excel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);				
				$this->excel->getActiveSheet()->getStyle('B2')->getFont()->setBold(true);				
				 
				 
				 
				
 
			 
					 
			 
                 $this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                //make the font become bold
                $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
				$this->excel->getActiveSheet()->getStyle('A2:S2')->getFont()->setBold(true);
			 
                $i = 3;
				$sno=1;
				 $total_purchased = 0;
				 $total_purchased_price = 0;
				 $purchased_total = 0;
				foreach($rdata['rset']->result()  as  $purchase_data){ 
				 
				   $purchased_total = $purchase_data->purchase_quantity * $purchase_data->purchase_price;
				  $total_purchased_price = $total_purchased_price + $purchased_total;
				  
					
					$this->excel->getActiveSheet()->setCellValue('A'.$i, $purchase_data->entry_date_dp);
					$this->excel->getActiveSheet()->setCellValue('B'.$i, $purchase_data->item_name);
					$this->excel->getActiveSheet()->setCellValue('C'.$i, $purchase_data->vendor_type);
					$this->excel->getActiveSheet()->setCellValue('D'.$i, $purchase_data->vendor_name);
					$this->excel->getActiveSheet()->setCellValue('E'.$i, $purchase_data->vendor_bank);
					$this->excel->getActiveSheet()->setCellValue('F'.$i, $purchase_data->vendor_bank_ifsc);
					$this->excel->getActiveSheet()->setCellValue('G'.$i, $purchase_data->vendor_account_number);
					$this->excel->getActiveSheet()->setCellValue('H'.$i, $purchase_data->purchase_quantity);
					$this->excel->getActiveSheet()->setCellValue('I'.$i, $purchase_data->purchase_price); 
					$this->excel->getActiveSheet()->setCellValue('J'.$i, $purchased_total); 
					$i++;$sno++;
				}
	 
				 $this->excel->getActiveSheet()->setCellValue('I'.$i, 'Total Purchased Amount');
					$this->excel->getActiveSheet()->setCellValue('J'.$i, $total_purchased_price);
					$this->excel->getActiveSheet()->getStyle('A'.$i.':S'.$i)->getFont()->setBold(true);
                
              
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
	/************************************************************************************/
	
	
	
	
	
}
