 
<style>
.bold td
{
	font-weight:bold;
}
.red
{
	color:#FF0000;
}
body {
	background-image: none;
}
.w3-btn, .w3-btn:link, .w3-btn:visited {
    color: #FFFFFF;
    background-color: #4CAF50;
	margin:5px;
	padding:10px;
}
</style>
<?php $this->load->view("site_css");?>
<h3 style="padding:10px;">Consolidated Report </h3>
 
 
 
  <table class="table table-bordered table-striped  "  style="padding:5px;" >
	
	<tr class='bold'><td align="center">DIET EXPENDITURE STATEMENT FOR THE date of  <span class='red'><b><?php echo $from_date . "</b></span> ";?></b></span></td></tr>
	<tr class='bold'><td align="center">School : <?php echo $school_name;?></span> 
	 <a  class='w3-btn' href='<?php echo site_url('dreport');?>'>Go Back</a><br><br>

	</td></tr>
	<tr>
		<td>
			<table id="example1"  class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info"> 
				 
				<tr class='bold'>
					<td>SLNO</td>
					<td>Item</td>
					<!--- Opening balance COLUMNS -->
					<td>Opening Balance Qty</td>
				 
					<!--- PURCHASE COLUMNS -->
					<td>Purchase  Qty</td >
					
					<!---- Total columns -->
					<td>Total Qty</td>
					 
					<!-- Consumption Columns -->
					<td>Consumption Qty</td>					
					
					<!-- Closing Balance -->
					<td>Closing Qty</td>
					<td>Consumption Amount</td>
					 
				</tr>
				<?php $i=1; 
				 
				foreach($items   as $index=>$printitem){ 
				 
					
				 		 
				$total_qty = $printitem['opening_quantity']   + $printitem['purchase_qty'];
			 
								?>
							<tr >
								<td><?php echo $printitem['sno'];?></td>
								<td><?php echo  $printitem['item_name'];?></td>
								<!--- Opening balance COLUMNS -->
								<td><?php echo number_format($printitem['opening_quantity'],3,'.', '');?></td>
							  
								<!--- PURCHASE COLUMNS -->
								<td><?php echo number_format($printitem['purchase_qty'],3,'.', '');?></td>								 
					
								<!---- Total columns -->
								<td><?php echo number_format($total_qty ,3,'.', '');?></td>
					
								<!-- Consumption Columns -->
								<td><?php echo number_format($printitem['consumed_qty'],3,'.', '');?></td>					
								
								<!-- Closing Balance -->
								<td><?php echo number_format($printitem['closing_quantity'] ,3,'.', '');?></td>
								<td><?php echo number_format($printitem['consumed_amount'] ,2,'.', '');?></td>
								
								 
							</tr>
				<?php $i++; } ?>
				<tr class="bold"><td colspan="7" align="right">Total</td><td><?php echo  number_format($total_amount ,2,'.', '');?></td></tr>
			</table>
		
		
		
		
		
		</td></tr>
		

</table>
 

 