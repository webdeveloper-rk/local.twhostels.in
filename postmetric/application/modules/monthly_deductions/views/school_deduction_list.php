 
  <style>
  <table id="example1" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
	 
	<tr>
		<td>
			<table id="example2"  class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example2_info"> 
				 
				<tr class='bold'>
					<td aria-controls="example1">SLNO</td>
					
					<td aria-controls="example1">Date</td>
					 <td aria-controls="example1">Item Name</td>
					
					 
				</tr>
				<?php $i=1; 
				$total_amount = 0;
				foreach($deduction_rs->result() as   $deduction_data){ 
							<tr > 
							<td><?php echo $i;?></td> 
							<td><?php echo $deduction_data->edate;?></td>
				<?php $i++; } ?>
				 
			</table>
		
		
		
		
		
		</td></tr>
		

</table>