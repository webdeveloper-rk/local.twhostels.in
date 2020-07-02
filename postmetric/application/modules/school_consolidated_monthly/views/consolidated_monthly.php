<style>
.bold td
{
	font-weight:bold;
}
.red
{
	color:#FF0000;
}
</style>
<h3>Consolidated Report </h3>
<?php 			 			 			 $errors = validation_errors();if($errors !=""){?> <div class="validation_errors" style="padding:10px;"><?php echo $errors; ?>  </div><?php } ?>			 <?php echo $this->session->flashdata('message');?>
<form method="post" action="">
<div class="box box-primary">
            <div class="box-header">
              <h3 class="box-title">Choose month &  Year to get the report</h3>
            </div><div class="box-body"><div class="form-group">                  <label  >School :</label> <div class="input-group date">                  <?php $school_id =$this->input->post("school_id");				  echo  school_selection($school_id);?>                  </div>                </div>				 
            <div class="box-body">
              <!-- Date -->
              <div class="form-group">
                <label>Month &  Year:</label>

                <div class="input-group date">
                  
                  <select name="month" id="month" required >					<option value="">Select Month</option>					<?php 					$month_selected = $this->input->post("month");					$months = array("01"=>"January","02"=>"February","03"=>"March","04"=>"April","05"=>"May",									"06"=>"June","07"=>"July","08"=>"August","09"=>"September","10"=>"October","11"=>"November","12"=>"December");					foreach($months as $key_month =>$month_name) { 					$selected = '';					if($key_month == $month_selected){ $selected =  " selected =selected ";}					echo '<option value="'.$key_month .'" '. $selected .'>'.$month_name.'</option>';					  } ?>					  </select> 					  <select name="year" id="year" required >					<option value="">Select Year</option>					<?php 					$year_selected = $this->input->post("year");					for($i=date('Y');$i>=2017;$i--)					{ 							$selected = '';							if( $i == $year_selected){ 												$selected =  " selected =selected ";											}								echo '<option value="'.$i .'" '. $selected .'>'.$i.'</option>';					}					?>					  </select> 
                </div>
                <!-- /.input group -->
              </div>
			  
			  			  			  			    <div class="form-group">                                <div  >						                   <label> <input type="checkbox" name="exclude"     checked   value="exclude"       >&nbsp;&nbsp; 				  Exclude Unused items				</label>                                 </div>                <!-- /.input group -->              </div>
              <!-- /.form group -->

              <!-- Date range -->
              <div class="box-footer">
                 
                <input type="submit" class="btn btn-info pull-right" value="Get Report" name="submit"> 
				 <br><br>
               <input type="submit" class="btn btn-info pull-right" value="Download Report" name="submit">  
              </div>
              <!-- /.form group -->

              <!-- Date and time range -->
              
              <!-- /.form group -->

              <!-- Date and time range -->
              
              <!-- /.form group -->

            </div>
            <!-- /.box-body -->
          </div>
		  </form></div></div> 
 <script>
  $( function() {
			$( ".datepicker" ).datepicker({ 
			startDate: '09-01-2018',
			endDate: '+0d'});
  } );
  </script>
  <?php if(isset($display_result) && $display_result ){ ?>
  <table class="table table-bordered table-striped  "  >
	<tr class='bold'><td align="center"><?php echo $this->config->item('society_name'); ?>,<?php echo $school_name; ?></td></tr>
	<tr class='bold'><td align="center">DIET EXPENDITURE STATEMENT FOR THE dates between <span class='red'><b><?php echo $f_fromdate . "</b></span> and  <span class='red'><b>". $f_todate;?></b></span></td></tr>
	<tr class='bold'><td align="center">Note: Qty will be measures in Kg / Litre / Unit / Dozens . O/b means Opening Balance</b></span></td></tr>
	<tr>
		<td>
			<table id="example1"  class="table table-bordered table-striped  " role="grid" aria-describedby="example1_info"> 
				 
				<tr class='bold'>
					<td>SLNO</td>
					<td>Item</td>
					<!--- Opening balance COLUMNS -->
					<td>O/b Qty</td>					<td>O/b Rate</td>					<td>O/b Amount</td>
				 
					<!--- PURCHASE COLUMNS -->
					<td>Purchase  Qty</td >					<td>Purchase  Rate</td > 
					<td>Purchase  Amount</td >
					
					<!---- Total columns -->
					<td>Total Qty</td>					<td> Total Rate</td>					<td>Total Amount</td>
					 
					<!-- Consumption Columns -->
					<td>Consumption Qty</td>					
					<td>Consumption Rate  </td>
					
					 <td>Consumption Amount </td>
					 <!-- Closing Balance -->					<td>Closing Qty</td>					<td>Closing Rate</td>					<td>Closing Amount</td>
				</tr>
				<?php $i=1; 
				$total_amount = 0;				$ob_total_amount = 0;				$purchase_total_amount = 0;				$total_total_amount = 0;				$total_consumed_amount = 0;				$total_closing_amount = 0 ;
				foreach($items as $item_id=>$printitem){ 						//print_a($printitem,1);
				if($exclude=="exclude" && $printitem['consumed_quantity']==0)						continue;
				 				 $purchase_total_amount =  $purchase_total_amount + $printitem['purchase_total'];					$ob_total_amount = $ob_total_amount + $printitem['opening_total'];					$total_total_amount = $total_total_amount + $printitem['total_price'];					$total_consumed_amount = $total_consumed_amount + $printitem['consumed_total'];					$total_closing_amount = $total_closing_amount + $printitem['closing_total'];
	 
								?>
							<tr >
								<td><?php echo $i;?></td>
								<td><?php echo  $itemnames[$item_id];?></td>
								<!--- Opening balance COLUMNS -->
								<td><?php echo number_format($printitem['opening_quantity'],3,'.', '');?></td>								<td><?php echo number_format($printitem['opening_price'],2,'.', '');?></td>								<td><?php echo number_format($printitem['opening_total'],2,'.', '');?></td>
							  
								<!--- PURCHASE COLUMNS -->
								<td><?php echo number_format($printitem['purchase_qty'],3,'.', '');?></td>								 								<td><?php echo number_format($printitem['purchase_price'],2,'.', '');?></td>								 								<td><?php echo number_format($printitem['purchase_total'],2,'.', '');?></td>								 
					
								<!---- Total columns -->
								<td><?php echo number_format($printitem['total_qty'],3,'.', '');?></td>								<td><?php echo number_format($printitem['total_rate'],2,'.', '');?></td>								<td><?php echo number_format($printitem['total_price'],2,'.', '');?></td>
					
								<!-- Consumption Columns -->
								<td><?php echo number_format($printitem['consumed_quantity'],3,'.', '');?></td>	 
								<td><?php echo number_format($printitem['consumed_rate'],2,'.', '');?></td>								<td><?php echo number_format($printitem['consumed_total'],2,'.', '');?></td>
								<!-- Closing Balance -->								<td><?php echo number_format($printitem['closing_quantity'],3,'.', '');?></td>								<td><?php echo number_format($printitem['closing_price'],2,'.', '');?></td>								<td><?php echo number_format($printitem['closing_total'],2,'.', '');?></td>
								 
							</tr>
				<?php $i++; } ?>
				<tr class="bold">						<td colspan="4" align="right">Opening Balance Amount: </td>						<td align="right"><?php echo  number_format( $ob_total_amount,2); ?> </td>						<td colspan="2" align="right">Purchase Amount: </td>						<td><?php echo  number_format($purchase_total_amount,2); ?> </td>						<td colspan="2" align="right">Total Amount: </td>						<td><?php echo  number_format($total_total_amount,2); ?> </td>												<td colspan="2" align="right">Consumption Amount: </td>						<td><?php echo  number_format($total_consumed_amount,2); ?> </td>												<td colspan="2" align="right">Closing Amount: </td>						<td><?php echo  number_format($total_closing_amount,2); ?> </td>				 				 </tr>
			</table>
		
		
		
		
		
		</td></tr>
		

</table>
 
	 
  <?php   }?> 