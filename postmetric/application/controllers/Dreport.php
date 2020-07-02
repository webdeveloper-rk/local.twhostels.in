<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dreport extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	 public function index()
	 {
		 $data['header'] = "withheader";
		 $this->load->view('dreport_list',$data);
	 }
	 function frame()
	 {
		 $this->load->view("framewindow");
	 }
	 public function viewschools($headershow='noheader')
	 {
		 $data['header'] = $headershow;
		 $this->load->view('dreport_list');
	 }
	 
	public function listview($school_code = '',$header='with')
	{
			 	  
		$school_id = 0;
		$today_allowed_Amount = '0.00';
		$today_consumed_Amount = '0.00';
		$today_remaining_Amount = '0.00';
		$data['result_flag']			  =  0;
		
		
		//echo "select * from users where school_code='$school_code'";die;
		
		 $srs = $this->db->query("select * from users where school_code='$school_code'");
			 if($srs->num_rows()==0)
			 {
					redirect(site_url('dreport'));die;
			 }
			 
			 
		 
			/* if(date("H")<17)
			 {
				 $school_date = date('Y-m-d',strtotime("-1 days"));
			 }
			 else
			 {
				 //Take Current date if Hour > 5 PM.
				 $school_date = date('Y-m-d');
			 }
			 */
			 $school_date = date('Y-m-d');
			 
			$school_data = $srs->row();
			$data['school_info'] =  $school_data;
			$school_id = $school_data->school_id;		
			$data['result_flag'] =  1;

		 //echo $school_date  ;
		 
		 
		/* calculate balances */
		
		$trs_sql  = "select sum(round(((session_1_qty*session_1_price) +(session_2_qty*session_2_price)+(session_3_qty*session_3_price)+(session_4_qty*session_4_price)),2)) as consumed_total
					from balance_sheet where school_id='$school_id' and entry_date='$school_date'";
		
		/*if($_GET['test']==1)
			echo $trs_sql ;
		*/
		$trs = $this->db->query($trs_sql);
					
		if($trs->num_rows()>0)
		{
			$tdata = $trs->row();
			$today_consumed_Amount = $tdata->consumed_total;
		}
		/**********Calculate attendence ***************/
		
		$daily_amount  = 0.00;
		
		/*
			Get Amounts				
		*/ 
		$price_sql = "select * from group_prices";
		$price_rs = $this->db->query($price_sql);
		$student_prices = array();
		foreach($price_rs->result() as $stu_price){
			$student_prices[$stu_price->group_code] = $stu_price->amount;
		}


		
		

		/******get attendence ******/
			
		$attendece  = 0;
		$group_1_attendence = 0;
		$group_2_attendence = 0;
		$group_3_attendence = 0;
		$group_1_price = 0;
		$group_2_price = 0;
		$group_3_price = 0;
		
		$group_1_perday_price = 0;
		$group_2_perday_price = 0;
		$group_3_perday_price = 0;
		
		
		$days_sql = "SELECT DAY( LAST_DAY( '$school_date' ) ) as days";
		$days_row  = $this->db->query($days_sql)->row();
		  $days_count = $days_row->days ;
		//print_a($student_prices);
		
		$group_1_perday_price = ($student_prices['gp_5_7']/$days_count);
		$group_2_perday_price =($student_prices['gp_8_10']/$days_count);
		$group_3_perday_price = ($student_prices['gp_inter']/$days_count);
		
		$atters_sql = "select * from school_attendence where school_id='$school_id' and entry_date='$school_date'";
		$atters = $this->db->query($atters_sql);
		if($atters->num_rows()>0)
		{
			$attedata = $atters->row();
			
			$group_1_attendence =  $attedata->cat1_attendence  +  $attedata->cat1_guest_attendence;
			$group_2_attendence =  $attedata->cat2_attendence  + $attedata->cat2_guest_attendence;
			$group_3_attendence =  $attedata->cat3_attendence  + $attedata->cat3_guest_attendence;
			 
			$group_1_price =  $group_1_attendence * ($student_prices['gp_5_7']/$days_count);
			$group_2_price =  $group_2_attendence * ($student_prices['gp_8_10']/$days_count);
			$group_3_price =  $group_3_attendence *  ($student_prices['gp_inter']/$days_count);
			
			
			
			$attendece =  $attedata->present_count;
			$attendece2 = $group_1_attendence + $group_2_attendence + $group_3_attendence;
		}
		$data['student_prices'] = $student_prices;
		$data['days_count'] = $days_count;
		$data['group_1_attendence'] = $group_1_attendence;
		$data['group_2_attendence']= $group_2_attendence ;
		$data['group_3_attendence'] = $group_3_attendence ;
		$data['group_1_price'] = number_format($group_1_price,2);
		$data['group_2_price'] = number_format($group_2_price,2);
		$data['group_3_price'] = number_format($group_3_price,2);
		
		$data['group_1_perday_price'] = number_format($group_1_perday_price,4);
		$data['group_2_perday_price'] = number_format($group_2_perday_price,4);
		$data['group_3_perday_price'] = number_format($group_3_perday_price,4);
		 
		//echo $attendece2 ;
	//	echo $group_1_attendence ,"--",$group_2_attendence ,"---",$group_3_attendence ,"---"  ;
		//echo "<br>",$group_1_price ,"--",$group_2_price ,"---",$group_3_price ,"---"  ;
		$today_allowed_Amount = $group_1_price + $group_2_price + $group_3_price;
		/**************************************/
		
        $data["reportdate"] =   date('d-M-Y',strtotime($school_date))  ;
        $data["school_name"] = $today_allowed_Amount;
        $data["per_stundent"] = $daily_amount;
        $data["attendence"] = $attendece;
		
        $data["today_allowed_Amount"] = number_format($today_allowed_Amount,2);
        $data["today_consumed_Amount"] = $today_consumed_Amount;
        $data["today_remaining_Amount"] = number_format($today_allowed_Amount -  $today_consumed_Amount,2);
		
        $data["header"] =  $header;
        $data["school_code"] = $school_code;
        $data["module"] = "admin";
        $data["view_file"] = "school_consumed";
		$this->load->view("school_consumed",$data);
  //      echo Modules::run("template/admin", $data);
  //print_a($data);
  //print_a($data);
  
	
	
		
	}
	
	function reports_old($school_code='')
	{
		 
		 
		 
		 $srs = $this->db->query("select * from users where school_code='$school_code'");
			 if($srs->num_rows()==0)
			 {
					redirect(site_url('dreport'));die;
			 }
			 
			 
		 
			 if(date("H")<17)
			 {
				 $school_date = date('Y-m-d',strtotime("-1 days"));
			 }
			 else
			 {
				 //Take Current date if Hour > 5 PM.
				 $school_date = date('Y-m-d');
			 }
			
			$school_data = $srs->row();
			$data['school_info'] =  $school_data;
			 $school_name =  $school_data->name;
			$school_id = $school_data->school_id;	
			
			 
			$items = array();
			 $item_ids = array(0);
			$itrs = $this->db->query("select * from balance_sheet where entry_date ='$school_date'");
			foreach($itrs->result() as $itrow)
			{
				$item_ids[] = $itrow->item_id;
			}
			 
				$from_date =  $school_date ;
				$to_date = $school_date ;
				
	 
			$school_ids	 = array($school_id);
				$this->get_report_values($school_ids ,$item_ids,$from_date,$to_date,$school_name );
		 
					
		 
		
		
	}
	
	
	function get_report_values($school_ids,$item_ids,$from_date,$to_date,$school_name )
	{
		
		$schools_in = implode(",",$school_ids);
		$item_ids_in = implode(",",$item_ids);
		//print_a($schools_in);
		//print_a($item_ids_in);
		$item_rs =  $this->db->query("select * from items");
		$item_count =  $item_rs->num_rows();
		
		$school_rs =  $this->db->query("select * from schools");
		$school_count =  $school_rs->num_rows();
		
		if(count($school_ids)==$school_count)
		{
			$school_condition = " ";
		}
		else{
			$school_condition = " and school_id in ($schools_in)  ";
		}
		
		if(count($item_ids)==$item_count)
		{
			$item_condition = " ";
		}
		else{
			$item_condition = " and item_id in ($item_ids_in)   ";
		}
		
		$tbl_purchased = "tbl_purchased_".uniqid();
		    $sql   = "		create temporary table  $tbl_purchased 	
						select school_id,item_id,sum(purchase_quantity) as purchase_qty   from balance_sheet consolidated_purchased 
						where entry_date between '$from_date' and '$to_date' $school_condition $item_condition  group by school_id,item_id";
		
		  $this->db->query($sql);
		 
		$tbl_consumed = "tbl_consumed".uniqid();
		  $sql   = "	create temporary table  $tbl_consumed 
							select school_id,item_id,
							sum((session_1_qty+session_2_qty+session_3_qty+session_4_qty)) as consumed_qty,
							sum(( 
									(session_1_qty*session_1_price) +
									(session_2_qty*session_2_price)+ 
									(session_3_qty*session_3_price) + 
									(session_4_qty*session_4_price)
									)) as consumed_amount

							from balance_sheet consolidated_consumed 
							where entry_date between '$from_date' and '$to_date' $school_condition $item_condition  group by school_id,item_id";
		
	  $this->db->query($sql);
		
		$tbl_opening_balances = "tbl_opening_balances".uniqid();
		$sql   = "	create temporary table  $tbl_opening_balances 
		select bs_opening.school_id, bs_opening.item_id,bs_opening.entry_date,opening_entries_selected.entry_date max_selected_date,bs_opening.closing_quantity as opening_quantity from 
		balance_sheet bs_opening inner join 
		(SELECT school_id,item_id,max(entry_date) as entry_date from balance_sheet where entry_date<'$from_date'
		$school_condition $item_condition group by school_id,item_id)  as opening_entries_selected
		on bs_opening.entry_date  = opening_entries_selected.entry_date  and 
		bs_opening.school_id = opening_entries_selected.school_id and 
		bs_opening.item_id = opening_entries_selected.item_id 		 
		group by bs_opening.school_id, bs_opening.item_id";

		 $this->db->query($sql);
 
		$tbl_closed_balances = "tbl_closed_balances".uniqid();
		$sql   = " create temporary table  $tbl_closed_balances 
		select bs_close.school_id, bs_close.item_id,bs_close.entry_date,closeing_entries_selected.entry_date max_selected_date,bs_close.`closing_quantity` from 
		balance_sheet bs_close inner join 
		(SELECT school_id,item_id,max(entry_date) as entry_date from balance_sheet where 
		entry_date<='$to_date'   and school_id in ($schools_in) and item_id in ($item_ids_in) group by school_id,item_id)  as closeing_entries_selected

		on bs_close.entry_date  = closeing_entries_selected.entry_date  and 
		bs_close.school_id = closeing_entries_selected.school_id and 
		bs_close.item_id = closeing_entries_selected.item_id 

		group by bs_close.school_id, bs_close.item_id ";
		
		  $this->db->query($sql);
		
		$consolidate_table = "consolidated_".uniqid();
		  $sql = "create temporary table $consolidate_table 
		  select tp.school_id,tp.item_id,top.opening_quantity,tp.purchase_qty,tc.consumed_qty ,tc.consumed_amount ,tcb.closing_quantity from  
					$tbl_purchased tp  inner join $tbl_consumed tc on  tp.item_id=tc.item_id and tp.school_id = tc.school_id
								inner join $tbl_opening_balances top on tp.item_id = top.item_id and tp.school_id = top.school_id 
								inner join $tbl_closed_balances tcb on tcb.item_id = tp.item_id and tp.school_id = tcb.school_id  
		
				";
		  $this->db->query($sql);
		  
		$csql = "select * from $consolidate_table order by school_id";
		$crs = $this->db->query($csql); 
		// echo $this->table->generate($crs);
		//echo "<br>**********************<br>";
		$sql = "select ct.item_id,items.item_name,sum(opening_quantity) as opening_quantity ,
						sum(purchase_qty) as purchase_qty,
						sum(consumed_qty) as consumed_qty,
						sum(consumed_amount) as consumed_amount,
						sum(closing_quantity) as closing_quantity from $consolidate_table ct inner join items on items.item_id = ct.item_id  group by ct.item_id
		";
		$sumrs = $this->db->query($sql );
		
				$i=1; 
				$total_amount = 0;
				$total_qty = 0;
				$output_rows = array();
				
				 
				foreach($sumrs->result() as $printitem){ 
				 $total_amount = $total_amount + $printitem->consumed_amount;
				
				$total_qty = 0;				 
				$total_qty = $printitem->opening_quantity   + $printitem->purchase_qty;
				$output_rows[$i]['sno'] = $i;
				$output_rows[$i]['item_name'] =  $printitem->item_name;
				$output_rows[$i]['opening_quantity'] = number_format($printitem->opening_quantity,3,'.', '');
				$output_rows[$i]['purchase_qty'] = number_format($printitem->purchase_qty,3,'.', '');
				$output_rows[$i]['total_qty'] =  number_format($total_qty ,3,'.', '');
				$output_rows[$i]['consumed_qty'] = number_format($printitem->consumed_qty,3,'.', '');
				$output_rows[$i]['closing_quantity'] = number_format($printitem->closing_quantity ,3,'.', '');
				$output_rows[$i]['consumed_amount'] =number_format($printitem->consumed_amount ,2,'.', '');
			 $i++;
				}
			  
			$data["school_name"] = $school_name;
			$data["items"] = $output_rows ;
			$data["total_amount"] = $total_amount ;
			$data["from_date"] = date('d/M/Y',strtotime($from_date)) ;
			$data["to_date"] = date('d/M/Y',strtotime($to_date)) ; 
		 
			//print_a($data);
			$this->load->view("report_display",$data);
	}
	
	/***********************************
	
	/***********************************/
		function reports($school_code='')
	{
		$sql_t = sprintf("select * from schools where school_code='%s'",$school_code);
		$schrs = $this->db->query($sql_t);
		$schooldata = $schrs ->row();
		$school_id = $schooldata->school_id;
		 
		
		 $today = date('Y-m-d');
		 
		 // $today = '2018-02-19';
				//get all items between dates 
				      $sql = "SELECT *,
							 ( session_1_qty+session_2_qty+session_3_qty+session_4_qty)  as consumed_qty ,
							 	
								(
									(session_1_qty*session_1_price) + 
									(session_2_qty*session_2_price) + 
									(session_3_qty*session_3_price) + 
									(session_4_qty*session_4_price) 
								)	   as consumed_total,
							(purchase_quantity*purchase_price) purchase_total
							

				  FROM `balance_sheet`   
				WHERE  school_id='".$school_id ."' and  entry_date = '$today' order by consumed_qty desc ";
				$rs  = $this->db->query($sql);
				 $data["rset"] = $rs;
				 
				 
		 $drs = $this->db->query("select * from  items  ");         
        $item_names = array();
		foreach($drs->result() as $row)
		{
			$item_names[$row->item_id] = $row->telugu_name ." - ".$row->item_name;
		}
        $data["itemnames"] = $item_names;
		 
		
		$data["schooldata"] = $schooldata;
		$data["today_date"] = date('d-M-Y');
		$data["module"] = "admin";
         
         
         $this->load->view("today_customreports",$data);
	}
	
	
	
	
	//old reports link
	
}
