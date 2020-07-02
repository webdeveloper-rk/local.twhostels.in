<?php  echo $this->session->flashdata('message'); ?><div   >  
<h3><?php echo strtoupper($school_type);?> - Schools List</span></h3>

 
<div class="box">
            
            <!-- /.box-header -->
            <div class="box-body   table-responsive">
               
			  
			  <table id="example1" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
                <thead>
                <tr role="row">
				<th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending"  aria-label="Rendering engine: activate to sort column descending" style="width: 126px;">School Name</th>				<th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending"  aria-label="Rendering engine: activate to sort column descending" style="width: 126px;">Village Name</th>				<th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending"  aria-label="Rendering engine: activate to sort column descending" style="width: 126px;">REV Village Name</th>
				  <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"  aria-label="Engine version: activate to sort column ascending" style="width: 106px;">District</th>				  <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"  aria-label="Engine version: activate to sort column ascending" style="width: 106px;">IS Agency ?</th>
				<!--<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"  aria-label="Engine version: activate to sort column ascending" style="width: 106px;">Regular Employees</th>							 
				<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Engine version: activate to sort column ascending" style="width: 106px;">CRT Employees</th>
				<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Engine version: activate to sort column ascending" style="width: 106px;">Total Employees</th> -->
				<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="CSS grade: activate to sort column ascending" style="width: 75px;">Action</th> 
				</tr>
                </thead>
                <tbody>
                 <?php 
			 
				 foreach($rset->result() as $item) { 				 				 									//echo "<pre>";print_r($item);echo "</pre>";die; 				 ?> 				 <tr role="row" class="odd">
                  <td class="sorting_1">
				  <a  class='selector' href='<?php echo site_url();?>employees/viewlist/<?php echo $item->school_id;?>'> <?php echo  $item->name ;?>  </a></td> 
                  <td> <?php echo  $item->village;?></td>                  <td> <?php echo  $item->rev_village;?> </td>                  <td> <?php echo  $item->dname;?> </td>                  <td> <?php echo  $item->school_agency;?> </td> 				                    <!--<td> 0 </td>                   <td> 0 </td>                   <td>0 </td>--> 
				<td><a  class='selector' href='<?php echo site_url();?>employees/viewlist/<?php echo $item->school_id;?>'> View Employee List </a></td> 								</tr>
				 <?php } ?>   				 
                					 
				
                </tbody>
                
              </table> 
			  
            </div>
            <!-- /.box-body -->
          </div><div id="divInDialog"><div>
		  <script>
  $(function () {
  //  $("#example1").DataTable();
    $('#example1').DataTable({
		"pageLength": 2000,
      "paging": true,
      "lengthChange": false,
      "searching": true,
      "ordering": true,
      "info": true,
	    "order": [[ 3, "desc" ]],
      "autoWidth": false
    });
  });     
</script>
	 