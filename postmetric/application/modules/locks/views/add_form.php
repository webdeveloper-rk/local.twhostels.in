<style>
.bold td
{
	font-weight:bold;
}
.red
{
	color:#FF0000;
}
</style>
<h3><?php echo ucfirst($lock_type);?> Update Lock Date Form</h3>
<?php 
$errors = validation_errors();
if($errors !=""){
?>
 <div class="validation_errors"><?php echo validation_errors(); ?>  </div>
<?php } ?>
<?php echo $this->session->flashdata('message');?>

<form method="post" action="" name="updateentries" id="updateentries"  >
<input type="hidden" name="entry_id" value="<?php echo @$form_data['entry_id'];?>">
<div class="box box-primary">
            <div class="box-header">
              <h3 class="box-title">Enter the below details</h3>
            </div>
            		  
              <!-- /.form group -->

			   <!-- Date -->
              <div class="box-body"> 
                   <label>&nbsp;&nbsp;Today Date:&nbsp;&nbsp; &nbsp;&nbsp; <span style="color:#ff0000"><b><?php echo date('d-M-Y');?></b></span></label>
                  
                 
              </div>
              <!-- /.form group -->
 <div class="box-body"> 
                   <label>&nbsp;&nbsp;Current Locked Date:&nbsp;&nbsp; &nbsp;&nbsp; <span style="color:#ff0000"><b><?php echo $locked_date;?></b></span></label>
                  
                 
              </div>
			<!-- Date -->
              <div class="box-body">
                <label>Proposed New Lock Date:</label>

                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="text" required name="entry_date" value=""    class="form-control   datepicker" style="width:300px"    >
                </div>
                <!-- /.input group -->
              </div>
              <!-- /.form group -->
			 
		 
			<div class="box-body">
                <label>Explanation to update lock date:</label>

                <div class="input-group date">
					<textarea rows="5" cols="130" name="comment" id="comment" required></textarea>
                   
                </div>
                <!-- /.input group -->
              </div>
              <!-- /.form group --> 	 
               
           
              </div>
			 <div id="changepwdnotifier" style="margin-left:30px;"></div>
			  <!-- Date range -->
              <div class="box-footer">&nbsp;&nbsp;&nbsp;&nbsp;
                 
                <input type="submit" class="btn btn-info pull-right" value="Submit" name="submit"> 
				<a href='<?php echo site_url('locks/index/'.$lock_type); ?>' onclick="return confirm('Are you sure to cancel?')" class="btn btn-info pull-right">Cancel</a>&nbsp;&nbsp;&nbsp;&nbsp;
				 <div id="error_div"> </div>
              </div>
              <!-- /.form group -->

              <!-- Date and time range -->
              
              <!-- /.form group -->

              <!-- Date and time range -->
              
              <!-- /.form group -->

            </div>
            <!-- /.box-body -->
          </div>
		  </form>
		<script src="<?php echo site_url(); ?>assets/admin/js/jquery-1.10.2.min.js"></script>


<script src="<?php echo site_url(); ?>assets/admin/js/jquery.form.js"></script>  
 <script>
  $( function() {
			$( ".datepicker" ).datepicker({ 
			startDate: '09-01-2016',
			endDate: '+0d'});
  } );
  </script>
  
		
		
 
 
	 

<script type="text/javascript">


    $(document).ready(function() {


        $('#updateentries').ajaxForm({beforeSubmit : function(arr, $form, options){
                $("#changepwdnotifier").html("<div class='alert alert-warning'><h3>Updating please wait...</h3></div>");
                 $("#error_div").html('');


          },dataType: 'json', success: processJson});


        


    });


    function processJson(data) {

		//alert(data);
        if (data.success) {


            $("#changepwdnotifier").html(data.message);


             setTimeout(function() {


                window.location = "<?php echo site_url('locks/index/'.$lock_type); ?>";


            }, 3000); 


			 


			 document.getElementById("updateentries").reset();


        } else {


            $("#changepwdnotifier").html(data.message); 


        }


    }


</script>

  