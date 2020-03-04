<style>
.daily table td{
	padding:5px;
}

</style>
 
<h3><?php echo ucfirst($lock_type);?> Lock Logs List  </h3>
 

<div class="box">
            <div class="box-header">
             
            </div>
            <!-- /.box-header -->
            <div class="box-body">
               
			   <a href='<?php echo site_url("locks/add/".$lock_type);?>' class='btn btn-info   noprint'  >Update <?php echo $lock_type;?> Lock</a>
			   <a href='#' class='btn btn-info pull-right noprint' onclick='javascript:window.print();'>Print</a>
			  <br><br>
			  <table id="example1" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
                <thead>
                <tr role="row">
				<th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" 
				aria-label="Rendering engine: activate to sort column descending" style="width: 126px;">Lock Type</th> 
				
				 <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Engine version: activate to sort column ascending" style="width: 106px;">Updated By</th> 
				 <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Engine version: activate to sort column ascending" style="width: 106px;">Lock Date</th> 
				<!--
				 <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Engine version: activate to sort column ascending" style="width: 106px;">Status</th> 
				
				<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Platform(s): activate to sort column ascending" style="width: 139px;">IP Address</th>
				
				<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Platform(s): activate to sort column ascending" style="width: 139px;">Updated Time</th>
				<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Platform(s): activate to sort column ascending" style="width: 139px;">Comment</th>
				-->
				 
				</tr>
                </thead>
                <tbody>
                 <?php 
					$status_list  = array("0"=> "Not Active","1"=>"Active");
				 foreach($locks_list->result() as $item) {  
				 
						 
				 ?>                <tr role="row" class="odd">
                  <td class="sorting_1"> <b> <?php echo ucfirst($item->lock_type);?></b></td> 
				
                   <td> <?php echo $item->name; ?>   </td> 
                   <td> <?php echo $item->entry_date_dp; ?>   </td> 
                   <!--  <td> <?php echo $status_list[$item->status]; ?>   </td> 
				  
				   </td>  <td> <?php echo  $item->ip_address; ?>   </td> 
				   </td>  <td> <?php echo  $item->posted_time; ?>   </td>  
				   </td>  <td> <?php echo  $item->comment; ?>   </td>  
				   -->
				   	 
                </tr>
				 <?php 
				 
						 
				 } ?>
				
                </tbody>
                
              </table>
			  
            </div>
            <!-- /.box-body -->
          </div>
		  <script>
  $(function () {
  //  $("#example1").DataTable();
    $('#example1').DataTable({
		"pageLength": 300,
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": false,
      "info": true,
	  
      "autoWidth": true
    });
  });
</script> 