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

 <h3 style='color:#FF0000'> </h3>   <h3> Item rates     </h3>
 <br>
<a href="<?php echo site_url("district_rates");?>" class="btn btn-info pull-right">Go back</a><br><br>
<div class="box">
            <div class="box-header">
             
            </div>
            <!-- /.box-header -->
            <div class="box-body">
               
			  
			  <table id="example1" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
                <thead>
                <tr role="row">
				<th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" 
				aria-label="Rendering engine: activate to sort column descending" style="width: 126px;">Item Name</th>
				 
				 
				
				<th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" 
				aria-label="Rendering engine: activate to sort column descending" style="width: 126px;">Rate per Kg/Litre/Units</th>
				
				
				<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="CSS grade: activate to sort column ascending" style="width: 75px;">Action</th></tr>
                </thead>
                <tbody>
                 <?php 
				 
				 foreach($rset->result() as $item_obj) { 
				 
		
				  
						  $encoded_item_id =  $this->ci_jwt->jwt_web_encode(array( 'district_id'=>$district_id, 'item_id'=>$item_obj->item_id,'item_title'=>$item_obj->item_name));	
				 ?>                <tr role="row" class="odd">
                  <td class="sorting_1"><a href='<?php echo site_url();?>district_rates/item_entry_form/<?php echo $encoded_item_id;?>'><?php echo $item_obj->item_name ;?></a></td> 
				  
				  
				  
				  <td class="sorting_1"> <?php echo $item_obj->price ;?></a></td> 
				  
              
                  <td><a href='<?php echo site_url();?>district_rates/item_entry_form/<?php echo $encoded_item_id;?>'>Update</a></td>
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
	   "order": [[ 1, "desc" ]],
      "autoWidth": true
    });
  });
</script>
	 