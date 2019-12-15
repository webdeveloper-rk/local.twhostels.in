<style>
.alert-success
{
	 color: #FFF;
    background-color: #4CAF50;
    border-color: #ebccd1;
}
.alert {
    padding: 15px;
    margin-bottom: 20px;
    border: 1px solid transparent;
    border-radius: 4px;
}
.daily table{
	border-collapse: collapse;
	 border: 1px;
}
.daily   td{
	padding:5px;
}
.rowred td{
	color:#FF0000;
	font-weight:bold;
}
</style>


<?php echo $this->session->flashdata('message');?>

<h3>Itemwise Top Spenders  on <?php echo $report_date;?>  </h3>
<b> Total schools count : <?php echo $rset->num_rows();?></b> <a href="javascript:window.history.back();;"><b>Go back</b></a>
 

<div class="box">
            <div class="box-header">
             
            </div>
            <!-- /.box-header -->
            <div class="box-body">
               
			  
			  <table id="example1" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
                <thead>
                <tr role="row">
				<th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" 
				aria-label="Rendering engine: activate to sort column descending" style="width: 126px;">School Name</th>
				<!--<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Browser: activate to sort column ascending" style="width: 159px;">Current Balance</th>-->
				 
				
				
				 <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Engine version: activate to sort column ascending" style="width: 106px;">School Code</th> 
				<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Platform(s): activate to sort column ascending" style="width: 139px;">Item Name</th>
				<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Platform(s): activate to sort column ascending" style="width: 139px;">Total Quantity</th><th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 				aria-label="Platform(s): activate to sort column ascending" style="width: 139px;">Avg Price</th>
				 	<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 				aria-label="Platform(s): activate to sort column ascending" style="width: 139px;">Total Amount</th>													 
				</tr>
                </thead>
                <tbody>
                 <?php 
				 
				 foreach($rset->result() as $school) {  ?>               				 <tr role="row " class="odd ">
                  <td class="sorting_1"> 
				 <b> <?php echo $school->name;?></b></td>
                  
				
                   <td>
					 <?php echo $school->school_code; ?> 
				   </td> 
				    <td> <?php echo $school->telugu_name."-".  $school->item_name; ?>  
				   </td> 
				    <td>
					<?php echo $school->used_qty ; ?>  
				   </td> 					<td>					<?php echo number_format($school->total_price/$school->used_qty,2 ,'.', '') ; ?>  				   </td> 
				      <td>					<?php echo number_format($school->total_price,2, '.', '') ; ?>  				   </td> 
                </tr>
				 <?php } ?>
				
                </tbody>
                
              </table>
			  
            </div>
            <!-- /.box-body -->
          </div>
		  
	 