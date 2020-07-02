<section class="content">
<div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">  Districtwise Teacher Requirement Reports  </h3>
			  
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
                
               
			 
                <button type="submit"  name="submit"  value="download"  class="btn btn-info pull-right">Download</button>
              </div>
              <!-- /.box-footer -->
            </form>
          </div>
		  
	 
            <!-- /.box-body -->
          </div>
		  <script>
  $(function () {
  //  $("#example1").DataTable();
    $('#example11').DataTable({
		"pageLength": 3000,
      "paging": true,
      "lengthChange": false,
      "searching": true,
      "ordering": true,
      "info": true,
	   
      "autoWidth": true
    });
  });
</script>
	 
 
</section>

<!-- jQuery 1.10.2 -->
 
     
 
