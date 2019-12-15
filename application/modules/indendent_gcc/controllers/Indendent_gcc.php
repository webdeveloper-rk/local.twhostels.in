<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Indendent_gcc extends MX_Controller {

    function __construct() {
        parent::__construct(); 
         $this->load->config("config.php");
         $this->load->model("admin/school_model");
		
		
		$this->load->library("ci_jwt");
		$this->load->model("common/common_model");
		 $this->load->library('excel'); 
		  //print_a($this->session->all_userdata(),1);
		 $allowed_roles = array( "gcc" );
		 if(!in_array($this->session->userdata("user_role"),$allowed_roles))
		{
				redirect("admin/general/logout"); 
		}
    }

   

    function index() {

	redirect("indendent_gcc/alreadygenerated"); 

    }
 
	 
	
	
	
function download_indent_report($data){

	   
		  $multiples = intval($this->input->post("multiples"));
if($multiples<=0){
	$multiples = 1;
}
                $this->excel->setActiveSheetIndex(0);
                //name the worksheet
				$title = "INDENT Report for ".ucfirst($data['month_name'])."-".$data['year']. " [ ".$data['school_info']->school_code."-".$data['school_info']->name." ] ";
                $this->excel->getActiveSheet()->setTitle("Indent Report");
               
                $this->excel->getActiveSheet()->setCellValue('A1',  $title);
				//merge cell A2 until Q2
                $this->excel->getActiveSheet()->mergeCells('A1:H1');
				
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

				 $this->excel->getActiveSheet()->getStyle('A1:Z1')->applyFromArray( $style_header );
				$this->excel->getActiveSheet()->getStyle('A1:Z1')->getFont()->setBold(true);				
				 
				
				
				
				////////////////////////////////////////////////////////////////////////////////////////////////
				$this->excel->getActiveSheet()->setCellValue('A2', 'SNO');
				$this->excel->getActiveSheet()->setCellValue('B2', 'Item Name');		
				 
				$this->excel->getActiveSheet()->setCellValue('C2', 'No Of Days');
				$this->excel->getActiveSheet()->setCellValue('D2', 'Strength');
				$this->excel->getActiveSheet()->setCellValue('E2', 'Opening Balance Qty');
				
				$this->excel->getActiveSheet()->setCellValue('F2', 'Monthly Required Qty');
				$this->excel->getActiveSheet()->setCellValue('G2', 'Required Qty per selected month'); 
				$this->excel->getActiveSheet()->setCellValue('H2', $multiples.' Months Required Qty'); 
			 
					 
			 
                 $this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                //make the font become bold
                $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
				$this->excel->getActiveSheet()->getStyle('A2:S2')->getFont()->setBold(true);
				
                $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setSize(12);
                //$this->excel->getActiveSheet()->getStyle('A3')->getFill()->getStartColor()->setARGB('#333');
                
                $i = 3;
				$sno=1;
				$consumption_amount_total = 0;
				$schools_data = $data['schools_data'];
				
				//print_a($schools_data );die;
				 foreach($schools_data->result()  as $item) {
				 
		 	 
					
					$this->excel->getActiveSheet()->setCellValue('A'.$i, $sno);
					$this->excel->getActiveSheet()->setCellValue('B'.$i,  $item->item_name); 
					$this->excel->getActiveSheet()->setCellValue('C'.$i,   $item->number_of_days);
					
					
					$this->excel->getActiveSheet()->setCellValue('D'.$i, $item->school_strength);
					$this->excel->getActiveSheet()->setCellValue('E'.$i, $item->opening_quantity);
					$this->excel->getActiveSheet()->setCellValue('F'.$i, $item->monthly_required_qty);
					
					
					$this->excel->getActiveSheet()->setCellValue('G'.$i, $item->balance_qty); 
					$this->excel->getActiveSheet()->setCellValue('H'.$i, ($multiples *  $item->monthly_required_qty)); 
					 
					
					  $this->excel->getActiveSheet()->getStyle('S'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
					 
					$i++;$sno++;
				}
	 
				 
					
				$filename =  $data['school_info']->school_code."-".$data['school_info']->name."-". ucfirst($data['month_name'])."-".$data['year'] ."-IndentReport.xls";
                
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


 function viewindent($encoded)
{
	$endata = $this->ci_jwt->jwt_web_decode($encoded);
	 // print_a($endata,1);
	 $indent_info_id = $endata->indent_info_id;
	 $indent_info_data = $this->db->query("select * from indent_info where indent_info_id=?",array($indent_info_id ))->row();
	// print_a($indent_info_data,1);
	 $indent_info_id = $endata->indent_info_id;
	 $indent_date = $endata->indent_date;
	 $school_id = $endata->school_id;
	 $dp_date = $endata->dp_date;
	 
	 
	 $schools_data =   $this->db->query("select *  from indent where school_id=? and indent_date=? and indent_info_id=? ",array($school_id,$indent_date,$indent_info_id));
				
				$data['schools_data'] = $schools_data; 
				$data['dp_date'] = $dp_date; 
				$data['school_id'] =  $school_id; 
				$data['indent_date'] = $indent_date; 
				$data['encoded'] = $encoded; 
				
				$data['school_info'] = $this->db->query("select * from schools where school_id=?",array($school_id))->row();
				
				$data['multiples'] = $indent_info_data->multiples; 
				$data['schools_list'] = $schools_list; 
		  
		$data["indent_info_gcc_submitted"] = $indent_info_data->gcc_submitted_date; 
		$data["module"] = "indendent_gcc"; 
        $data["view_file"] = "view_indendent"; 
        echo Modules::run("template/admin", $data);
	
}


function alreadygenerated()
	{
		$schools_names  = array();
		$rs = $this->db->query("select * from schools ");
		foreach($rs->result() as $row)
		{
			$schools_names[$row->school_id] = $row->school_code . " - " .$row->name;
		}
		$data['schools_names'] =$schools_names;
	 
		$data['generated_list'] = $this->db->query(" select *,date_format(created_date,'%d-%M-%Y %H:%i:%s') as created_date,date_format(gcc_submitted_date,'%d-%M-%Y %H:%i:%s') as gcc_submitted_date from indent_info where district_id=? and submited_to_gcc='1' order by indent_info_id desc ",array($this->session->userdata("district_id")));
		//echo $this->db->last_query();die;
		
		 
		  
		$data["module"] = "indendent_gcc"; 
        $data["view_file"] = "generated_indents"; 
        echo Modules::run("template/admin", $data);
	}


 
function sendittogcc($encoded)
	{
		$endata = $this->ci_jwt->jwt_web_decode($encoded);
		$indent_info_id = $endata->indent_info_id;
		$this->db->query("update indent_info set gcc_submitted_date=now(),submited_to_gcc='1' where indent_info_id=?",array($indent_info_id));
		redirect("indendent/alreadygenerated");
		//print_a($endata,1); 
		
	}
function indent_schools_list($encoded)
	{
		$endata = $this->ci_jwt->jwt_web_decode($encoded);
		$indent_info_id = $endata->indent_info_id;
		//print_a($endata,1);
		
		$schools_names  = array();
		$rs = $this->db->query("select * from schools where district_id=?",array($this->session->userdata("district_id")));
		
		//echo $this->db->last_query();
		
		foreach($rs->result() as $row)
		{
			$schools_names[$row->school_id] = $row->school_code . " - " .$row->name;
		}
		$data['schools_names'] =$schools_names;
		//print_a($data['schools_names']);
	 
		$data['generated_list'] = $this->db->query("select indent_info_id,indent_date,school_id,date_format(indent_date,'%d-%M-%Y') as dp_date from indent where district_id=?  and indent_info_id=? group by school_id, indent_date ",array($this->session->userdata("district_id"),$indent_info_id));
		//echo $this->db->last_query();
		
		 
		  
		$data["module"] = "indendent_gcc"; 
        $data["view_file"] = "generated_reports"; 
        echo Modules::run("template/admin", $data);
	}
	/*******************************************************************************************************************
	
	
	*******************************************************************************************************************/
	function downloadindent($encoded)
		{
			$endata = $this->ci_jwt->jwt_web_decode($encoded);
			 // print_a($endata,1);
			 $indent_info_id = $endata->indent_info_id;
			 $indent_info_data = $this->db->query("select * from indent_info where indent_info_id=?",array($indent_info_id ))->row();
			// print_a($indent_info_data,1);
			 $indent_info_id = $endata->indent_info_id;
			 $indent_date = $endata->indent_date;
			 $school_id = $endata->school_id;
			 $dp_date = $endata->dp_date;
			 
			 
			 $schools_data =   $this->db->query("select *  from indent where school_id=? and indent_date=? and indent_info_id=? ",array($school_id,$indent_date,$indent_info_id));
						
						$data['schools_data'] = $schools_data; 
						$data['dp_date'] = $dp_date; 
						$data['school_id'] =  $school_id; 
						$data['indent_date'] = $indent_date; 
						$data['encoded'] = $encoded; 
						
						$data['school_info'] = $this->db->query("select * from schools where school_id=?",array($school_id))->row();
						
						$data['multiples'] = $multiples = $indent_info_data->multiples; 
						$data['schools_list'] = $schools_list; 
				  
				$data["indent_info_gcc_submitted"] = $indent_info_data->gcc_submitted_date; 
				
				
				$this->excel->setActiveSheetIndex(0);
                //name the worksheet
				$title = "INDENT Report for ".ucfirst($indent_info_data->month_year) . " - ".$data['school_info']->school_code."-".$data['school_info']->name." . ";
                $this->excel->getActiveSheet()->setTitle("Indent Report");
               
                 $this->excel->getActiveSheet()->setCellValue('A1',  $title);
				//merge cell A2 until Q2
                $this->excel->getActiveSheet()->mergeCells('A1:H1');
				
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

				 $this->excel->getActiveSheet()->getStyle('A1:Z1')->applyFromArray( $style_header );
				$this->excel->getActiveSheet()->getStyle('A1:Z1')->getFont()->setBold(true);				
				 
				
				
				
				////////////////////////////////////////////////////////////////////////////////////////////////
				$this->excel->getActiveSheet()->setCellValue('A2', 'SNO');
				$this->excel->getActiveSheet()->setCellValue('B2', 'Item Name');		
				 
				$this->excel->getActiveSheet()->setCellValue('C2', 'No Of Days');
				$this->excel->getActiveSheet()->setCellValue('D2', 'Multiples');
				$this->excel->getActiveSheet()->setCellValue('E2', 'Strength');
				$this->excel->getActiveSheet()->setCellValue('F2', 'Opening Balance Qty');
				
				$this->excel->getActiveSheet()->setCellValue('G2', 'Monthly Required Qty');
				
				$this->excel->getActiveSheet()->setCellValue('H2', $multiples.' Months Required Qty'); 
				$this->excel->getActiveSheet()->setCellValue('I2',  'Required Qty per selected Days');  
				$this->excel->getActiveSheet()->setCellValue('J2',  'DTDO FINAL QTY'); 
			 
					 
			 
                 $this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                //make the font become bold
                $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
				$this->excel->getActiveSheet()->getStyle('A2:S2')->getFont()->setBold(true);
				
                $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setSize(12);
                //$this->excel->getActiveSheet()->getStyle('A3')->getFill()->getStartColor()->setARGB('#333');
                
                $i = 3;
				$sno=1;
				$consumption_amount_total = 0;
				$schools_data = $data['schools_data'];
				
				 //print_a($schools_data );die;
				 foreach($schools_data->result()  as $item) {
				 
					//PRINT_A($item,1);
					
					$this->excel->getActiveSheet()->setCellValue('A'.$i, $sno);
					$this->excel->getActiveSheet()->setCellValue('B'.$i,  $item->item_name); 
					$this->excel->getActiveSheet()->setCellValue('C'.$i,   $item->number_of_days);
					
					
					$this->excel->getActiveSheet()->setCellValue('D'.$i, $multiples);
					$this->excel->getActiveSheet()->setCellValue('E'.$i, $item->school_strength);
					$this->excel->getActiveSheet()->setCellValue('F'.$i, $item->opening_quantity);
					$this->excel->getActiveSheet()->setCellValue('G'.$i, $item->monthly_required_qty);
					
					
					$this->excel->getActiveSheet()->setCellValue('H'.$i, $item->multiples_monthly_required_qty); 
					$this->excel->getActiveSheet()->setCellValue('I'.$i,  $item->balance_qty); 
					 
					$this->excel->getActiveSheet()->setCellValue('J'.$i, $item->indent_raised_by_dtdo); 
					 
					
					  $this->excel->getActiveSheet()->getStyle('S'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
					 
					$i++;$sno++;
				}
	 
				 
					
				$filename =  $data['school_info']->school_code."-".$data['school_info']->name."-". ucfirst($indent_info_data->month_year)  ."-IndentReport.xls";
                
                header('Content-Type: application/vnd.ms-excel'); //mime type
                header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
                header('Cache-Control: max-age=0'); //no cache
 
                //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
                //if you want to save it as .XLSX Excel 2007 format
                $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');  
                //force user to download the Excel file without writing it to server's HD
				 
				 ob_end_clean();
				 ob_start();
                $objWriter->save('php://output'); 
			
		}

}
