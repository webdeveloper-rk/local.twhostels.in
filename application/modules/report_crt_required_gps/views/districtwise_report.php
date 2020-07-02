<section class="content">
<div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title"> GPS -  Districtwise  CRT REQUIRED  Reports  </h3>
			  
            </div>
            <!-- /.box-header -->
            <!-- form start -->
             <?php 

$errors = validation_errors();
if($errors !=""){
?>
 <div class="validation_errors"><?php echo validation_errors(); ?>  </div>
<?php } ?>

<?php echo $this->session->flashdata("message");?>
			<form   role="form" class="form-horizontal"   action=""  method="post"  onsubmit="return validate(this)">
              <div class="box-body">
				  <div id="changepwdnotifier"></div>
				   <?php if($this->session->userdata("user_role")=="subadmin"){?>		
 
					 
				  			 <div class="form-group" id="fg_district"   >
                  <label for="inputEmail3" class="col-sm-2 control-label">District Name:</label>

                  <div class="col-sm-10">
                  <select name="district_id" id="district_id"  required >
						<option value="">Select District</option>
						<?php 
						$dis_selected = $this->input->post('district_id');
						foreach($districts_list->result() as $districtObj)
						{
							$selected_text = '';
							if($dis_selected == $districtObj->district_id){
								$selected_text = ' selected ';
							}
						 ?>	
						<option <?php echo $selected_text ;?>  value="<?php echo $districtObj->district_id;?>"><?php echo $districtObj->name;?></option>
						<?php } ?>
					</select>
                    
                  </div>
                </div>
					 
					 
				  			 
				  
				   <?php } ?>
				   <input type="hidden" name="type" value="district">
				 
           						 
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                
               <input type="submit"  name="submit"     class="btn btn-info pull-right" value="Download">
                <input type="submit"  name="submit"     class="btn btn-info pull-right" value="View Report">
              </div>
              <!-- /.box-footer -->
            </form>
          </div>
		  
	  <?php if($display_result ==true) {
		
		 ?>

 
<h3>TRIBAL WELFARE DEPARTMENT  ,<?php echo $rdata['district_name'];?> DISTRICT  - GPS - CRT REQUIRED  Reports												
 </h3>
 

<div class="box">
            <div class="box-header">
             
            </div>
            <!-- /.box-header -->
            <div class="box-body">
               
			   <a href='#' class='btn btn-info pull-right noprint' onclick='javascript:window.print();'>Print</a>
			  
			  <table id="example1" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info" style="font-size:12px;">
                <thead>
                <tr role="row">
				<th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" 
				aria-label="Rendering engine: activate to sort column descending" style="width: 126px;"> S.NO</th>
				 																

				 
				 			 		

				
				 <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Engine version: activate to sort column ascending" style="width: 106px;">Name of GPS</th> 
				
				 
				
				<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Platform(s): activate to sort column ascending" style="width: 139px;">Agency / Non Agency</th>
				  
				 <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Platform(s): activate to sort column ascending" style="width: 139px;">Secondary grade CRT Required.</th>
				 
				 
				</tr>
                </thead>
                <tbody>
                  <?php 
				 $sno = 1;
				 $grand_total = 0;
				 $posts_total = array();
				 $posts_array = array(2);
				foreach($rows as  $index=>$crt_required_data){ 
				
				$school_info = $crt_required_data['school_info'];
				$posts_info = $crt_required_data['posts'];
				 
						
						
						$total_required = 	intval($posts_info[2]) ;
						foreach($posts_array as $post_id)
						{
							$posts_total[$post_id] = intval($posts_total[$post_id]) + intval($posts_info[$post_id]);
						}		
					$grand_total =  $grand_total + 	$total_required;			
											
											

				?>
						<tr>
								<td><?php echo $sno ;?></td>
								<td><?php echo $school_info->name;?></td>
								 
								<td><?php echo $school_info->school_agency;?></td>
						
							 
							<td  ><B><?php echo intval($total_required);?></b></td> 
							 
				</tr> 
					 
				<?php $sno++; } ?>
				<tr style="font-weight:bold;">
								<td></td>
								<td></td>
								 
								<td>Grand Total</td>
						
							 
							<td  ><?php echo intval($grand_total);?></td> 
							 
				</tr> 
                </tbody>
                
              </table>
			  
            </div>
            <!-- /.box-body -->
          </div>
		  <script>
  $(function () {
 
    $('#example1').DataTable({
		"pageLength": 300,
      "paging": false,
      "lengthChange": false,
      "searching": true,
      "ordering": false,
      "info": true,
	  
      "autoWidth": true
    });
  });
</script>
	   <!-- /.box-body -->
         
<?php } ?> </div>
 
</section>

<!-- jQuery 1.10.2 -->
 
     
 
