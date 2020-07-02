<?php  echo $this->session->flashdata('message'); 
<h3><?php echo $school_name;?> - Employees List</span></h3>
<A href="<?php echo site_url();?>employees/processform/add/<?php echo $school_id;?>/0/regular" class="btn btn-primary">Add New Regular Employee</a>

<div class="box">
            
            <!-- /.box-header -->
            <div class="box-body   table-responsive">
               
			  
			  <table id="example1" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
                <thead>
                <tr role="row">
				<th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending"  aria-label="Rendering engine: activate to sort column descending" style="width: 126px;">Employee Name</th>
				  <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"  aria-label="Engine version: activate to sort column ascending" style="width: 106px;">Gender</th>
				<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="CSS grade: activate to sort column ascending" style="width: 75px;">Action</th> 
				</tr>
                </thead>
                <tbody>
                 <?php 
			 
				 foreach($rset->result() as $item) {  
                  <td class="sorting_1">
				 <?php echo  $item->fullname ;?>   </td> 
                  <td> <?php echo  $item->post_title;?></td>
				<td><a  class='selector' href='<?php echo site_url();?>employees/processform/edit/<?php echo $item->school_id;?>/<?php echo $item->emp_id;?>/<?php echo $item->employee_type;?>'> Edit  Employee Info </a></td> 
				 <?php } ?>   
                
				
                </tbody>
                
              </table>
			  
            </div>
            <!-- /.box-body -->
          </div>
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
	 