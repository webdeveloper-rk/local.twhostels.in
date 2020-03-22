<?php 
 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
set_time_limit(0);
 date_default_timezone_set('Asia/Kolkata');
class School_consolidated_monthly extends MX_Controller {

    function __construct() {
        parent::__construct();
		if($this->uri->segment(2) !="login") { 
					 Modules::run("security/is_admin");		 
					
		}
		if ($this->session->userdata("is_loggedin") != TRUE || $this->session->userdata("user_id") == "" ) {
							redirect("admin/login");
							die;
					}
					 			
					if($this->session->userdata("user_role") != "subadmin")
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
		$data["display_result"] = false;
		$data['fromdate'] = ''; 
		$data['todate'] = '';
	
					
		$in_time = $this->db->query("select CURRENT_TIME >= convert(? ,TIME) as in_time",array($this->config->item('allowed_after')))->row()->in_time;
		if($in_time == 0)
		{
			$this->session->set_flashdata('message', '<div class="alert alert-danger"><h1>This Report Disabled untill 6:00 PM</h1></div>');
				redirect(current_url()."/disbaled");
		}
	 
	 
		$this->form_validation->set_rules('school_id', 'School', 'required');              
		$this->form_validation->set_rules('month', 'Month', 'required');              
		$this->form_validation->set_rules('year', 'Year', 'required'); 
		 
		$item_names   = array();
		$data = array();
		$data['exclude'] = '';
		if($this->form_validation->run() == true && $this->input->post('month') !="" && $this->input->post('year') !="")
		{
				
			 
			
				$data['exclude'] = $this->input->post('exclude');
			 
				$from_date = $this->input->post('year')."-".$this->input->post('month')."-01";
				$to_date = $this->db->query("select last_day(?) as last_day ",array($from_date))->row()->last_day;
				
				 
				 
				$school_id =  $this->input->post('school_id') ;
				
				$exclude = $this->input->post('exclude');
				$display_unused_items = true;
				if(isset($exclude))
				{
					$display_unused_items = false;
				}
				$data['display_unused_items'] = $display_unused_items;
				
				$opening_qty = 0;
				$opening_price = 0;
				$items = array();
				
				//get all items between dates 
				  $sql = "SELECT distinct bs.item_id,item_name   FROM `balance_sheet`  bs inner join items on items.item_id=bs.item_id  
				WHERE bs.school_id=? and  bs.entry_date between ? and ?";
				$rs  = $this->db->query($sql,array($school_id,$from_date,$to_date ));
				$item_ids  = array();
				
				foreach($rs->result() as $row)
				{
					$item_ids[] = $row->item_id;
					$item_names[$row->item_id] = $row->item_name;
				}
			 
				  /*foreach($item_ids as $opening_item_id){
								
								
								$tsql = "select *,truncate((opening_quantity*opening_price),2) as opening_total from balance_sheet 
								where school_id=? and 
								entry_date BETWEEN  ?  and ?  and 
								item_id=? order by entry_date asc limit 0,1";
								$trs  = $this->db->query($tsql,array($school_id,$from_date,$to_date,$opening_item_id));
								if($trs->num_rows()>0){
									$sdata = $trs->row();
										$items[$opening_item_id]['opening_quantity'] = $sdata->opening_quantity;
										$items[$opening_item_id]['opening_price'] = $sdata->opening_price;
										$items[$opening_item_id]['opening_total'] = $sdata->opening_total;
								}
								else{
										$tsql = "select *,truncate((opening_quantity*opening_price),2) as opening_total from balance_sheet where
													school_id=? and entry_date <=? and item_id=? order by entry_date desc limit 0,1";
									$trs  = $this->db->query($tsql,array($school_id,$from_date,$opening_item_id));
										if($trs->num_rows()>0){
											$sdata = $trs->row();
												$items[$opening_item_id]['opening_quantity'] = $sdata->opening_quantity;
												$items[$opening_item_id]['opening_price'] = $sdata->opening_price;
												$items[$opening_item_id]['opening_total'] = $sdata->opening_total;
										}
									
								}
		
					}*/
					//opening balance calculation 
					$ob_rs = $this->db->query("
select it.item_name,bs.item_id,bs.closing_quantity as opening_quantity from  
balance_sheet bs inner join (select item_id,max(entry_date) as entry_date from balance_sheet where entry_date<?  and school_id=? and item_id>0 group by item_id )
as cb on bs.item_id = cb.item_id and bs.entry_date = cb.entry_date 
inner join items it on it.item_id = bs.item_id 
where bs.school_id = ? ",array($from_date,$school_id,$school_id));
foreach($ob_rs->result() as $ob_row)
{
		$items[$ob_row->item_id]['opening_quantity'] = $ob_row->opening_quantity;
		$items[$ob_row->item_id]['opening_price'] = 0;
		$items[$ob_row->item_id]['opening_total'] = 0;
}
				 
					
					/*foreach($item_ids as $opening_item_id){
								
								 
								$tsql = "select *,truncate((closing_quantity*closing_price),2) as closing_total from balance_sheet 
								where school_id='".$school_id."' and 
								entry_date BETWEEN '".$from_date ."'  and '".$to_date ."'  and 
								item_id='".$opening_item_id."' order by entry_date desc limit 0,1";
								$trs  = $this->db->query($tsql);
								if($trs->num_rows()>0){
									$sdata = $trs->row();
										$items[$opening_item_id]['closing_quantity'] = $sdata->closing_quantity;
										$items[$opening_item_id]['closing_price'] = $sdata->closing_price;
										$items[$opening_item_id]['closing_total'] = $sdata->closing_total;
								}
								else{
										$tsql =  "select *,(closing_quantity*closing_price) as closing_total from balance_sheet 
														`where school_id=? and  entry_date <=?  and item_id=?  order by entry_date desc limit 0,1";
									$trs  = $this->db->query($tsql,array($school_id,$from_date,$opening_item_id));
										if($trs->num_rows()>0){
											$sdata = $trs->row();
												$items[$opening_item_id]['closing_quantity'] = $sdata->closing_quantity;
												$items[$opening_item_id]['closing_price'] = $sdata->closing_price;
												$items[$opening_item_id]['closing_total'] = $sdata->closing_total;
										}
									
								}
		
					}
					*/
					//closing balance calculation 
					$cb_rs = $this->db->query("select it.item_name,bs.item_id,bs.closing_quantity as closing_quantity from  
balance_sheet bs inner join (select item_id,max(entry_date) as entry_date from balance_sheet where entry_date<=?  and school_id=? and item_id>0 group by item_id )
as cb on bs.item_id = cb.item_id and bs.entry_date = cb.entry_date 
inner join items it on it.item_id = bs.item_id 
where bs.school_id = ? ",array($to_date,$school_id,$school_id));
foreach($cb_rs->result() as $ob_row)
{
		$items[$ob_row->item_id]['closing_quantity'] = $ob_row->closing_quantity;
		$items[$ob_row->item_id]['closing_price'] = 0;
		$items[$ob_row->item_id]['closing_total'] = 0;
}
					
								
					/* end of closing balances */
					
				/* calculate consumption data 
				*/				
				$sql = "select item_id,sum(( session_1_qty+session_2_qty+session_3_qty+session_4_qty)) as consumed_qty ,
						truncate(sum(
							( 	(session_1_qty*session_1_price) + 
								(session_2_qty*session_2_price) + 
								(session_3_qty*session_3_price) + 
								(session_4_qty*session_4_price) )
							),2) as consumed_total
						from balance_sheet
					where school_id=? and  entry_date between ? and  ? group by item_id";
				 $rs  = $this->db->query($sql,array($school_id,$from_date,$to_date));
				foreach($rs->result() as $row)
				{
					$items[$row->item_id]['consumed_quantity'] = $row->consumed_qty;
					$items[$row->item_id]['consumed_total'] = $row->consumed_total;
					 
				}	
		
		/*
		calculate purchase data 
		*/
		
		$sql ="select item_id,sum(purchase_quantity) as purchase_qty,	purchase_price,
		
							truncate(sum((purchase_quantity*purchase_price)),2) purchase_total from balance_sheet 
							where school_id=? and entry_date between ? and ? group by item_id";				 
		
		
		$rs  = $this->db->query($sql,array($school_id,$from_date,$to_date));
				foreach($rs->result() as $row)
				{
					$items[$row->item_id]['purchase_price'] = $row->purchase_price;
					$items[$row->item_id]['purchase_qty'] = $row->purchase_qty;
					$items[$row->item_id]['purchase_total'] = $row->purchase_total;
					 
				}					
				
				 /* end purchase data */
				 
				 //rearrange items array , check all keys exists or not if not add trhem
				 
				  $items_dup = $items;
				 foreach($items_dup as $item_id=>$itemobj)
				 {
					 
					 $cols =  array(
								'opening_quantity',
								'opening_price',
								'opening_total',
								'purchase_price',
								'closing_quantity',
								'closing_price',
								'closing_total',
								'consumed_quantity',
								'consumed_total',
								'purchase_qty',
								'purchase_total',
								'total_qty',
								'total_price');
								foreach($cols as $column){
										 if(!array_key_exists( $column,$itemobj))
												$items[$item_id][ $column] = '0';
								}
						
						
				 }
				 /* calaculate Total from opeinig balance and purchase balance */
				 $items_dup = $items;
				 foreach($items_dup as $item_id=>$itemobj)
				 {
					// echo $item_id;
					 
				//print_a($item_id,1);
						$items[$item_id]['total_qty'] =    ($itemobj['purchase_qty'] +  $itemobj['opening_quantity']);
						$items[$item_id]['total_price'] =   ($itemobj['purchase_total'] +  $itemobj['opening_total']);
				 }
				 
				$data["display_result"] = true;
				$data['rfrom_date'] = $from_date;
				$data['rto_date'] = $to_date;
				$data['items'] = $items;
				$data['fromdate'] = date('m/d/Y',strtotime($this->input->post('fromdate')));
				$data['todate'] =  date('m/d/Y',strtotime($this->input->post('todate')));
				
				$data['f_fromdate'] = date('d-M-Y',strtotime($this->input->post('fromdate')));
				$data['f_todate'] =  date('d-M-Y',strtotime($this->input->post('todate')));
				
				$data["school_name_rs"] = $this->db->query("select * from  schools where school_id=?",array($school_id));; 
				$data["school_name"] =  $data["school_name_rs"]->row()->school_code." - ". $data["school_name_rs"]->row()->name; 
				
				if($this->input->post('submit')=="Download Report")
				{
					$filedata['fromdate'] = date('d-M-Y',strtotime($this->input->post('fromdate')));
					$filedata['todate'] =  date('d-M-Y',strtotime($this->input->post('todate')));
					$filedata['sname'] = $data["school_name"];
					$this->consolidated_report($items,$filedata,$item_names);
					die;
				} 
		}
		
		
		 
		$drs = $this->db->query("select * from  items where status='1'");         
        $data["rset"] = $drs;
        $data["itemnames"] = $item_names;
        
		
		
		$data["module"] = "school_consolidated_monthly";
					//$data["view_file"] = "consolidated_between_dates";
        $data["view_file"] = "consolidated_monthly";
        echo Modules::run("template/admin", $data);
 
	}
	function disbaled()
	{
		$in_time = $this->db->query("select CURRENT_TIME >= convert(? ,TIME) as in_time",array($this->config->item('allowed_after')))->row()->in_time;
		if($in_time == 1)
		{
			 
				redirect("school_consolidated_monthly");
		}
		
		$time = date('h:i:s A',strtotime($this->config->item('allowed_after')));
		$data["message"] = '<div class="alert alert-danger"><h1>This Report Disabled untill '.$time.'</h1></div>';
		$data["module"] = "school_consolidated_monthly";
        $data["view_file"] = "disbaled_message";
        echo Modules::run("template/admin", $data);
	}
    
		private function consolidated_report($rows,$metadata,$item_names)
    {
          
                $this->excel->setActiveSheetIndex(0);
                //name the worksheet
                $this->excel->getActiveSheet()->setTitle('Report');
                //set cell A1 content with some text
                $this->excel->getActiveSheet()->setCellValue('A1', $this->config->item('society_name').$metadata['sname']);
				 //merge cell A1 until Q1
                $this->excel->getActiveSheet()->mergeCells('A1:H1');
                $this->excel->getActiveSheet()->setCellValue('A2', 'DIET EXPENDITURE STATEMENT FOR dates between '.$metadata['fromdate']. " - ".$metadata['todate']);
				//merge cell A2 until Q2
                $this->excel->getActiveSheet()->mergeCells('A2:H2');
				
				$this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                //make the font become bold
                $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
                $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(16);
                $this->excel->getActiveSheet()->getStyle('A1')->getFill()->getStartColor()->setARGB('#333');
				
				$this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                //make the font become bold
                $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
                $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setSize(12);
                $this->excel->getActiveSheet()->getStyle('A2')->getFill()->getStartColor()->setARGB('#333');
				
				
				
				 
				
				$this->excel->getActiveSheet()->setCellValue('A3', 'SLNO');
				$this->excel->getActiveSheet()->setCellValue('B3', 'Item');				
				$this->excel->getActiveSheet()->setCellValue('C3', 'Opening Balance Qty');
				$this->excel->getActiveSheet()->setCellValue('D3', 'Purchase Qty');
				$this->excel->getActiveSheet()->setCellValue('E3', 'Purchase Amount');
				
				$this->excel->getActiveSheet()->setCellValue('F3', 'Total Qty');				
				$this->excel->getActiveSheet()->setCellValue('G3', 'Consumption Qty');				
				$this->excel->getActiveSheet()->setCellValue('H3', 'Closing Qty');				
				$this->excel->getActiveSheet()->setCellValue('I3', 'Consumption Amount');
			 
														 
				
                 $this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                //make the font become bold
                $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
				$this->excel->getActiveSheet()->getStyle('A3:O3')->getFont()->setBold(true);
				
                $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setSize(12);
                $this->excel->getActiveSheet()->getStyle('A2')->getFill()->getStartColor()->setARGB('#333');
                
                $i = 4;
				$sno=1;
				$consumption_amount_total = 0;
				$purchase_total_amount = 0;
				foreach($rows as $item_idd =>$rowitem)
				{
					//echo $item_idd;
				//print_a($rowitem);
				
				if($this->input->post('exclude')=="exclude" && $rowitem['consumed_quantity']==0)
						continue;
					
				 
				 
				 $purchase_total_amount =  $purchase_total_amount + $rowitem['purchase_total'];
				 
						$consumption_amount_total = $consumption_amount_total + 	$rowitem['consumed_total'];	
					$this->excel->getActiveSheet()->setCellValue('A'.$i,  $sno);
					$this->excel->getActiveSheet()->setCellValue('B'.$i, $item_names[$item_idd]);
					$this->excel->getActiveSheet()->setCellValue('C'.$i, $rowitem['opening_quantity']);
					$this->excel->getActiveSheet()->setCellValue('D'.$i, $rowitem['purchase_qty']);
					$this->excel->getActiveSheet()->setCellValue('E'.$i, $rowitem['purchase_total']);
					
					
					$this->excel->getActiveSheet()->setCellValue('F'.$i, $rowitem['total_qty']);
					$this->excel->getActiveSheet()->setCellValue('G'.$i, $rowitem['consumed_quantity']);
					
					$this->excel->getActiveSheet()->setCellValue('H'.$i, $rowitem['closing_quantity']);
					$this->excel->getActiveSheet()->setCellValue('I'.$i,  number_format($rowitem['consumed_total'],2));
					 
					$i++;$sno++;
				}
	 
					$this->excel->getActiveSheet()->setCellValue('D'.$i, "Total PURCHASED  Amount");
					$this->excel->getActiveSheet()->setCellValue('E'.$i, $purchase_total_amount);
					$this->excel->getActiveSheet()->setCellValue('H'.$i, "Total Consumption  Amount");
					$this->excel->getActiveSheet()->setCellValue('I'.$i,  number_format($consumption_amount_total,2));
				$this->excel->getActiveSheet()->getStyle('A'.$i.':Z'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                //make the font become bold
                $this->excel->getActiveSheet()->getStyle('A'.$i.':Z'.$i)->getFont()->setBold(true);
                
              
                $filename='consolidated_report_'.date('d-m-Y H:i:s').'.xls'; //save our workbook as this file name
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
	

}
