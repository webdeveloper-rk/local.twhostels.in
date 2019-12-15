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

</style>


<?php echo $this->session->flashdata('message');?>

<h3>Classes - Quantities and rates    </h3>
 

<div class="box">
            <div class="box-header">
             
            </div>
            <!-- /.box-header -->
            <div class="box-body">
               
			  
			  <table id="example1" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
                <thead>
                <tr role="row">
				<th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" 
				aria-label="Rendering engine: activate to sort column descending" style="width: 126px;">Class Name</th>
				 
				 
				<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="CSS grade: activate to sort column ascending" style="width: 75px;">Action</th></tr>
                </thead>
                <tbody>
                 <?php 
				 
				 foreach($rset->result() as $class_obj) { 
				  
						  $encoded_class_id =  $this->ci_jwt->jwt_web_encode(array('class_id'=>$class_obj->class_id,'district_id'=>$district_id,'class_title'=>$class_obj->class_title));	
				 ?>                <tr role="row" class="odd">
                  <td class="sorting_1"><a href='<?php echo site_url();?>district_rates/list_items/<?php echo $encoded_class_id;?>'><?php echo $class_obj->class_title ;?></a></td> 
              
                  <td><a href='<?php echo site_url();?>district_rates/list_items/<?php echo $encoded_class_id;?>'>List Items</a></td>
                </tr>
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
		"pageLength": 300,
      "paging": true,
      "lengthChange": false,
      "searching": true,
      "ordering": true,
      "info": true,
	   "order": [[ 3, "desc" ]],
      "autoWidth": true
    });
  });
</script>
	 