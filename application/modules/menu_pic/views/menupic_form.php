<div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Menu Picture upload</b>  </b></h3>
			   
            </div>
            <!-- /.box-header -->
            <!-- form start -->
         <?php   $attributes = array('class' => 'email', 'id' => 'myform');
echo form_open_multipart(''); 	

$errors = validation_errors();
if($errors !=""){
?>
 <div class="validation_errors"><?php echo validation_errors(); ?>  </div>
<?php } 
	
	
	
?><div style='padding:10px;'><?php   echo $this->session->flashdata('message'); ?></div>
              <div class="box-body">
                <input type="hidden" value="1" name="bill_type">
                
                 <div class="form-group">
                  <label for="exampleInputEmail1">Menu Picture *</label>
                  
				 <input type="file" name='menu_image'   >
                </div>
              </div>
              <!-- /.box-body -->
 

              <div class="box-footer">
			  	
                <button type="submit" class="btn btn-primary">Submit</button>
			
  </div> 
           <?php form_close(); ?>
          </div> <div><h3>Current Menu Pic</h3>
		  <img src="<?php echo $menu_pic_path;?>" width="1024"  >