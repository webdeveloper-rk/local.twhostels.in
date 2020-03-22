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
<?php 
<form method="post" action="">
<div class="box box-primary">
            <div class="box-header">
              <h3 class="box-title">Choose month &  Year to get the report</h3>
            </div><div class="box-body">
            <div class="box-body">
              <!-- Date -->
              <div class="form-group">
                <label>Month &  Year:</label>

                <div class="input-group date">
                  
                  <select name="month" id="month" required >
                </div>
                <!-- /.input group -->
              </div>
			  
			  
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
		  </form>
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
					<td>O/b Qty</td>
				 
					<!--- PURCHASE COLUMNS -->
					<td>Purchase  Qty</td >
					<td>Purchase  Amount</td >
					
					<!---- Total columns -->
					<td>Total Qty</td>
					 
					<!-- Consumption Columns -->
					<td>Consumption Qty</td>					
					<td>Consumption Rate  </td>
					
					 <td>Consumption Amount </td>
					 <!-- Closing Balance -->
				</tr>
				<?php $i=1; 
				$total_amount = 0;
				foreach($items as $item_id=>$printitem){ 
				if($exclude=="exclude" && $printitem['consumed_quantity']==0)
				 
	 
								?>
							<tr >
								<td><?php echo $i;?></td>
								<td><?php echo  $itemnames[$item_id];?></td>
								<!--- Opening balance COLUMNS -->
								<td><?php echo number_format($printitem['opening_quantity'],3,'.', '');?></td>
							  
								<!--- PURCHASE COLUMNS -->
								<td><?php echo number_format($printitem['purchase_qty'],3,'.', '');?></td>								 
					
								<!---- Total columns -->
								<td><?php echo number_format($printitem['total_qty'],3,'.', '');?></td>
					
								<!-- Consumption Columns -->
								<td><?php echo number_format($printitem['consumed_quantity'],3,'.', '');?></td>	 
								<td><?php echo number_format($printitem['consumed_rate'],2,'.', '');?></td>
								
								 
							</tr>
				<?php $i++; } ?>
				<tr class="bold">
			</table>
		
		
		
		
		
		</td></tr>
		

</table>
 
	 
  <?php 