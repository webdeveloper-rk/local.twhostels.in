<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Indendent extends MX_Controller {

    function __construct() {
        parent::__construct(); 
         $this->load->config("config.php");
         $this->load->model("admin/school_model");
		
		
		$this->load->library("ci_jwt");
		$this->load->model("common/common_model");
		 $this->load->library('excel'); 
		  //print_a($this->session->all_userdata(),1);
		 $allowed_roles = array( "subadmin" );
		 if(!in_array($this->session->userdata("user_role"),$allowed_roles))
		{
				redirect("admin/general/logout"); 
		}
    }

   

    function index() {

	redirect("indendent/alreadygenerated"); 

    }
	function metadata()
	{
		$month = date('m');
		$year = date('Y');
		/*if($month<10)
		{
			$month = "0".$month;
		}*/
		$sql = "select * from schools where is_school='1'" ;
		$rs = $this->db->query($sql);
		foreach($rs->result() as $rdata)
		{
			$school_id= $rdata->school_id;
			$strength =  $rdata->strength;
			$chkrs = $this->db->query("select * from indendent_sch_strength where school_id= ? and month=? and year=?",array($school_id,$month,$year));
			if($chkrs->num_rows()==0)
			{
				$ins_data = array('school_id'=>$school_id,'month'=>$month,'year'=>$year,'strength'=>$strength);
				$this->db->insert('indendent_sch_strength',$ins_data);
				
			}else{
				$up_data = array('strength'=>$strength);
				$condition= array('school_id'=>$school_id,'month'=>$month,'year'=>$year);
				$this->db->where($condition);
				$this->db->update('indendent_sch_strength',$up_data);
				//echo $this->db->last_query(),"<br>";
				
			}
		
		}
		echo "Done";
	
	
	}
	function listindendent()
	{
		 //print_a($this->session->all_userdata());
		 $allowed_roles = array( "subadmin" );
		 if(!in_array($this->session->userdata("user_role"),$allowed_roles))
		{
				redirect("admin/general/logout"); 
		}
		
		  $months = array("01"=>"January","02"=>"February","03"=>"March","04"=>"April","05"=>"May",
									"06"=>"June","07"=>"July","08"=>"August","09"=>"September","10"=>"October","11"=>"November","12"=>"December");	
									
									
		$schools_list = array(0);
		$data['school_selection'] = false;
		$data['display_result'] = false;
		if(in_array($this->session->userdata("user_role"),array("subadmin","gcc")))
		{
			$condition = '';
			$district_id= intval($this->session->userdata("district_id"));
			if($this->session->userdata("is_dco") ==1  )
			{
				$condition = " and district_id='$district_id' ";
			}
			$schools_list_rs = $this->db->query("select school_id,school_code,name from schools where is_school='1'  $condition  "); 
			foreach($schools_list_rs->result() as $row)
			{
				$schools_list[] = $row->school_id;
			}
			$data['school_selection'] = true;
		}
		$schools_condition = " and school_id in (".implode(",",$schools_list)." ) ";
		 
		$start_year = 2018;
		$end_year = date('Y') ;
		$this->form_validation->set_rules('month', 'Month', 'required|numeric|less_than_equal_to[12]|greater_than_equal_to[1]');  
		$this->form_validation->set_rules('year', 'Year', "required|numeric|less_than_equal_to[$end_year]|greater_than_equal_to[$start_year]");  
		
		$indendent_start_date = $this->config->item("indendent_start_date");
		if($this->form_validation->run() == true )
		{
			
			 
			 
			
			$month = intval($this->input->post('month')); 
			if($month<10)
				$month="0".$month;
			$year = intval($this->input->post('year'));
			$date = $year."-".$month."-01";
			/*if($date == $this->config->item("indendent_start_date"))
			{
				$date = $year."-".$month."-09";
			}*/
				
				
				$district_id =   $this->session->userdata("district_id");  
				$month_year =  intval($this->input->post('month')) . "-".intval($this->input->post('year')); 
				$alreadyexists_rs = $this->db->query("select * from indent_info where district_id=? and month_year=?",array($district_id ,$month_year));
				if($alreadyexists_rs->num_rows()>0)
				{
					$this->session->set_flashdata('message', '<div class="alert alert-danger">Indent already generated for selected month and year. pleasee check it in already generated list.</div>');
					redirect("indendent/listindendent");
				}
					
					
			
			$datecheck_qry =  "select  ? between  ? and CURRENT_DATE  as isvalid "; 
			  $isvalid = $this->db->query($datecheck_qry ,array($date,$indendent_start_date))->row()->isvalid;
			 if($isvalid ==0)
			 {
				$this->session->set_flashdata('message', '<div class="alert alert-danger">Data is not avilable for selected month and year .</div>');
				redirect("indendent");
			 }
			 
			$opening_rs = $this->db->query("select bs.school_id,bs.item_id,concat( ' ',it.item_name) as item_name,bs.opening_quantity,bs.opening_price,  bs.opening_quantity*bs.opening_price as opening_amount  from balance_sheet bs inner join items it 
												on bs.item_id=it.item_id and bs.entry_date=?  $schools_condition  order by item_name ",array($date));
											
			//echo $this->db->last_query();die;
			  
			$schools_data = array();
			foreach($opening_rs->result() as $scdata)
			{
				$schools_data[$scdata->school_id][$scdata->item_id] = $scdata;
			}
			
			
			
			///  print_a($schools_data,0);
			$number_of_days  = $this->db->query("select day(last_day('$date')) as last_day")->row()->last_day;  
			//$number_of_days  = $number_of_days * intval($this->input->post("multiples"));
			$multiples =  intval($this->input->post("multiples"));
			
			
			$schools_condition2 = "   school_id in (".implode(",",$schools_list)." ) ";
			$items_rs= $this->db->query("select school_id,
indt.item_id,it.item_name ,sum(strength) as total_strength,
TRUNCATE(sum((grams * strength * ? )),3) as required_qty from 
( select scs.school_id,scs.class_id,item_id,grams,strength from 
school_class_strengths scs inner join class_indent_grams grams on scs.class_id = grams.class_id  
) as indt   inner join  items it on indt.item_id = it.item_id where  $schools_condition2 group by school_id, item_id

 ",array($number_of_days));
 
 //echo $this->db->last_query();	die;
 
 
			foreach($items_rs->result() as $itemobj)
			{
					 //print_a($itemobj,0);
				 
				$schools_data[$itemobj->school_id][$itemobj->item_id]->number_of_days  =  $number_of_days ;
 
				$schools_data[$itemobj->school_id][$itemobj->item_id]->school_strength  = $itemobj->total_strength  ;
				$schools_data[$itemobj->school_id][$itemobj->item_id]->monthly_required_qty   = $itemobj->required_qty  ;
				
				$multiples_month_required_qty =  $itemobj->required_qty *  $multiples;
				
				$schools_data[$itemobj->school_id][$itemobj->item_id]->multiples_month_required_qty   = $multiples_month_required_qty ;
				 
				
				$schools_data[$itemobj->school_id][$itemobj->item_id]->balance_qty   = $multiples_month_required_qty  - $schools_data[$itemobj->school_id][$itemobj->item_id]->opening_quantity;  
				 
			}
			
			 // print_a($schools_data,1);
			  
			  
			  
			 
			  
			  
					$ins_data2['district_id'] =   $this->session->userdata("district_id"); 
					$ins_data2['indent_title'] =   $this->input->post('indent_title') ;
					$ins_data2['month_year'] =  intval($this->input->post('month')) . "-".intval($this->input->post('year')); 
					$ins_data2['multiples'] =  $this->input->post('multiples') ; 
					$this->db->insert("indent_info",$ins_data2);
					
					$indent_info_id = $this->db->insert_id();
									
					// print_a($schools_data,1);				
					
					foreach($schools_data as $school_id=>$school_info_data)
					{
						
								foreach($school_info_data as $item_id=>$itemobj)
								{
									// print_a($itemobj,1);
									$ins_data = array();
									$update_data = array();
										$chkrs = $this->db->query("select *  from indent where school_id=? and indent_date=? and item_id=? and indent_info_id=?",array($school_id,$date,$item_id,$indent_info_id));
										//echo $this->db->last_query(); 
										if($chkrs->num_rows()==0) { // if not exists add record to database 
									
												$ins_data['school_id'] = $school_id;
												$ins_data['indent_date'] = $date;
												$ins_data['item_id'] = $item_id;
												$ins_data['item_name'] = $itemobj->item_name;
												$ins_data['opening_quantity'] = $itemobj->opening_quantity;
												$ins_data['number_of_days'] = $itemobj->number_of_days;
												$ins_data['school_strength'] = $itemobj->school_strength;
												$ins_data['monthly_required_qty'] = $itemobj->monthly_required_qty;
												
												$ins_data['multiples'] = $multiples;
												$ins_data['multiples_monthly_required_qty'] = $itemobj->multiples_month_required_qty;
												
												$ins_data['balance_qty'] = $itemobj->balance_qty;
												$ins_data['indent_raised_by_dtdo'] = $itemobj->balance_qty;
												
												
												$ins_data['indent_info_id'] = $indent_info_id;
												$ins_data['raised_by'] = $this->session->userdata("user_id");
												$ins_data['district_id'] = $this->session->userdata("district_id");
												
												
												$this->db->insert("indent",$ins_data);
										}else
										{
												$ins_data['school_id'] = $school_id;
												$ins_data['indent_date'] = $date;
												$ins_data['item_id'] = $item_id;
												
												$update_data['item_name'] = $itemobj->item_name;
												$update_data['opening_quantity'] = $itemobj->opening_quantity;
												$update_data['number_of_days'] = $itemobj->number_of_days;
												$update_data['school_strength'] = $itemobj->school_strength;
												$update_data['monthly_required_qty'] = $itemobj->monthly_required_qty;
												$update_data['balance_qty'] = $itemobj->balance_qty;
												$update_data['indent_raised_by_dtdo'] = $itemobj->balance_qty;
												$update_data['multiples'] = $multiples;
												$update_data['multiples_monthly_required_qty'] = $itemobj->multiples_month_required_qty;
												
												$update_data['indent_info_id'] = $indent_info_id;
												
												$this->db->where('school_id', $school_id); 
												$this->db->where('indent_date', $date);
												$this->db->where('item_id', $item_id);
												$this->db->update("indent",$update_data);
												
										}
										// echo $this->db->last_query();die;
								}
					}
					
					$dp_date = $this->db->query("select date_format(?,'%d-%M-%Y') as dp_date",array($date))->row()->dp_date;
					//redirect to view indent page 
					 $vieweditencoded =   $this->ci_jwt->jwt_web_encode(array('indent_date'=>$date,"school_id"=>$school_id,'dp_date'=>$dp_date));
					 redirect("indendent/alreadygenerated");
					 
					
					
				$schools_data =   $this->db->query("select *  from indent where school_id=? and indent_date=?  ",array($school_id,$date));
				$data['schools_data'] = $schools_data; 
				$data['month'] = $month; 
				$data['month_name'] =  $months[$month]; 
				$data['year'] = $year; 
				$data['school_info'] = $this->db->query("select * from schools where school_id=?",array($school_id))->row();
				
				 
				if(strtolower(trim($this->input->post('submit')))=="download")
				{
					 
					$this->download_indent_report($data);
					 
					die;
				}
				
				
				
				$data['display_result'] = true;
			  
		}
		
	 
		$data['schools_list'] = $schools_list; 
		  
		$data["module"] = "indendent"; 
        $data["view_file"] = "indendent"; 
        echo Modules::run("template/admin", $data);
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


function edit($encoded)
{
	$endata = $this->ci_jwt->jwt_web_decode($encoded);
	
	$indent_auto_id = $endata->indent_auto_id;
	
	 $indent_info_id = $this->db->query("select * from indent where indent_auto_id=?",array($indent_auto_id ))->row()->indent_info_id;
	 $indent_info_data = $this->db->query("select * from indent_info where indent_info_id=?",array($indent_info_id ))->row();
	 if($indent_info_data->gcc_submitted_date==1)
	 {
		die("<h1>Access Denied</h1>");
	 }
	
	$action = $this->input->post("action");
	if(isset($action))
	{
		//echo "Here";
		$action = $this->input->post("action");
		if($action=="updateindenet")
		{
			$dtdo_raised_qty  = floatval($this->input->post("dtdo_quantity"));
			$indent_auto_id  = intval($this->input->post("indent_auto_id")) ;
			$this->db->query("update indent set indent_raised_by_dtdo=? where indent_auto_id=?",array($dtdo_raised_qty, $indent_auto_id));
			//echo $this->db->last_query();
			$indent_data = $this->db->query("select * from indent where indent_auto_id=?",array($indent_auto_id))->row();
		//	print_a($indent_data,1);
			
			$this->session->set_flashdata("message","<div class='alert alert-success' style='margin-left: 0;margin-top: 10px;'>Updated Successfully</div>" );
			
			$dp_date = $this->db->query("select date_format(?,'%d-%M-%Y') as dp_date",array($indent_data->indent_date))->row()->dp_date;
					//redirect to view indent page 
					$school_id = $indent_data->school_id;
					 $vieweditencoded =   $this->ci_jwt->jwt_web_encode(array('indent_info_id'=>$indent_data->indent_info_id,'indent_date'=>$indent_data->indent_date,"school_id"=>$school_id,'dp_date'=>$dp_date));
					 redirect("indendent/viewindent/".$vieweditencoded); 
		}
	}
	
	//print_a($endata );
	
	 
	$rs = $this->db->query("select * from  indent where indent_auto_id=?",array($indent_auto_id));
	$indent_data = $rs->row();
	$school_info = $this->db->query("select * from schools where school_id=?",array($indent_data->school_id))->row();
	
	$dp_date = $this->db->query("select date_format(?,'%d-%M-%Y') as dp_date ",array($indent_data->indent_date))->row()->dp_date;
	
	$data["dp_date"] = $dp_date; 
	$data["school_info"] = $school_info; 
	$data["indent_data"] = $indent_data;
	
	$data["module"] = "indendent"; 
        $data["view_file"] = "edit_form"; 
        echo Modules::run("template/admin", $data);
 
	
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
		$data["module"] = "indendent"; 
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
	 
		$data['generated_list'] = $this->db->query(" select *,date_format(created_date,'%d-%M-%Y %H:%i:%s') as created_date,date_format(gcc_submitted_date,'%d-%M-%Y %H:%i:%s') as gcc_submitted_date from indent_info where district_id=? order by indent_info_id desc ",array($this->session->userdata("district_id")));
		//echo $this->db->last_query();die;
		
		 
		  
		$data["module"] = "indendent"; 
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
		
		 
		  
		$data["module"] = "indendent"; 
        $data["view_file"] = "generated_reports"; 
        echo Modules::run("template/admin", $data);
	}


}
