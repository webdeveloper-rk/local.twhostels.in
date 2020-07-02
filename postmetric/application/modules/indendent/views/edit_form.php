<section class="content">
 

<div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">   Indent  Edit form :: <b><?php echo $school_info->school_code." - ".$school_info->name;?> :: <?php echo $dp_date ;?> - <?php echo $indent_data->item_name;?> </b></h3>
			  <?php 
$multiples = intval($this->input->post("multiples"));
if($multiples<=0){
	$multiples = 1;
}
$errors = validation_errors();

if($errors !=""){

?>

 <div class="validation_errors"><?php echo validation_errors(); ?>  </div>

<?php 
}
echo $this->session->flashdata('message');
 ?>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
             
			<form   role="form" class="form-horizontal"   action=""  method="post"  onsubmit="return validate(this)">
              <div class="box-body">
				  <div id="changepwdnotifier"></div>
				    <div class="form-group"   id="fg_school" style=" ">
                  <label for="inputEmail3" class="col-sm-2 control-label">Item Name:</label>

                  <div class="col-sm-10"><b><?php echo $indent_data->item_name;?></b>
				  </div>
				  </div>
				  
				   <div class="form-group"   id="fg_school" style=" ">
                  <label for="inputEmail3" class="col-sm-2 control-label">System generated Quantity:</label>

                  <div class="col-sm-10"><b><?php echo $indent_data->balance_qty;  //print_a( $indent_data);?></b>
				  </div>
				  </div>
				  
				   <div class="form-group"   id="fg_school" style=" ">
                  <label for="inputEmail3" class="col-sm-2 control-label">DTDO Raising Quantity:</label>

                  <div class="col-sm-10"><b>
				  <input type="text" class="form " name="dtdo_quantity" value="<?php echo $indent_data->indent_raised_by_dtdo; ?>">
				  <input type="hidden" class="form " name="indent_auto_id" value="<?php echo $indent_data->indent_auto_id; ?>">
				  <input type="hidden"  name="action" value="updateindenet">
				  
				  </b>
				  </div>
				  </div>
				  
				   
				  			 
					  
				    
 
					 
				   
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                
                <button type="submit" name="submit"  value="getreport" class="btn btn-info pull-right">Update</button> 
			 <!--
                <button type="submit"  name="submit"  value="download"  class="btn btn-info pull-right">Download</button>-->
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
 
     
<script type="text/javascript">
 
    function validate(form) {
       
	   if(form.school_code.value.trim()=="")
	   {
		   alert("Please enter school_code");
		   form.school_code.focus();
		   return false;
	   }
	    if(form.school_date.value.trim()=="")
	   {
		   alert("Please select date");
		   form.school_date.focus();
		   return false;
	   }
    }
</script>
 <script>
  $( function() {
			$( ".datepicker" ).datepicker({ 
			startDate: '09-01-2016',
			endDate: '+0d'});
  } );
  function showElement(sval)
  {
	//  console.log(sval);
	  switch(sval)
	  {
		  case 'district':
							$("#fg_school").hide();
							$("#fg_district").show();
					break;
		  case 'school':
							$("#fg_school").show();
							$("#fg_district").hide();
					break;
		default: 
						$("#fg_school").hide();
							$("#fg_district").hide();
					break;
	  }
  }
  function validate(frm)
  {
	  
	  rtype = $('input[name=type]:checked').val();
	  
	  if(rtype=="school")
	  {
		  if($("#school_id").val()==""){
				  alert("Please select School ");
				  $("#school_id").focus();
				  return false ;
		  }
	  }
	   if(rtype=="district")
	  {
		   if($("#district_id").val()==""){
						  alert("Please select district ");
						  $("#district_id").focus();
						  return false ;
		   }
	  }
  }
  </script>

