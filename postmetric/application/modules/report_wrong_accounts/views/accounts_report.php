<section class="content">
<div  >
             <h4><b>SUPPLIER Accounts   Report <b> 
            <!-- /.box-header -->
            <!-- form start -->
             
			<form   role="form" class="form-horizontal"   action=""  method="post"  onsubmit="return validate(this)">
<?php 

$errors = validation_errors();
if($errors !=""){
?>
 <div class="validation_errors"><?php echo validation_errors(); ?>  </div>
<?php } ?>

<?php echo $this->session->flashdata("message");?>             
			 <div class="box-body">
				  <div id="changepwdnotifier"></div>
	 
						 
              <!-- /.box-body -->
              <div class="box-footer">
                
                 
                <button type="submit"  name="submit"  value="download"  class="btn btn-info pull-right">Download</button>
              </div>
              <!-- /.box-footer -->
            </form>
          </div>
		  
		  

